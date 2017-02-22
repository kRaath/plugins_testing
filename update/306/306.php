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
    
    // ###############################
    // Version worauf upgedated wird
    $nVersionAfter = 307;
    // ###############################
    
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
                        if ($nRow > $oVersion->nZeileBis) {//updateFertig($nVersionAfter); // Fertig
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
                                    //updateFertig($nVersionAfter); // Fertig
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
                    //updateFertig($nVersionAfter); // Fertig
                    naechsterUpdateStep(2, 1);
                }
            } else {// Fertig!
                //updateFertig($nVersionAfter); // Fertig
                naechsterUpdateStep(2, 1);
            }
            break;
           
        case 2:
            if ($oVersion->nZeileVon <= $oVersion->nZeileBis) {
                // Checkboxen deaktivieren falls Einstellung von Datenschutz und Newsletter auf Nein
                $oEinstellung = $GLOBALS['DB']->executeQuery("SELECT cWert FROM teinstellungen WHERE cName = 'kundenregistrierung_datenschutz_checkbox'", 1);
                if (isset($oEinstellung->cWert) && $oEinstellung->cWert === "N") {
                    $GLOBALS['DB']->executeQuery("UPDATE tcheckbox SET nAktiv=0 WHERE kCheckBox=19", 3);
                }
                
                $oEinstellung = $GLOBALS['DB']->executeQuery("SELECT cWert FROM teinstellungen WHERE cName = 'kundenregistrierung_newsletterabonnieren_anzeigen'", 1);
                if (isset($oEinstellung->cWert) && $oEinstellung->cWert === "N") {
                    $GLOBALS['DB']->executeQuery("UPDATE tcheckbox SET nAktiv=0 WHERE kCheckBox=17", 3);
                }
                
                $oVersion->nZeileVon++;
                $GLOBALS['DB']->executeQuery("UPDATE tversion SET nZeileVon = " . $oVersion->nZeileVon . ", nFehler=0, cFehlerSQL=''", 4);
                
                if ($oVersion->nZeileVon > $oVersion->nZeileBis) {
                    deleteSettingsUpdate();
                    updateFertig($nVersionAfter); // Fertig
                }
            } else {
                deleteSettingsUpdate();
                updateFertig($nVersionAfter); // Fertig
            }
            break;
    }
    
    // Abschluss
    $GLOBALS['DB']->executeQuery("UPDATE tversion SET nInArbeit = 0", 4);
    @fclose($file_handle);
    header("Location: " . URL_SHOP . "/" . PFAD_ADMIN . "dbupdater.php?nErrorCode=-1");
    exit();
    
    function deleteSettingsUpdate()
    {
        $GLOBALS['DB']->executeQuery("DELETE FROM teinstellungenconf WHERE kEinstellungenConf = 197", 3);
        $GLOBALS['DB']->executeQuery("DELETE FROM teinstellungen WHERE cName = 'kundenregistrierung_datenschutz_checkbox'", 3);
        $GLOBALS['DB']->executeQuery("DELETE FROM teinstellungenconf WHERE kEinstellungenConf = 627", 3);
        $GLOBALS['DB']->executeQuery("DELETE FROM teinstellungen WHERE cName = 'kundenregistrierung_newsletterabonnieren_anzeigen'", 3);
        
        // Billpay
        $oZahlungsart = $GLOBALS['DB']->executeQuery("SELECT kZahlungsart FROM tzahlungsart WHERE cModulId = 'za_billpay_jtl'", 1);
        if (!isset($oZahlungsart->kZahlungsart)) {
            $GLOBALS['DB']->executeQuery("INSERT INTO `tzahlungsart` (`kZahlungsart`, `cName`, `cModulId`, `cKundengruppen`, `cZusatzschrittTemplate`, `cPluginTemplate`, `cBild`, `nSort`, `nMailSenden`, `nActive`, `cAnbieter`, `cTSCode`, `nWaehrendBestellung`, `nCURL`, `nSOAP`, `nSOCKETS`, `nNutzbar`) VALUES (NULL, 'Billpay', 'za_billpay_jtl', '', 'checkout/modules/billpay/zusatzschritt.tpl', NULL, 'gfx/Billpay/LogoSmall_0.png', 0, 1, 1, 'Billpay GmbH', 'OTHER', 0, 1, 0, 0, 0);", 3);
        }
    }
