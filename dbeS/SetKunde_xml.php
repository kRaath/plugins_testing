<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

require_once dirname(__FILE__) . '/syncinclude.php';
require_once PFAD_ROOT . PFAD_INCLUDES . 'mailTools.php';
require_once PFAD_ROOT . PFAD_INCLUDES . 'sprachfunktionen.php';

$return = 3;
$kKunde = 0;
if (auth()) {
    checkFile();
    $return  = 2;
    $archive = new PclZip($_FILES['data']['tmp_name']);

    if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
        Jtllog::writeLog('Entpacke: ' . $_FILES['data']['tmp_name'], JTLLOG_LEVEL_DEBUG, false, 'SetKunde_xml');
    }
    if ($list = $archive->listContent()) {
        if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
            Jtllog::writeLog('Anzahl Dateien im Zip: ' . count($list), JTLLOG_LEVEL_DEBUG, false, 'SetKunde_xml');
        }
        if ($archive->extract(PCLZIP_OPT_PATH, PFAD_SYNC_TMP)) {
            $return = 0;
            foreach ($list as $zip) {
                if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                    Jtllog::writeLog('bearbeite: ' . PFAD_SYNC_TMP . $zip['filename'] . ' size: ' . filesize(PFAD_SYNC_TMP . $zip['filename']), JTLLOG_LEVEL_DEBUG, false, 'SetKunde_xml');
                }
                $d   = file_get_contents(PFAD_SYNC_TMP . $zip['filename']);
                $xml = XML_unserialize($d);
                $res = bearbeite($xml);
            }
        } elseif (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
            Jtllog::writeLog('Error : ' . $archive->errorInfo(true), JTLLOG_LEVEL_ERROR, false, 'SetKunde_xml');
        }
    } elseif (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
        Jtllog::writeLog('Error : ' . $archive->errorInfo(true), JTLLOG_LEVEL_ERROR, false, 'SetKunde_xml');
    }
}

if ($return == 1) {
    syncException('Error : ' . $archive->errorInfo(true));
}

if (is_array($res)) {
    echo $return . ";\n" . XML_serialize($res);
} else {
    echo $return . ';' . $res;
}

/**
 * @param array $xml
 * @return array
 */
