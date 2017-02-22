<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

/**
 * Holt für einen Kunden die aktive Wunschliste (falls vorhanden) aus der DB und fügt diese in die Session
 */
function setzeWunschlisteInSession()
{
    if (!empty($_SESSION['Kunde']->kKunde)) {
        $oWunschliste = Shop::DB()->query("
            SELECT kWunschliste
                FROM twunschliste
                WHERE kKunde = " . intval($_SESSION['Kunde']->kKunde) . "
                AND nStandard = 1", 1
        );
        if (isset($oWunschliste->kWunschliste)) {
            $_SESSION['Wunschliste'] = new Wunschliste($oWunschliste->kWunschliste);
            $GLOBALS['hinweis']      = $_SESSION['Wunschliste']->ueberpruefePositionen();
        }
    }
}

/**
 * @param int $kWunschliste
 * @return string
 */
function wunschlisteLoeschen($kWunschliste)
{
    $hinweis      = '';
    $kWunschliste = intval($kWunschliste);
    if ($kWunschliste > 0) {
        // Prüfe ob die Wunschliste dem eingeloggten Kunden gehört
        $oWunschliste = Shop::DB()->query("SELECT kKunde, nStandard FROM twunschliste WHERE kWunschliste = " . $kWunschliste, 1);
        if (isset($oWunschliste->kKunde) && $oWunschliste->kKunde == $_SESSION['Kunde']->kKunde) {
            // Hole alle Positionen der Wunschliste
            $oWunschlistePos_arr = Shop::DB()->query("SELECT kWunschlistePos FROM twunschlistepos WHERE kWunschliste = " . $kWunschliste, 2);
            if (count($oWunschlistePos_arr) > 0) {
                // Alle Eigenschaften und Positionen aus DB löschen
                foreach ($oWunschlistePos_arr as $oWunschlistePos) {
                    Shop::DB()->delete('twunschlisteposeigenschaft', 'kWunschlistePos', $oWunschlistePos->kWunschlistePos);
                }
            }
            // Lösche alle Positionen mit $kWunschliste
            Shop::DB()->delete('twunschlistepos', 'kWunschliste', $kWunschliste);
            // Lösche Wunschliste aus der DB
            Shop::DB()->delete('twunschliste', 'kWunschliste', $kWunschliste);
            // Lösche Wunschliste aus der Session (falls Wunschliste = Standard)
            if (isset($_SESSION['Wunschliste']->kWunschliste) && $_SESSION['Wunschliste']->kWunschliste == $kWunschliste) {
                unset($_SESSION['Wunschliste']);
            }
            // Wenn die gelöschte Wunschliste nStandard = 1 war => neue setzen
            if ($oWunschliste->nStandard == 1) {
                // Neue Wunschliste holen (falls vorhanden) und nStandard=1 neu setzen
                $oWunschliste = Shop::DB()->query("SELECT kWunschliste FROM twunschliste WHERE kKunde = " . (int)$_SESSION['Kunde']->kKunde, 1);
                if (isset($oWunschliste->kWunschliste)) {
                    Shop::DB()->query("UPDATE twunschliste SET nStandard = 1 WHERE kWunschliste = " . (int)$oWunschliste->kWunschliste, 3);
                    // Neue Standard Wunschliste in die Session laden
                    $_SESSION['Wunschliste'] = new Wunschliste($oWunschliste->kWunschliste);
                    $GLOBALS['hinweis']      = $_SESSION['Wunschliste']->ueberpruefePositionen();
                }
            }

            $hinweis = Shop::Lang()->get('wishlistDelete', 'messages');
        }
    }

    return $hinweis;
}

/**
 * @param int $kWunschliste
 * @return string
 */
