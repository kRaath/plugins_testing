<?php
    require_once("../../includes/config.JTL-Shop.ini.php");
    require_once(PFAD_ROOT . "includes/" . "defines.php");

    //existiert Konfiguration?
    if (!defined('DB_HOST')) {
        die("Kein MySql-Datenbank Host angegeben. Bitte config.JTL-Shop.ini.php bearbeiten!");
    }
    if (!defined('DB_NAME')) {
        die("Kein MySql Datenbanknamen angegeben. Bitte config.JTL-Shop.ini.php bearbeiten!");
    }
    if (!defined('DB_USER')) {
        die("Kein MySql-Datenbank Benutzer angegeben. Bitte config.JTL-Shop.ini.php bearbeiten!");
    }
    if (!defined('DB_PASS')) {
        die("Kein MySql-Datenbank Passwort angegeben. Bitte config.JTL-Shop.ini.php bearbeiten!");
    }

    require_once(PFAD_ROOT . PFAD_CLASSES_CORE."class.core.NiceDB.php");
    require_once(PFAD_ROOT . PFAD_INCLUDES."tools.Global.php");
    require_once(PFAD_ROOT . PFAD_ADMIN . PFAD_INCLUDES . "dbupdater_inc.php");
    
    //datenbankverbindung aufbauen
    $DB = new NiceDB(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    session_name("eSIdAdm");
    session_start();
    if (!isset($_SESSION['AdminAccount'])) {
        header('Location: ' . URL_SHOP . "/" . PFAD_ADMIN . "index.php");
        exit;
    }
    
    // ##### Anfang Script
    
    
    // Vorbereitung
    $nStartStamp = time();
    if (intval(ini_get('max_execution_time')) < 320) {
        @ini_set('max_execution_time', 320);
    }
    $nMaxLaufzeit = intval(ini_get('max_execution_time')) / 2;  // Maximale Laufzeit die das Script laufen darf
    //$nMaxLaufzeit = 2;
    $nEndeStamp = $nStartStamp + $nMaxLaufzeit;
    $cSQLDatei = "update1.sql";
    
    // ### Main Script
    if (intval($_GET['nFirstStart']) == 1) {
        resetteUpdateDB();                  // Fügt Spalten hinzu die vielleicht noch nicht vorhanden sind und setzt alle wichtigen Spalten auf 0
        updateZeilenBis($cSQLDatei);     // Läuft die Datei durch und zählt die Reihen. Danach wird die Anzahl in der DB hinterlegt.
    }
    
    $oVersion = $GLOBALS['DB']->executeQuery("SELECT * FROM tversion", 1);
    
    // Logging
    define("UPDATER_LOGFILE", PFAD_LOGFILES . "update_" . intval($oVersion->nVersion) . ".log");
    
    if (!file_exists($cSQLDatei)) {
        header("Location: " . URL_SHOP . "/" . PFAD_ADMIN . "dbupdater.php?nErrorCode=1");
        exit();
    }

    $GLOBALS['DB']->executeQuery("UPDATE tversion SET nInArbeit = 1", 4);
    $nRow = 1;
    
    switch ($oVersion->nTyp) {
        case 1:    // SQL
            $file_handle = @fopen($cSQLDatei, "r");
            if ($oVersion->nZeileVon <= $oVersion->nZeileBis) {
                while ($cData = fgets($file_handle)) {
                    if (time() < $nEndeStamp) {
                        if ($nRow > $oVersion->nZeileBis) {//updateFertig(305); // Fertig
                            naechsterUpdateStep(2, 1);
                        }
                        
                        if ($nRow >= $oVersion->nZeileVon) {
                            // Wurde bei einem SQL 3x ein Fehler ausgegeben?
                            if (intval($oVersion->nFehler) >= 3) {
                                @fclose($file_handle);
                                header("Location: " . URL_SHOP . "/" . PFAD_ADMIN . "dbupdater.php?nErrorCode=999");
                                exit();
                            }
                                                     
                            // SQL ausführen
                            $GLOBALS['DB']->executeQuery($cData, 4);
                    
                            $nErrno = $GLOBALS['DB']->DB()->errno;
                            
                            if (!$nErrno || $nErrno == 1062 || $nErrno == 1060 || $nErrno == 1267) {
                                writeLog(UPDATER_LOGFILE, $nRow . ": " . $cData . " erfolgreich ausgeführt. MySQL Errno: " . $nErrno . " - " . str_replace("'", "", $GLOBALS['DB']->DB()->error), 1);
                                $nRow++;
                                $GLOBALS['DB']->executeQuery("UPDATE tversion SET nZeileVon = " . $nRow . ", nFehler=0, cFehlerSQL=''", 4);
                                
                                if ($nRow > $oVersion->nZeileBis) {
                                    @fclose($file_handle);
                                    naechsterUpdateStep(2, 1);
                                }
                            } else {
                                if (strpos(strtolower($cData), "alter table")) {// Alter Table darf nicht nochmal ausgeführt werden
                                    $GLOBALS['DB']->executeQuery("UPDATE tversion SET nFehler=3, cFehlerSQL='Zeile " . $nRow . ": " . str_replace("'", "", $GLOBALS['DB']->DB()->error) . "'", 4);
                                } else {
                                    $GLOBALS['DB']->executeQuery("UPDATE tversion SET nFehler=nFehler+1, cFehlerSQL='Zeile " . $nRow . ": " . str_replace("'", "", $GLOBALS['DB']->DB()->error) . "'", 4);
                                }
                                
                                writeLog(UPDATER_LOGFILE, "Fehler in Zeile " . $nRow . ": " . str_replace("'", "", $GLOBALS['DB']->DB()->error), 1);
                                @fclose($file_handle);
                                $GLOBALS['DB']->executeQuery("UPDATE tversion SET nInArbeit = 0", 4);
                                header("Location: " . URL_SHOP . "/" . PFAD_ADMIN . "dbupdater.php?nErrorCode=1");
                                exit();
                            }
                        } else {
                            $nRow++;
                        }
                    } else {
                        break;
                    }
                }
                
                if ($nRow == $oVersion->nZeileBis) {// Fertig!
                    //updateFertig(305); // Fertig
                    naechsterUpdateStep(2, 1);
                }
            } else {// Fertig!
                //updateFertig(305); // Fertig
                naechsterUpdateStep(2, 1);
            }
            break;
            
        case 2:
            if ($oVersion->nZeileVon <= $oVersion->nZeileBis) {
                // Kampagne AUTO_INCREMENT fixen
                writeLog(UPDATER_LOGFILE, "### Kampagne AUTO_INCREMENT...", 1);
                $oTableStatus = $GLOBALS['DB']->executeQuery("SHOW TABLE STATUS FROM " . DB_NAME . " LIKE 'tkampagne'", 1);
                writeLog(UPDATER_LOGFILE, "### Kampagne TABLE STATUS: " . print_r($oTableStatus, 1), 1);
                
                if (isset($oTableStatus->Auto_increment) && intval($oTableStatus->Auto_increment) < 1000) {
                    $GLOBALS['DB']->executeQuery("UPDATE tkampagne SET kKampagne=kKampagne+1000 WHERE kKampagne > 3 && kKampagne < 1000", 3);
                    $GLOBALS['DB']->executeQuery("UPDATE tkampagnevorgang SET kKampagne=kKampagne+1000 WHERE kKampagne > 3 && kKampagne < 1000", 3);
                    writeLog(UPDATER_LOGFILE, "### Kampagne wurde geaendert.", 1);
                } else {
                    writeLog(UPDATER_LOGFILE, "### Kampagne wurde nicht geaendert.", 1);
                }
                    
                // TrustedShops CLASSIC ID übernehmen
                writeLog(UPDATER_LOGFILE, "### TrustedShops CLASSIC ID...", 1);
                $oEinstellungTS = $GLOBALS['DB']->executeQuery("SELECT * FROM teinstellungen WHERE cName = 'trusted_shops_id'", 1);
                if (isset($oEinstellungTS->cWert) && strlen($oEinstellungTS->cWert) > 0) {
                    writeLog(UPDATER_LOGFILE, "### TrustedShops CLASSIC ID existiert, ID: " . $oEinstellungTS->cWert, 1);
                    require_once(PFAD_ROOT . PFAD_CLASSES . "class.JTL-Shop.TrustedShops.php");
                    $oSprache = gibStandardsprache(true);
                    $oTrustedShops = new TrustedShops(-1, convertISO2ISO639($oSprache->cISO));
                    if (!isset($oTrustedShops->kTrustedShopsZertifikat) || !$oTrustedShops->kTrustedShopsZertifikat) {
                        $oZertifikat = new stdClass();
                        $oZertifikat->cTSID             = $oEinstellungTS->cWert;
                        $oZertifikat->cWSUser           = "";
                        $oZertifikat->cWSPasswort       = "";
                        $oZertifikat->cISOSprache       = convertISO2ISO639($oSprache->cISO);
                        $oZertifikat->nAktiv            = 0;
                        $oZertifikat->eType             = TS_BUYERPROT_CLASSIC;
                        $oZertifikat->dErstellt         = "now()";
                        
                        $nReturnValue = $oTrustedShops->speicherTrustedShopsZertifikat($oZertifikat);
                        writeLog(UPDATER_LOGFILE, "### TrustedShops CLASSIC oZertifikat: " . print_r($oZertifikat, 1), 1);
                        writeLog(UPDATER_LOGFILE, "### TrustedShops CLASSIC ReturnValue: " . $nReturnValue, 1);
                    } else {
                        writeLog(UPDATER_LOGFILE, "### TrustedShops Zertifikat existiert bereits: " . print_r($oTrustedShops, 1), 1);
                    }
                } else {
                    writeLog(UPDATER_LOGFILE, "### TrustedShops CLASSIC Einstellung nicht vorhanden", 1);
                }
                
                $oVersion->nZeileVon++;
                $GLOBALS['DB']->executeQuery("UPDATE tversion SET nZeileVon = " . $oVersion->nZeileVon . ", nFehler=0, cFehlerSQL=''", 4);
                
                if ($oVersion->nZeileVon > $oVersion->nZeileBis) {
                    $GLOBALS['DB']->executeQuery("DELETE FROM teinstellungen WHERE cName = 'trusted_shops_id'", 3);
                    updateFertig(305); // Fertig
                }
            } else {
                $GLOBALS['DB']->executeQuery("DELETE FROM teinstellungen WHERE cName = 'trusted_shops_id'", 3);
                updateFertig(305); // Fertig
            }
            break;
    }
    
    // Abschluss
    $GLOBALS['DB']->executeQuery("UPDATE tversion SET nInArbeit = 0", 4);
    @fclose($file_handle);
    header("Location: " . URL_SHOP . "/" . PFAD_ADMIN . "dbupdater.php?nErrorCode=-1");
    exit();