function bearbeite($xml)
{
    $res_obj              = array();
    $nr                   = 0;
    $Kunde                = new Kunde();
    $Kunde->kKundengruppe = 0;
    $oKundenattribut_arr  = array();

    if (is_array($xml['tkunde attr'])) {
        $Kunde->kKundengruppe = intval($xml['tkunde attr']['kKundengruppe']);
        $Kunde->kSprache      = intval($xml['tkunde attr']['kSprache']);
    }
    if (is_array($xml['tkunde'])) {
        mappe($Kunde, $xml['tkunde'], $GLOBALS['mKunde']);
        // Kundenattribute
        if (isset($xml['tkunde']['tkundenattribut']) && is_array($xml['tkunde']['tkundenattribut']) && count($xml['tkunde']['tkundenattribut']) > 0) {
            $cMember_arr = array_keys($xml['tkunde']['tkundenattribut']);

            if ($cMember_arr[0] == '0') {
                foreach ($xml['tkunde']['tkundenattribut'] as $oKundenattributTMP) {
                    unset($oKundenattribut);
                    $oKundenattribut        = new stdClass();
                    $oKundenattribut->cName = $oKundenattributTMP['cName'];
                    $oKundenattribut->cWert = $oKundenattributTMP['cWert'];
                    $oKundenattribut_arr[]  = $oKundenattribut;
                }
            } else {
                unset($oKundenattribut);
                $oKundenattribut        = new stdClass();
                $oKundenattribut->cName = $xml['tkunde']['tkundenattribut']['cName'];
                $oKundenattribut->cWert = $xml['tkunde']['tkundenattribut']['cWert'];
                $oKundenattribut_arr[]  = $oKundenattribut;
            }
        }
        //Mappe Anrede
        $Kunde->cAnrede = mappeWawiAnrede2ShopAnrede($Kunde->cAnrede);

        $oSprache = Shop::DB()->query("SELECT kSprache FROM tsprache WHERE kSprache = " . (int)$Kunde->kSprache, 1);
        if (empty($oSprache->kSprache)) {
            $oSprache        = Shop::DB()->query("SELECT kSprache FROM tsprache WHERE cShopStandard = 'Y'", 1);
            $Kunde->kSprache = $oSprache->kSprache;
        }

        $kInetKunde = intval($xml['tkunde attr']['kKunde']);
        $oKundeAlt  = new stdClass();
        if ($kInetKunde > 0) {
            $oKundeAlt = new Kunde($kInetKunde);
        }
        
        // Kunde existiert mit dieser kInetKunde
        // Kunde wird aktualisiert bzw. seine KdGrp wird geändert
        if (isset($oKundeAlt->kKunde) && $oKundeAlt->kKunde > 0) { // KUNDENAKTUALSIERUNG
            //Angaben vom alten Kunden übernehmen
            $Kunde->kKunde       = $kInetKunde;
            $Kunde->cAbgeholt    = 'Y';
            $Kunde->cAktiv       = 'Y';
            $Kunde->dVeraendert  = 'now()';
            
            //keep login-credentials! fixes jtlshop/jtl-shop#267
            $Kunde->cMail        = $oKundeAlt->cMail;
            $Kunde->cPasswort    = $oKundeAlt->cPasswort;
            
            $Kunde->nRegistriert = $oKundeAlt->nRegistriert;
            $Kunde->dErstellt    = $oKundeAlt->dErstellt;
            $Kunde->fGuthaben    = $oKundeAlt->fGuthaben;
            $Kunde->cHerkunft    = $oKundeAlt->cHerkunft;
            //schaue, ob dieser Kunde diese Kundengruppe schon hat
            if ($oKundeAlt->kKundengruppe != $Kunde->kKundengruppe && $Kunde->cMail) {
                //Mail an Kunden mit Info, dass Kundengruppe verändert wurde
                $obj         = new stdClass();
                $obj->tkunde = $Kunde;
                sendeMail(MAILTEMPLATE_KUNDENGRUPPE_ZUWEISEN, $obj);
            }
            // Hausnummer extrahieren
            extractStreet($Kunde);
            //DBUpdateInsert('tkunde', array($Kunde), 'kKunde');

            $Kunde->updateInDB();
            // Kundendatenhistory
            require_once PFAD_ROOT . PFAD_CLASSES . 'class.JTL-Shop.Kundendatenhistory.php';
            Kundendatenhistory::saveHistory($oKundeAlt, $Kunde, Kundendatenhistory::QUELLE_DBES);

            if (count($oKundenattribut_arr) > 0) {
                speicherKundenattribut($Kunde->kKunde, $Kunde->kSprache, $oKundenattribut_arr, false);
            }
            $res_obj['keys']['tkunde attr']['kKunde'] = $kInetKunde;
            $res_obj['keys']['tkunde']                = '';
        } else {
            // Kunde existiert mit dieser kInetKunde im Shop nicht. Gib diese Info zurück an Wawi
            if ($kInetKunde > 0) {
                $res_obj['keys']['tkunde attr']['kKunde'] = 0;
                $res_obj['keys']['tkunde']                = '';

                if (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
                    Jtllog::writeLog('Verknuepfter Kunde in Wawi existiert nicht im Shop: ' . XML_serialize($res_obj), JTLLOG_LEVEL_ERROR, false, 'SetKunde_xml');
                }

                return $res_obj;
            }
            //Kunde existiert nicht im Shop - check, ob email schon belegt
            $oKundeAlt = Shop::DB()->query("SELECT kKunde FROM tkunde WHERE nRegistriert = 1 AND cMail = '" . Shop::DB()->escape($Kunde->cMail) . "'", 1);
            if ($oKundeAlt->kKunde > 0) {
                //EMAIL SCHON BELEGT -> Kunde wird nicht neu angelegt, sondern der Kunde wird an Wawi zurückgegeben
                $xml_obj['kunden']['tkunde']      = Shop::DB()->query(
                    "SELECT kKunde, kKundengruppe, kSprache, cKundenNr, cPasswort, cAnrede, cTitel, cVorname,
                        cNachname, cFirma, cZusatz, cStrasse, cHausnummer, cAdressZusatz, cPLZ, cOrt, cBundesland, cLand, cTel,
                        cMobil, cFax, cMail, cUSTID, cWWW, fGuthaben, cNewsletter, dGeburtstag, fRabatt,
                        cHerkunft, dErstellt, dVeraendert, cAktiv, cAbgeholt,
                        date_format(dGeburtstag, '%d.%m.%Y') AS dGeburtstag_formatted,
                        nRegistriert
                        FROM tkunde
                        WHERE kKunde = " . (int)$oKundeAlt->kKunde, 9
                );
                $xml_obj['kunden attr']['anzahl'] = 1;

                $xml_obj['kunden']['tkunde'][0]['cNachname'] = trim(entschluesselXTEA($xml_obj['kunden']['tkunde'][0]['cNachname']));
                $xml_obj['kunden']['tkunde'][0]['cFirma']    = trim(entschluesselXTEA($xml_obj['kunden']['tkunde'][0]['cFirma']));
                $xml_obj['kunden']['tkunde'][0]['cZusatz']   = trim(entschluesselXTEA($xml_obj['kunden']['tkunde'][0]['cZusatz']));
                $xml_obj['kunden']['tkunde'][0]['cStrasse']  = trim(entschluesselXTEA($xml_obj['kunden']['tkunde'][0]['cStrasse']));
                $xml_obj['kunden']['tkunde'][0]['cAnrede']   = mappeKundenanrede($xml_obj['kunden']['tkunde'][0]['cAnrede'], $xml_obj['kunden']['tkunde'][0]['kSprache']);
                //Strasse und Hausnummer zusammenführen
                $xml_obj['kunden']['tkunde'][0]['cStrasse'] .= ' ' . $xml_obj['kunden']['tkunde'][0]['cHausnummer'];
                unset($xml_obj['kunden']['tkunde'][0]['cHausnummer']);
                //Land ausgeschrieben der Wawi geben
                $xml_obj['kunden']['tkunde'][0]['cLand'] = ISO2land($xml_obj['kunden']['tkunde'][0]['cLand']);

                unset($xml_obj['kunden']['tkunde'][0]['cPasswort']);
                $xml_obj['kunden']['tkunde']['0 attr']             = buildAttributes($xml_obj['kunden']['tkunde'][0]);
                $xml_obj['kunden']['tkunde'][0]['tkundenattribut'] = Shop::DB()->query("SELECT * FROM tkundenattribut WHERE kKunde = " . (int)$xml_obj['kunden']['tkunde']['0 attr']['kKunde'], 9);
                $kundenattribute_anz                               = count($xml_obj['kunden']['tkunde'][0]['tkundenattribut']);
                for ($o = 0; $o < $kundenattribute_anz; $o++) {
                    $xml_obj['kunden']['tkunde'][0]['tkundenattribut'][$o . ' attr'] = buildAttributes($xml_obj['kunden']['tkunde'][0]['tkundenattribut'][$o]);
                }
                if (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
                    Jtllog::writeLog('Dieser Kunde existiert: ' . XML_serialize($xml_obj), JTLLOG_LEVEL_ERROR, false, 'SetKunde_xml');
                }

                return $xml_obj;
            }
            //Email noch nicht belegt, der Kunde muss neu erstellt werden -> KUNDE WIRD NEU ERSTELLT
            $Kunde->dErstellt         = 'now()';
            $Kunde->cPasswortKlartext = $Kunde->generatePassword(12);//strtoupper(gibUID(8));
            $Kunde->cPasswort         = $Kunde->generatePasswordHash($Kunde->cPasswortKlartext);//cryptPasswort($cPasswortKlartext);
            $Kunde->nRegistriert      = 1;
            $Kunde->cAbgeholt         = 'Y';
            $Kunde->cAktiv            = 'Y';
            $Kunde->cSperre           = 'N';
            //mail an Kunden mit Accounterstellung durch Shopbetreiber
            $obj         = new stdClass();
            $obj->tkunde = $Kunde;
            if ($Kunde->cMail) {
                sendeMail(MAILTEMPLATE_ACCOUNTERSTELLUNG_DURCH_BETREIBER, $obj);
            }
            unset($Kunde->cPasswortKlartext);
            unset($Kunde->Anrede);
            $kInetKunde = $Kunde->insertInDB();

            if (count($oKundenattribut_arr) > 0) {
                speicherKundenattribut($Kunde->kKunde, $Kunde->kSprache, $oKundenattribut_arr, true);
            }

            $res_obj['keys']['tkunde attr']['kKunde'] = $kInetKunde;
            $res_obj['keys']['tkunde']                = '';
        }

        if ($kInetKunde > 0) {
            //kunde akt. bzw. neu inserted

            //lieferadressen
            if (isset($xml['tkunde']['tadresse']) && is_array($xml['tkunde']['tadresse']) && count($xml['tkunde']['tadresse']) > 0 &&
                (!isset($xml['tkunde']['tadresse attr']) || !is_array($xml['tkunde']['tadresse attr']))) {
                //mehrere adressen

                $cntLieferadressen = count($xml['tkunde']['tadresse']) / 2;
                for ($i = 0; $i < $cntLieferadressen; $i++) {
                    unset($Lieferadresse);
                    $Lieferadresse = new stdClass();
                    if ($xml['tkunde']['tadresse'][$i . ' attr']['kInetAdresse'] > 0) {
                        //update
                        $Lieferadresse->kLieferadresse = $xml['tkunde']['tadresse'][$i . ' attr']['kInetAdresse'];
                        $Lieferadresse->kKunde         = $kInetKunde;
                        mappe($Lieferadresse, $xml['tkunde']['tadresse'][$i], $GLOBALS['mLieferadresse']);
                        // Hausnummer extrahieren
                        extractStreet($Lieferadresse);
                        //verschlüsseln: Nachname, Firma, Strasse
                        $Lieferadresse->cNachname = verschluesselXTEA(trim($Lieferadresse->cNachname));
                        $Lieferadresse->cFirma    = verschluesselXTEA(trim($Lieferadresse->cFirma));
                        $Lieferadresse->cZusatz   = verschluesselXTEA(trim($Lieferadresse->cZusatz));
                        $Lieferadresse->cStrasse  = verschluesselXTEA(trim($Lieferadresse->cStrasse));
                        $Lieferadresse->cAnrede   = mappeWawiAnrede2ShopAnrede($Lieferadresse->cAnrede);
                        DBUpdateInsert('tlieferadresse', array($Lieferadresse), 'kLieferadresse');
                    } else {
                        $Lieferadresse->kKunde = $kInetKunde;
                        mappe($Lieferadresse, $xml['tkunde']['tadresse'][$i], $GLOBALS['mLieferadresse']);
                        // Hausnummer extrahieren
                        extractStreet($Lieferadresse);
                        //verschlüsseln: Nachname, Firma, Strasse
                        $Lieferadresse->cNachname = verschluesselXTEA(trim($Lieferadresse->cNachname));
                        $Lieferadresse->cFirma    = verschluesselXTEA(trim($Lieferadresse->cFirma));
                        $Lieferadresse->cZusatz   = verschluesselXTEA(trim($Lieferadresse->cZusatz));
                        $Lieferadresse->cStrasse  = verschluesselXTEA(trim($Lieferadresse->cStrasse));
                        $Lieferadresse->cAnrede   = mappeWawiAnrede2ShopAnrede($Lieferadresse->cAnrede);
                        $kInetLieferadresse       = DBinsert('tlieferadresse', $Lieferadresse);
                        if ($kInetLieferadresse > 0) {
                            $res_obj['keys']['tkunde']['tadresse'][$nr . ' attr']['kAdresse']     = $xml['tkunde']['tadresse'][$i . ' attr']['kAdresse'];
                            $res_obj['keys']['tkunde']['tadresse'][$nr . ' attr']['kInetAdresse'] = $kInetLieferadresse;
                            $res_obj['keys']['tkunde']['tadresse'][$nr]                           = '';
                            $nr++;
                        }
                    }
                }
            } elseif (isset($xml['tkunde']['tadresse attr']) && is_array($xml['tkunde']['tadresse attr'])) {
                //nur eine lieferadresse
                if ($xml['tkunde']['tadresse attr']['kInetAdresse'] > 0) {
                    //update
                    if (!isset($Lieferadresse)) {
                        $Lieferadresse = new stdClass();
                    }
                    $Lieferadresse->kLieferadresse = $xml['tkunde']['tadresse attr']['kInetAdresse'];
                    $Lieferadresse->kKunde         = $kInetKunde;
                    mappe($Lieferadresse, $xml['tkunde']['tadresse'], $GLOBALS['mLieferadresse']);
                    // Hausnummer extrahieren
                    extractStreet($Lieferadresse);
                    //verschlüsseln: Nachname, Firma, Strasse
                    $Lieferadresse->cNachname = verschluesselXTEA(trim($Lieferadresse->cNachname));
                    $Lieferadresse->cFirma    = verschluesselXTEA(trim($Lieferadresse->cFirma));
                    $Lieferadresse->cZusatz   = verschluesselXTEA(trim($Lieferadresse->cZusatz));
                    $Lieferadresse->cStrasse  = verschluesselXTEA(trim($Lieferadresse->cStrasse));
                    $Lieferadresse->cAnrede   = mappeWawiAnrede2ShopAnrede($Lieferadresse->cAnrede);
                    DBUpdateInsert('tlieferadresse', array($Lieferadresse), 'kLieferadresse');
                } else {
                    if (!isset($Lieferadresse)) {
                        $Lieferadresse = new stdClass();
                    }
                    $Lieferadresse->kKunde = $kInetKunde;
                    mappe($Lieferadresse, $xml['tkunde']['tadresse'], $GLOBALS['mLieferadresse']);
                    // Hausnummer extrahieren
                    extractStreet($Lieferadresse);
                    //verschlüsseln: Nachname, Firma, Strasse
                    $Lieferadresse->cNachname = verschluesselXTEA(trim($Lieferadresse->cNachname));
                    $Lieferadresse->cFirma    = verschluesselXTEA(trim($Lieferadresse->cFirma));
                    $Lieferadresse->cZusatz   = verschluesselXTEA(trim($Lieferadresse->cZusatz));
                    $Lieferadresse->cStrasse  = verschluesselXTEA(trim($Lieferadresse->cStrasse));
                    $Lieferadresse->cAnrede   = mappeWawiAnrede2ShopAnrede($Lieferadresse->cAnrede);
                    $kInetLieferadresse       = DBinsert('tlieferadresse', $Lieferadresse);
                    if ($kInetLieferadresse > 0) {
                        $res_obj['keys']['tkunde']['tadresse attr']['kAdresse']     = $xml['tkunde']['tadresse attr']['kAdresse'];
                        $res_obj['keys']['tkunde']['tadresse attr']['kInetAdresse'] = $kInetLieferadresse;
                        $res_obj['keys']['tkunde']['tadresse']                      = '';
                    }
                }
            }
        }
    }

    return $res_obj;
}