function wunschlisteAktualisieren($kWunschliste)
{
    $hinweis      = '';
    $kWunschliste = (int)$kWunschliste;
    // Ist ein Wunschlisten Name gesetzt
    if (isset($_POST['WunschlisteName']) && strlen($_POST['WunschlisteName']) > 0) {
        $cName = StringHandler::htmlentities(StringHandler::filterXSS(substr($_POST['WunschlisteName'], 0, 254)));
        // Name der Wunschliste updaten
        $_upd        = new stdClass();
        $_upd->cName = $cName;
        Shop::DB()->update('twunschliste', 'kWunschliste', $kWunschliste, $_upd);
    }
    //aktualisiere positionen
    $oWunschlistePos_arr = Shop::DB()->query("SELECT kWunschlistePos FROM twunschlistepos WHERE kWunschliste = " . $kWunschliste, 2);
    // Prüfen ab Positionen vorhanden
    if (count($oWunschlistePos_arr) > 0) {
        foreach ($oWunschlistePos_arr as $oWunschlistePos) {
            $kWunschlistePos = $oWunschlistePos->kWunschlistePos;
            // Ist ein Kommentar vorhanden
            if (strlen($_POST['Kommentar_' . $kWunschlistePos]) > 0) {
                $cKommentar = substr($_POST['Kommentar_' . $kWunschlistePos], 0, 254);
                // Kommentar der Position updaten
                $_upd             = new stdClass();
                $_upd->cKommentar = StringHandler::htmlentities(StringHandler::filterXSS(Shop::DB()->escape($cKommentar)));
                Shop::DB()->update('twunschlistepos', 'kWunschlistePos', (int)$kWunschlistePos, $_upd);
            }
            // Ist eine Anzahl gesezt
            if (intval($_POST['Anzahl_' . $kWunschlistePos]) > 0) {
                $fAnzahl = floatval($_POST['Anzahl_' . $kWunschlistePos]);
                // Anzahl der Position updaten
                $_upd          = new stdClass();
                $_upd->fAnzahl = $fAnzahl;
                Shop::DB()->update('twunschlistepos', 'kWunschlistePos', (int)$kWunschlistePos, $_upd);
            }
        }
        $hinweis = Shop::Lang()->get('wishlistUpdate', 'messages');
    }

    return $hinweis;
}

/**
 * @param int $kWunschliste
 * @return string
 */
function wunschlisteStandard($kWunschliste)
{
    $hinweis      = '';
    $kWunschliste = (int)$kWunschliste;
    if ($kWunschliste > 0) {
        // Prüfe ob die Wunschliste dem eingeloggten Kunden gehört
        $oWunschliste = Shop::DB()->select('twunschliste', 'kWunschliste', $kWunschliste);
        if ($oWunschliste->kKunde == $_SESSION['Kunde']->kKunde && $oWunschliste->kKunde) {
            // Wunschliste auf Standard setzen
            Shop::DB()->query("UPDATE twunschliste SET nStandard = 0 WHERE kKunde = " . (int)$_SESSION['Kunde']->kKunde, 3);
            Shop::DB()->query("UPDATE twunschliste SET nStandard = 1 WHERE kWunschliste = " . $kWunschliste, 3);
            // Session updaten
            unset($_SESSION['Wunschliste']);
            $_SESSION['Wunschliste'] = new Wunschliste($kWunschliste);
            $GLOBALS['hinweis']      = $_SESSION['Wunschliste']->ueberpruefePositionen();

            $hinweis = Shop::Lang()->get('wishlistStandard', 'messages');
        }
    }

    return $hinweis;
}

/**
 * @param string $cWunschlisteName
 * @return string
 */
function wunschlisteSpeichern($cWunschlisteName)
{
    $hinweis = '';
    if ($_SESSION['Kunde']->kKunde > 0 && !empty($cWunschlisteName)) {
        $CWunschliste            = new Wunschliste();
        $CWunschliste->cName     = $cWunschlisteName;
        $CWunschliste->nStandard = 0;
        unset($CWunschliste->CWunschlistePos_arr);
        unset($CWunschliste->oKunde);
        unset($CWunschliste->kWunschliste);
        unset($CWunschliste->dErstellt_DE);

        Shop::DB()->insert('twunschliste', $CWunschliste);

        unset($CWunschliste);

        $hinweis = Shop::Lang()->get('wishlistAdd', 'messages');
    }

    return $hinweis;
}

/**
 * @param array $cEmail_arr
 * @param int   $kWunschliste
 * @return string
 */