/**
 * @param int   $kKunde
 * @param int   $kSprache
 * @param array $oKundenattribut_arr
 * @param bool  $bNeu
 */
function speicherKundenattribut($kKunde, $kSprache, $oKundenattribut_arr, $bNeu)
{
    $kKunde   = (int)$kKunde;
    $kSprache = (int)$kSprache;
    if ($kKunde > 0 && $kSprache > 0 && is_array($oKundenattribut_arr) && count($oKundenattribut_arr) > 0) {
        foreach ($oKundenattribut_arr as $oKundenattribut) {
            $oKundenfeld = Shop::DB()->query(
                "SELECT tkundenfeld.kKundenfeld, tkundenfeldwert.cWert
                     FROM tkundenfeld
                     LEFT JOIN tkundenfeldwert
                        ON tkundenfeldwert.kKundenfeld = tkundenfeld.kKundenfeld
                     WHERE tkundenfeld.cWawi = '" . $oKundenattribut->cName . "'
                        AND tkundenfeld.kSprache = " . $kSprache, 1
            );
            if (isset($oKundenfeld->kKundenfeld) && $oKundenfeld->kKundenfeld > 0) {
                if (strlen($oKundenfeld->cWert) > 0 && $oKundenfeld->cWert != $oKundenattribut->cWert) {
                    continue;
                }
                if (!$bNeu) {
                    Shop::DB()->query(
                        "DELETE FROM tkundenattribut
                            WHERE kKunde = " . $kKunde . "
                                AND kKundenfeld = " . (int)$oKundenfeld->kKundenfeld, 4
                    );
                }
                $oKundenattributTMP              = new stdClass();
                $oKundenattributTMP->kKunde      = $kKunde;
                $oKundenattributTMP->kKundenfeld = (int)$oKundenfeld->kKundenfeld;
                $oKundenattributTMP->cName       = $oKundenattribut->cName;
                $oKundenattributTMP->cWert       = $oKundenattribut->cWert;

                Shop::DB()->insert('tkundenattribut', $oKundenattributTMP);
            }
        }
    }
}