function wunschlisteSenden($cEmail_arr, $kWunschliste)
{
    $hinweis      = '';
    $kWunschliste = intval($kWunschliste);
    // Wurden Emails übergeben?
    if (count($cEmail_arr) > 0) {
        $conf = Shop::getSettings(array(CONF_GLOBAL));
        if (!isset($oMail)) {
            $oMail = new stdClass();
        }
        $oMail->tkunde       = $_SESSION['Kunde'];
        $oMail->twunschliste = bauecPreis(new Wunschliste($kWunschliste));

        $oWunschlisteVersand                    = new stdClass();
        $oWunschlisteVersand->kWunschliste      = $kWunschliste;
        $oWunschlisteVersand->dZeit             = 'now()';
        $oWunschlisteVersand->nAnzahlEmpfaenger = min(count($cEmail_arr), intval($conf['global']['global_wunschliste_max_email']));
        $oWunschlisteVersand->nAnzahlArtikel    = count($oMail->twunschliste->CWunschlistePos_arr);

        Shop::DB()->insert('twunschlisteversand', $oWunschlisteVersand);

        $cValidEmail_arr = array();
        // Schleife mit Emails (versenden)
        for ($i = 0; $i < $oWunschlisteVersand->nAnzahlEmpfaenger; $i++) {
            // Email auf "Echtheit" prüfen
            $cEmail = StringHandler::filterXSS($cEmail_arr[$i]);
            if (!pruefeEmailblacklist($cEmail)) {
                if (!isset($oMail->mail)) {
                    $oMail->mail = new stdClass();
                }
                $oMail->mail->toEmail = $cEmail;
                $oMail->mail->toName  = $cEmail;
                // Emails senden
                sendeMail(MAILTEMPLATE_WUNSCHLISTE, $oMail);
            } else {
                $cValidEmail_arr[] = $cEmail;
            }
        }
        // Gabs Emails die nicht validiert wurden?
        if (count($cValidEmail_arr) > 0) {
            $hinweis = Shop::Lang()->get('novalidEmail', 'messages');
            foreach ($cValidEmail_arr as $cValidEmail) {
                $hinweis .= $cValidEmail . ', ';
            }
            $hinweis = substr($hinweis, 0, strlen($hinweis) - 2) . '<br />';
        }
        // Hat der benutzer mehr Emails angegeben als erlaubt sind?
        if (count($cEmail_arr) > intval($conf['global']['global_wunschliste_max_email'])) {
            $nZuviel = count($cEmail_arr) - intval($conf['global']['global_wunschliste_max_email']);
            $hinweis .= '<br />';

            if (strpos($hinweis, Shop::Lang()->get('novalidEmail', 'messages')) === false) {
                $hinweis = Shop::Lang()->get('novalidEmail', 'messages');
            }

            for ($i = 0; $i < $nZuviel; $i++) {
                if (strpos($hinweis, $cEmail_arr[((count($cEmail_arr) - 1) - $i)]) === false) {
                    if ($i > 0) {
                        $hinweis .= ', ' . $cEmail_arr[((count($cEmail_arr) - 1) - $i)];
                    } else {
                        $hinweis .= $cEmail_arr[((count($cEmail_arr) - 1) - $i)];
                    }
                }
            }

            $hinweis .= '<br />';
        }

        $hinweis .= Shop::Lang()->get('emailSeccessfullySend', 'messages');
    } else {
        // Keine Emails eingegeben
        $hinweis = Shop::Lang()->get('noEmail', 'messages');
    }

    return $hinweis;
}

/**
 * @param int $kWunschliste
 * @param int $kWunschlistePos
 * @return array|bool
 */
function gibEigenschaftenZuWunschliste($kWunschliste, $kWunschlistePos)
{
    $kWunschliste    = intval($kWunschliste);
    $kWunschlistePos = intval($kWunschlistePos);
    if ($kWunschliste > 0 && $kWunschlistePos > 0) {
        // $oEigenschaftwerte_arr anlegen
        $oEigenschaftwerte_arr          = array();
        $oWunschlistePosEigenschaft_arr = Shop::DB()->query(
            "SELECT *
                FROM twunschlisteposeigenschaft
                WHERE kWunschlistePos = " . $kWunschlistePos, 2
        );

        if (is_array($oWunschlistePosEigenschaft_arr) && count($oWunschlistePosEigenschaft_arr) > 0) {
            foreach ($oWunschlistePosEigenschaft_arr as $oWunschlistePosEigenschaft) {
                $oEigenschaftwerte                       = new stdClass();
                $oEigenschaftwerte->kEigenschaftWert     = $oWunschlistePosEigenschaft->kEigenschaftWert;
                $oEigenschaftwerte->kEigenschaft         = $oWunschlistePosEigenschaft->kEigenschaft;
                $oEigenschaftwerte->cEigenschaftName     = $oWunschlistePosEigenschaft->cEigenschaftName;
                $oEigenschaftwerte->cEigenschaftWertName = $oWunschlistePosEigenschaft->cEigenschaftWertName;
                $oEigenschaftwerte->cFreifeldWert        = $oWunschlistePosEigenschaft->cFreifeldWert;

                $oEigenschaftwerte_arr[] = $oEigenschaftwerte;
            }
        }

        return $oEigenschaftwerte_arr;
    }

    return false;
}

/**
 * @param int $kWunschlistePos
 * @return bool
 */
function giboWunschlistePos($kWunschlistePos)
{
    $kWunschlistePos = intval($kWunschlistePos);
    if ($kWunschlistePos > 0) {
        $oWunschlistePos = Shop::DB()->query(
            "SELECT *
                FROM twunschlistepos
                WHERE kWunschlistePos = " . $kWunschlistePos, 1
        );

        if ($oWunschlistePos->kWunschliste > 0) {
            $oArtikelOptionen          = Artikel::getDefaultOptions();
            $oArtikelOptionen->nKonfig = 0;
            $oArtikel                  = new Artikel();
            $oArtikel->fuelleArtikel($oWunschlistePos->kArtikel, $oArtikelOptionen);

            if (intval($oArtikel->kArtikel) > 0) {
                $oWunschlistePos->bKonfig = $oArtikel->bHasKonfig;
            }

            return $oWunschlistePos;
        }
    }

    return false;
}

/**
 * @param int    $kWunschliste
 * @param string $cURLID
 * @return bool
 */
function giboWunschliste($kWunschliste = 0, $cURLID = '')
{
    if ($kWunschliste > 0 || strlen($cURLID) > 0) {
        $cSQL = "kWunschliste = " . (int)$kWunschliste;
        if ($kWunschliste == 0 && strlen($cURLID) > 0) {
            $cSQL = "cURLID LIKE '" . addcslashes($cURLID, '%_') . "%'";
        }
        $oWunschliste = Shop::DB()->query("SELECT * FROM twunschliste WHERE " . $cSQL, 1);

        if (isset($oWunschliste->kWunschliste) && $oWunschliste->kWunschliste > 0) {
            return $oWunschliste;
        }
    }

    return false;
}

/**
 * @param object $oWunschliste
 * @return mixed
 */
function bauecPreis($oWunschliste)
{
    // Wunschliste durchlaufen und cPreis setzen (Artikelanzahl mit eingerechnet)
    if (is_array($oWunschliste->CWunschlistePos_arr) && count($oWunschliste->CWunschlistePos_arr) > 0) {
        foreach ($oWunschliste->CWunschlistePos_arr as $oWunschlistePos) {
            if (intval($_SESSION['Kundengruppe']->nNettoPreise) > 0) {
                $fPreis = (isset($oWunschlistePos->Artikel->Preise->fVKNetto)) ?
                    intval($oWunschlistePos->fAnzahl) * $oWunschlistePos->Artikel->Preise->fVKNetto :
                    0;
            } else {
                $fPreis = (isset($oWunschlistePos->Artikel->Preise->fVKNetto)) ?
                    intval($oWunschlistePos->fAnzahl) * ($oWunschlistePos->Artikel->Preise->fVKNetto * (100 + $_SESSION['Steuersatz'][$oWunschlistePos->Artikel->kSteuerklasse]) / 100) :
                0;
            }
            $oWunschlistePos->cPreis = gibPreisStringLocalized($fPreis, $_SESSION['Waehrung']);
        }
    }

    return $oWunschliste;
}

/**
 * @param int $nMSGCode
 * @return string
 */
function mappeWunschlisteMSG($nMSGCode)
{
    $cMSG = '';
    if (intval($nMSGCode) > 0) {
        switch ($nMSGCode) {
            case 1:
                $cMSG = Shop::Lang()->get('basketAdded', 'messages');
                break;
            case 2:
                $cMSG = Shop::Lang()->get('basketAllAdded', 'messages');
                break;
            default:
                break;
        }
    }

    return $cMSG;
}
