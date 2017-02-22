<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

/**
 * @param array $oUmfrageFrage_arr
 * @return int
 */
function bestimmeAnzahlSeiten($oUmfrageFrage_arr)
{
    $nAnzahlSeiten = 1;

    if (is_array($oUmfrageFrage_arr) && count($oUmfrageFrage_arr) > 0) {
        foreach ($oUmfrageFrage_arr as $i => $oUmfrageFrage) {
            if ($i > 0) {
                if ($oUmfrageFrage->cTyp === 'text_statisch_seitenwechsel') {
                    $nAnzahlSeiten++;
                }
            }
        }
    }

    return $nAnzahlSeiten;
}

/**
 * @param array $oUmfrageFrage_arr
 * @return array
 */
function baueSeitenAnfaenge($oUmfrageFrage_arr)
{
    $nSeitenAnfang_arr = array();

    if (is_array($oUmfrageFrage_arr) && count($oUmfrageFrage_arr) > 0) {
        $nSeitenAnfang_arr[] = 0;
        foreach ($oUmfrageFrage_arr as $i => $oUmfrageFrage) {
            if ($i > 0) {
                if ($oUmfrageFrage->cTyp === 'text_statisch_seitenwechsel') {
                    $nSeitenAnfang_arr[] = $i;
                }
            }
        }
    }

    return $nSeitenAnfang_arr;
}

/**
 * @param array $oUmfrageFrage_arr
 * @param int   $nAnzahlFragen
 * @return array
 */
function baueSeitenNavi($oUmfrageFrage_arr, $nAnzahlFragen)
{
    $nAnzahlSeiten     = bestimmeAnzahlSeiten($oUmfrageFrage_arr);
    $nSeitenAnfang_arr = baueSeitenAnfaenge($oUmfrageFrage_arr);
    $oNavi_arr         = array();
    if ($nAnzahlSeiten > 0) {
        for ($i = 0; $i < $nAnzahlSeiten; $i++) {
            if (!isset($oNavi_arr[$i])) {
                $oNavi_arr[$i] = new stdClass();
            }
            $oNavi_arr[$i]->nSeite = $i + 1;
            $oNavi_arr[$i]->nVon   = (isset($nSeitenAnfang_arr[$i])) ? $nSeitenAnfang_arr[$i] : 0;

            if ($i == (count($nSeitenAnfang_arr) - 1)) {
                $oNavi_arr[$i]->nAnzahl = $nAnzahlFragen - $nSeitenAnfang_arr[$i];
            } else {
                $oNavi_arr[$i]->nAnzahl = $nSeitenAnfang_arr[$i + 1] - $oNavi_arr[$i]->nVon;
            }
        }
    }

    return $oNavi_arr;
}

/**
 * @param array $cPost_arr
 */
function speicherFragenInSession($cPost_arr)
{
    if (is_array($cPost_arr['kUmfrageFrage']) && count($cPost_arr['kUmfrageFrage']) > 0) {
        foreach ($cPost_arr['kUmfrageFrage'] as $i => $kUmfrageFrage) {
            $kUmfrageFrage = intval($kUmfrageFrage);
            $oUmfrageFrage = Shop::DB()->query(
                "SELECT cTyp
                    FROM tumfragefrage
                    WHERE kUmfrageFrage = " . $kUmfrageFrage, 1
            );

            if ($oUmfrageFrage->cTyp !== 'text_statisch_seitenwechsel' && $oUmfrageFrage->cTyp !== 'text_statisch') {
                if ($oUmfrageFrage->cTyp === 'matrix_single') {
                    $_SESSION['Umfrage']->oUmfrageFrage_arr[$kUmfrageFrage]->oUmfrageFrageAntwort_arr = array();

                    $oUmfrageFrageAntwort_arr = Shop::DB()->query(
                        "SELECT kUmfrageFrageAntwort
                            FROM tumfragefrageantwort
                            WHERE kUmfrageFrage = " . $kUmfrageFrage, 2
                    );

                    if (is_array($oUmfrageFrageAntwort_arr) && count($oUmfrageFrageAntwort_arr) > 0) {
                        foreach ($oUmfrageFrageAntwort_arr as $oUmfrageFrageAntwort) {
                            $_SESSION['Umfrage']->oUmfrageFrage_arr[$kUmfrageFrage]->oUmfrageFrageAntwort_arr[] = $cPost_arr[$kUmfrageFrage . '_' . $oUmfrageFrageAntwort->kUmfrageFrageAntwort];
                        }
                    }
                } elseif ($oUmfrageFrage->cTyp === 'matrix_multi') {
                    $_SESSION['Umfrage']->oUmfrageFrage_arr[$kUmfrageFrage]->oUmfrageFrageAntwort_arr = array();
                    $_SESSION['Umfrage']->oUmfrageFrage_arr[$kUmfrageFrage]->oUmfrageFrageAntwort_arr = $cPost_arr[$kUmfrageFrage];
                } else {
                    $_SESSION['Umfrage']->oUmfrageFrage_arr[$kUmfrageFrage]->oUmfrageFrageAntwort_arr = array();
                    $_SESSION['Umfrage']->oUmfrageFrage_arr[$kUmfrageFrage]->oUmfrageFrageAntwort_arr = $cPost_arr[$kUmfrageFrage];
                }
            }
        }
    }
}

/**
 * @param array $cPost_arr
 */
function findeFragenUndUpdateSession($cPost_arr)
{
    if (is_array($cPost_arr['kUmfrageFrage']) && count($cPost_arr['kUmfrageFrage']) > 0) {
        foreach ($cPost_arr['kUmfrageFrage'] as $kUmfrageFrage) {
            $kUmfrageFrage = intval($kUmfrageFrage);
            $oUmfrageFrage = Shop::DB()->query(
                "SELECT cTyp
                    FROM tumfragefrage
                    WHERE kUmfrageFrage = " . $kUmfrageFrage, 1
            );
            unset($_SESSION['Umfrage']->oUmfrageFrage_arr[$kUmfrageFrage]->oUmfrageFrageAntwort_arr);
            $_SESSION['Umfrage']->oUmfrageFrage_arr[$kUmfrageFrage]->oUmfrageFrageAntwort_arr = array();
            $_SESSION['Umfrage']->oUmfrageFrage_arr[$kUmfrageFrage]->oUmfrageFrageAntwort_arr = $cPost_arr[$kUmfrageFrage];
            if ($oUmfrageFrage->cTyp === 'matrix_single' || $oUmfrageFrage->cTyp === 'matrix_multi') {
                unset($_SESSION['Umfrage']->oUmfrageFrage_arr[$i]->oUmfrageMatrixOption_arr);
                $_SESSION['Umfrage']->oUmfrageFrage_arr[$i]->oUmfrageMatrixOption_arr             = array();
                $_SESSION['Umfrage']->oUmfrageFrage_arr[$kUmfrageFrage]->oUmfrageMatrixOption_arr = $cPost_arr[$kUmfrageFrage];
            }
        }
    }
}

/**
 * @param array $oUmfrageFrage_arr
 * @return array
 */
function findeFragenInSession($oUmfrageFrage_arr)
{
    $nSessionFragenWerte_arr = array();

    if (is_array($oUmfrageFrage_arr) && count($oUmfrageFrage_arr) > 0) {
        foreach ($oUmfrageFrage_arr as $oUmfrageFrage) {
            foreach ($_SESSION['Umfrage']->oUmfrageFrage_arr as $i => $oUmfrageFrageSession) {
                if ($oUmfrageFrageSession->kUmfrageFrage == $oUmfrageFrage->kUmfrageFrage) {
                    if (isset($oUmfrageFrageSession->oUmfrageFrageAntwort_arr) && is_array($oUmfrageFrageSession->oUmfrageFrageAntwort_arr) && count($oUmfrageFrageSession->oUmfrageFrageAntwort_arr) > 0) {
                        if ($oUmfrageFrageSession->cTyp === 'matrix_single' || $oUmfrageFrageSession->cTyp === 'matrix_multi') {
                            $nSessionFragenWerte_arr[$oUmfrageFrageSession->kUmfrageFrage]->cUmfrageFrageAntwort_arr = array();
                            foreach ($oUmfrageFrageSession->oUmfrageFrageAntwort_arr as $cUmfrageFrageAntwort) {
                                list($kUmfrageFrageAntwort, $kUmfrageMatrixOption)                                         = explode('_', $cUmfrageFrageAntwort);
                                $oAntwort                                                                                  = new stdClass();
                                $oAntwort->kUmfrageFrageAntwort                                                            = $kUmfrageFrageAntwort;
                                $oAntwort->kUmfrageMatrixOption                                                            = $kUmfrageMatrixOption;
                                $nSessionFragenWerte_arr[$oUmfrageFrageSession->kUmfrageFrage]->cUmfrageFrageAntwort_arr[] = $oAntwort;
                            }
                        } else {
                            $nSessionFragenWerte_arr[$oUmfrageFrageSession->kUmfrageFrage]->cUmfrageFrageAntwort_arr = array();
                            foreach ($oUmfrageFrageSession->oUmfrageFrageAntwort_arr as $cUmfrageFrageAntwort) {
                                $nSessionFragenWerte_arr[$oUmfrageFrageSession->kUmfrageFrage]->cUmfrageFrageAntwort_arr[] = $cUmfrageFrageAntwort;
                            }
                        }
                    }
                }
            }
        }
    }

    return $nSessionFragenWerte_arr;
}

/**
 *
 */
function setzeUmfrageErgebnisse()
{
    if (isset($_SESSION['Umfrage']) && is_array($_SESSION['Umfrage']->oUmfrageFrage_arr) && count($_SESSION['Umfrage']->oUmfrageFrage_arr)) {
        // Eintrag in tumfragedurchfuehrung
        $oUmfrageDurchfuehrung = new stdClass();
        if (isset($_SESSION['Kunde']->kKunde) && $_SESSION['Kunde']->kKunde > 0) {
            $oUmfrageDurchfuehrung->kKunde = (int) $_SESSION['Kunde']->kKunde;
            $oUmfrageDurchfuehrung->cIP    = '';
        } else {
            $oUmfrageDurchfuehrung->kKunde = 0;
            $oUmfrageDurchfuehrung->cIP    = $_SESSION['oBesucher']->cID;
        }
        $oUmfrageDurchfuehrung->kUmfrage       = $_SESSION['Umfrage']->kUmfrage;
        $oUmfrageDurchfuehrung->dDurchgefuehrt = 'now()';

        $kUmfrageDurchfuehrung = Shop::DB()->insert('tumfragedurchfuehrung', $oUmfrageDurchfuehrung);

        // Daten der Umfrage in die Datenbank (tumfragedurchfuehrungantwort) speichern
        foreach ($_SESSION['Umfrage']->oUmfrageFrage_arr as $j => $oUmfrageFrage) {
            if ($oUmfrageFrage->cTyp !== 'text_statisch' && $oUmfrageFrage->cTyp !== 'text_statisch_seitenwechsel') {
                if (is_array($oUmfrageFrage->oUmfrageFrageAntwort_arr) && count($oUmfrageFrage->oUmfrageFrageAntwort_arr) > 0) {
                    foreach ($oUmfrageFrage->oUmfrageFrageAntwort_arr as $i => $cUmfrageFrageAntwort) {
                        if (isset($cUmfrageFrageAntwort) && isset($oUmfrageFrage->oUmfrageFrageAntwort_arr[$i]) && strlen($cUmfrageFrageAntwort) > 0 && $oUmfrageFrage->oUmfrageFrageAntwort_arr[$i] != '-1') {
                            unset($oUmfrageDurchfuehrungAntwort);
                            $oUmfrageDurchfuehrungAntwort                        = new stdClass();
                            $oUmfrageDurchfuehrungAntwort->kUmfrageDurchfuehrung = $kUmfrageDurchfuehrung;
                            $oUmfrageDurchfuehrungAntwort->kUmfrageFrage         = $oUmfrageFrage->kUmfrageFrage;

                            if ($oUmfrageFrage->cTyp === 'text_klein' || $oUmfrageFrage->cTyp === 'text_gross') {
                                $oUmfrageDurchfuehrungAntwort->kUmfrageFrageAntwort = 0;
                                $oUmfrageDurchfuehrungAntwort->kUmfrageMatrixOption = 0;
                                $oUmfrageDurchfuehrungAntwort->cText                = StringHandler::htmlentities(StringHandler::filterXSS($cUmfrageFrageAntwort));
                            } elseif ($oUmfrageFrage->cTyp === 'matrix_single' || $oUmfrageFrage->cTyp === 'matrix_multi') {
                                list($kUmfrageFrageAntwort, $kUmfrageMatrixOption)  = explode('_', $cUmfrageFrageAntwort);
                                $oUmfrageDurchfuehrungAntwort->kUmfrageFrageAntwort = $kUmfrageFrageAntwort;
                                $oUmfrageDurchfuehrungAntwort->kUmfrageMatrixOption = $kUmfrageMatrixOption;
                                $oUmfrageDurchfuehrungAntwort->cText                = '';
                            } else {
                                if ($cUmfrageFrageAntwort == '-1') {
                                    $oUmfrageDurchfuehrungAntwort->kUmfrageFrageAntwort = 0;
                                    $oUmfrageDurchfuehrungAntwort->kUmfrageMatrixOption = 0;
                                    $oUmfrageDurchfuehrungAntwort->cText                = StringHandler::htmlentities(StringHandler::filterXSS($oUmfrageFrage->oUmfrageFrageAntwort_arr[$i + 1]));
                                    array_pop($_SESSION['Umfrage']->oUmfrageFrage_arr[$j]->oUmfrageFrageAntwort_arr);
                                } else {
                                    $oUmfrageDurchfuehrungAntwort->kUmfrageFrageAntwort = $cUmfrageFrageAntwort;
                                    $oUmfrageDurchfuehrungAntwort->kUmfrageMatrixOption = 0;
                                    $oUmfrageDurchfuehrungAntwort->cText                = '';
                                }
                            }

                            Shop::DB()->insert('tumfragedurchfuehrungantwort', $oUmfrageDurchfuehrungAntwort);
                        }
                    }
                }
            }
        }
    }
}

/**
 * Return 0 falls alles in Ordnung
 * Return $kUmfrageFrage falls inkorrekte oder leere Antwort
 *
 * @param array $cPost_arr
 * @return int
 */
function pruefeEingabe($cPost_arr)
{
    if (is_array($cPost_arr['kUmfrageFrage']) && count($cPost_arr['kUmfrageFrage']) > 0) {
        foreach ($cPost_arr['kUmfrageFrage'] as $i => $kUmfrageFrage) {
            $kUmfrageFrage = intval($kUmfrageFrage);
            $oUmfrageFrage = Shop::DB()->query(
                "SELECT kUmfrageFrage, cTyp, nNotwendig
                    FROM tumfragefrage
                    WHERE kUmfrageFrage = " . $kUmfrageFrage, 1
            );

            if ($oUmfrageFrage->nNotwendig == 1) {
                $oUmfrageFrageAntwort_arr = Shop::DB()->query(
                    "SELECT kUmfrageFrageAntwort, cName
                        FROM tumfragefrageantwort
                        WHERE kUmfrageFrage = " . (int) $oUmfrageFrage->kUmfrageFrage, 2
                );

                if ($oUmfrageFrage->cTyp === 'matrix_single' || $oUmfrageFrage->cTyp === 'matrix_multi') {
                    if (is_array($oUmfrageFrageAntwort_arr) && count($oUmfrageFrageAntwort_arr) > 0) {
                        foreach ($oUmfrageFrageAntwort_arr as $oUmfrageFrageAntwort) {
                            if ($oUmfrageFrage->cTyp === 'matrix_single') {
                                if (!isset($cPost_arr[$oUmfrageFrage->kUmfrageFrage . '_' . $oUmfrageFrageAntwort->kUmfrageFrageAntwort])) {
                                    return $oUmfrageFrage->kUmfrageFrage;
                                }
                            } elseif ($oUmfrageFrage->cTyp === 'matrix_multi') {
                                if (is_array($cPost_arr[$oUmfrageFrage->kUmfrageFrage]) && count($cPost_arr[$oUmfrageFrage->kUmfrageFrage]) > 0) {
                                    $nEnthalten = 0;
                                    foreach ($cPost_arr[$oUmfrageFrage->kUmfrageFrage] as $cUmfrageFrageAntwortMatrix) {
                                        list($kUmfrageFrageAntwortTMP, $kUmfrageMatrixOption) = explode('_', $cUmfrageFrageAntwortMatrix);

                                        if ($kUmfrageFrageAntwortTMP == $oUmfrageFrageAntwort->kUmfrageFrageAntwort) {
                                            $nEnthalten = 1;
                                            break;
                                        }
                                    }

                                    if ($nEnthalten == 0) {
                                        return $oUmfrageFrage->kUmfrageFrage;
                                    }
                                } else {
                                    return $oUmfrageFrage->kUmfrageFrage;
                                }
                            }
                        }
                    }
                } elseif ($oUmfrageFrage->cTyp === 'text_klein' || $oUmfrageFrage->cTyp === 'text_gross') {
                    if (!isset($cPost_arr[$oUmfrageFrage->kUmfrageFrage]) || strlen(trim($cPost_arr[$oUmfrageFrage->kUmfrageFrage][0])) === 0) {
                        return $oUmfrageFrage->kUmfrageFrage;
                    }
                } else {
                    if (is_array($oUmfrageFrageAntwort_arr) && count($oUmfrageFrageAntwort_arr) > 0) {
                        if (!isset($cPost_arr[$oUmfrageFrage->kUmfrageFrage])) {
                            return $oUmfrageFrage->kUmfrageFrage;
                        }
                    }
                }
            }
        }
    }

    return 0;
}

/**
 * @param int    $kUmfrage
 * @param int    $kKunde
 * @param string $cIP
 * @return bool
 */
function pruefeUserUmfrage($kUmfrage, $kKunde, $cIP = '')
{
    $kUmfrage = intval($kUmfrage);
    $kKunde   = intval($kKunde);
    if ($kKunde > 0) {
        $oUmfrageDurchfuehrung = Shop::DB()->query(
            "SELECT kUmfrageDurchfuehrung
                FROM tumfragedurchfuehrung
                WHERE kUmfrage = " . $kUmfrage . "
                    AND kKunde = " . $kKunde, 1
        );

        if (isset($oUmfrageDurchfuehrung->kUmfrageDurchfuehrung) && $oUmfrageDurchfuehrung->kUmfrageDurchfuehrung > 0) {
            return false;
        }
    } else {
        $oUmfrageDurchfuehrung = Shop::DB()->query(
            "SELECT kUmfrageDurchfuehrung
                FROM tumfragedurchfuehrung
                WHERE kUmfrage = " . $kUmfrage . "
                    AND kKunde = 0
                    AND cIP = '" . $cIP . "'", 1
        );

        if (isset($oUmfrageDurchfuehrung->kUmfrageDurchfuehrung) && $oUmfrageDurchfuehrung->kUmfrageDurchfuehrung > 0) {
            return false;
        }
    }

    return true;
}

/**
 * @param float $fGuthaben
 * @param int   $kKunde
 * @return bool
 */
function gibKundeGuthaben($fGuthaben, $kKunde)
{
    if ($kKunde > 0) {
        Shop::DB()->query(
            "UPDATE tkunde
                SET fGuthaben = fGuthaben + " . doubleval($fGuthaben) . "
                WHERE kKunde = " . intval($kKunde), 4
        );

        return true;
    }

    return false;
}

/**
 * @param int $kUmfrage
 * @return mixed
 */
function holeAktuelleUmfrage($kUmfrage)
{
    // Modulprüfung
    $oNice = Nice::getInstance();
    if (!$oNice->checkErweiterung(SHOP_ERWEITERUNG_UMFRAGE)) {
        return;
    }
    if (!$kUmfrage) {
        return;
    }
    // Umfrage holen
    return Shop::DB()->query(
        "SELECT tumfrage.kUmfrage, tumfrage.kSprache, tumfrage.kKupon, tumfrage.cKundengruppe, tumfrage.cName, tumfrage.cBeschreibung,
            tumfrage.fGuthaben, tumfrage.nBonuspunkte, tumfrage.nAktiv, tumfrage.dGueltigVon, tumfrage.dGueltigBis, tumfrage.dErstellt, tseo.cSeo,
            count(tumfragefrage.kUmfrageFrage) AS nAnzahlFragen
            FROM tumfrage
            JOIN tumfragefrage ON tumfragefrage.kUmfrage = tumfrage.kUmfrage
            LEFT JOIN tseo ON tseo.cKey = 'kUmfrage'
                AND tseo.kKey = tumfrage.kUmfrage
                AND tseo.kSprache = " . (int) $_SESSION['kSprache'] . "
            WHERE tumfrage.kUmfrage = " . (int) $kUmfrage . "
                AND tumfrage.nAktiv = 1
                AND tumfrage.kSprache = " . (int) $_SESSION['kSprache'] . "
                AND (cKundengruppe LIKE '%;-1;%' OR cKundengruppe LIKE '%;" . (int) $_SESSION['Kundengruppe']->kKundengruppe . ";%')
                AND ((dGueltigVon <= now() AND dGueltigBis >= now()) || (dGueltigVon <= now() AND dGueltigBis = '0000-00-00 00:00:00'))
            GROUP BY tumfrage.kUmfrage
            ORDER BY tumfrage.dGueltigVon DESC", 1
    );
}

/**
 * @return null|array
 */
function holeUmfrageUebersicht()
{
    // Modulprüfung
    $oNice = Nice::getInstance();
    if (!$oNice->checkErweiterung(SHOP_ERWEITERUNG_UMFRAGE)) {
        return;
    }
    // Umfrage Übersicht
    return Shop::DB()->query(
        "SELECT tumfrage.kUmfrage, tumfrage.kSprache, tumfrage.kKupon, tumfrage.cKundengruppe, tumfrage.cName, tumfrage.cBeschreibung,
            tumfrage.fGuthaben, tumfrage.nBonuspunkte, tumfrage.nAktiv, tumfrage.dGueltigVon, tumfrage.dGueltigBis, tumfrage.dErstellt, tseo.cSeo,
            DATE_FORMAT(tumfrage.dGueltigVon, '%d.%m.%Y  %H:%i') AS dGueltigVon_de,
            DATE_FORMAT(tumfrage.dGueltigBis, '%d.%m.%Y  %H:%i') AS dGueltigBis_de, count(tumfragefrage.kUmfrageFrage) AS nAnzahlFragen
            FROM tumfrage
            JOIN tumfragefrage ON tumfragefrage.kUmfrage = tumfrage.kUmfrage
            LEFT JOIN tseo ON tseo.cKey = 'kUmfrage'
                AND tseo.kKey = tumfrage.kUmfrage
                AND tseo.kSprache = " . (int) $_SESSION['kSprache'] . "
            WHERE tumfrage.nAktiv = 1
                AND tumfrage.kSprache = " . (int) $_SESSION['kSprache'] . "
                AND (cKundengruppe LIKE '%;-1;%' OR cKundengruppe LIKE '%;" . (int) $_SESSION['Kundengruppe']->kKundengruppe . ";%')
                AND ((dGueltigVon <= now() AND dGueltigBis >= now()) || (dGueltigVon <= now() AND dGueltigBis = '0000-00-00 00:00:00'))
            GROUP BY tumfrage.kUmfrage
            ORDER BY tumfrage.dGueltigVon DESC", 2
    );
}

/**
 * @param object $oUmfrage
 * @return null|void
 */
function bearbeiteUmfrageAuswertung($oUmfrage)
{
    global $cHinweis;

    // Modulprüfung
    $oNice = Nice::getInstance();
    if (!$oNice->checkErweiterung(SHOP_ERWEITERUNG_UMFRAGE)) {
        return;
    }

    setzeUmfrageErgebnisse();

    // Prüfe ob die Umfrage dem User einen Bonus gibt
    // $nBonus = 0 => Keinen
    // $nBonus = 1 => Kupon
    // $nBonus = 2 => Guthaben
    // $nBonus = 3 => Bonuspunkte
    $nBonus     = 0;
    $nBonusWert = 0;

    if ($_SESSION['Kunde']->kKunde > 0) {
        // Bekommt der Kunde einen Kupon und ist dieser Gültig?
        if ($oUmfrage->kKupon > 0) {
            $oSprache = Shop::DB()->query(
                "SELECT cISO
                    FROM tsprache
                    WHERE kSprache=" . $_SESSION['kSprache'], 1
            );

            $oKupon = Shop::DB()->query(
                "SELECT tkuponsprache.cName, tkupon.kKupon, tkupon.cCode
                    FROM tkupon
                    JOIN tkuponsprache ON tkuponsprache.kKupon = tkupon.kKupon
                    WHERE tkupon.kKupon = " . (int) $oUmfrage->kKupon . "
                        AND tkuponsprache.cISOSprache = '" . $oSprache->cISO . "'
                        AND tkupon.cAktiv = 'Y'
                        AND (tkupon.dGueltigAb <= now() AND tkupon.dGueltigBis >= now())
                        AND (tkupon.kKundengruppe = -1 OR tkupon.kKundengruppe = " . (int) $_SESSION['Kunde']->kKundengruppe . ")", 1
            );

            // Gültig
            if ($oKupon->kKupon > 0) {
                $nBonus     = 1;
                $nBonusWert = $oKupon->cCode;
                $cHinweis   = sprintf(Shop::Lang()->get('pollCoupon', 'messages'), $oKupon->cCode);
            }
        } elseif ($oUmfrage->fGuthaben > 0) { // Guthaben?
            $nBonus     = 2;
            $nBonusWert = $oUmfrage->fGuthaben;
            $cHinweis   = sprintf(Shop::Lang()->get('pollCredit', 'messages'), gibPreisStringLocalized($oUmfrage->fGuthaben));

            // Kunde Guthaben gutschreiben
            gibKundeGuthaben($oUmfrage->fGuthaben, $_SESSION['Kunde']->kKunde);
        } elseif ($oUmfrage->nBonuspunkte > 0) { // Bonuspunkte?
            $nBonus     = 3;
            $nBonusWert = $oUmfrage->nBonuspunkte;
            $cHinweis   = sprintf(Shop::Lang()->get('pollExtrapoint', 'messages'), $oUmfrage->nBonuspunkte);
            // ToDo: Bonuspunkte dem Kunden gutschreiben
        } else {
            $cHinweis .= Shop::Lang()->get('pollAdd', 'messages') . '<br>';
        }
    } else {
        $cHinweis .= Shop::Lang()->get('pollAdd', 'messages') . '<br>';
    }

    $_SESSION['Umfrage']->nEnde = 1;
}

/**
 * @param int    $kUmfrage
 * @param object $oUmfrage
 * @param array  $oUmfrageFrageTMP_arr
 * @param array  $oNavi_arr
 * @param int    $nAktuelleSeite
 * @return null|void
 */
function bearbeiteUmfrageDurchfuehrung($kUmfrage, $oUmfrage, &$oUmfrageFrageTMP_arr, &$oNavi_arr, &$nAktuelleSeite)
{
    global $smarty;
    // Modulprüfung
    $oNice = Nice::getInstance();
    if (!$oNice->checkErweiterung(SHOP_ERWEITERUNG_UMFRAGE) || !$kUmfrage || !isset($oUmfrage->kUmfrage)) {
        return;
    }
    $kUmfrage = intval($kUmfrage);
    // Ersten Trenner suchen
    $oUmfrageFrageTMP_arr = Shop::DB()->query(
        "SELECT *
            FROM tumfragefrage
            WHERE kUmfrage = " . $kUmfrage . "
            ORDER BY nSort", 2
    );

    $oNavi_arr      = baueSeitenNavi($oUmfrageFrageTMP_arr, $oUmfrage->nAnzahlFragen);
    $cSQL           = '';
    $nAktuelleSeite = 1;

    if (verifyGPCDataInteger('s') === 0) {
        unset($_SESSION['Umfrage']);
        $_SESSION['Umfrage']                    = new stdClass();
        $_SESSION['Umfrage']->kUmfrage          = $oUmfrage->kUmfrage;
        $_SESSION['Umfrage']->oUmfrageFrage_arr = array();
        $_SESSION['Umfrage']->nEnde             = 0;

        // Speicher alle Fragen in Session
        if (is_array($oUmfrageFrageTMP_arr) && count($oUmfrageFrageTMP_arr) > 0) {
            foreach ($oUmfrageFrageTMP_arr as $oUmfrageFrageTMP) {
                $_SESSION['Umfrage']->oUmfrageFrage_arr[$oUmfrageFrageTMP->kUmfrageFrage] = $oUmfrageFrageTMP;
            }
        }

        $cSQL .= " LIMIT " . $oNavi_arr[0]->nVon . ", " . $oNavi_arr[0]->nAnzahl;
    } else {
        $nAktuelleSeite = verifyGPCDataInteger('s');

        if (isset($_POST['next'])) {
            speicherFragenInSession($_POST);

            $kUmfrageFrageError = pruefeEingabe($_POST);
            if ($kUmfrageFrageError > 0) {
                if (!isset($cFehler)) {
                    $cFehler = '';
                }
                $cFehler .= Shop::Lang()->get('pollRequired', 'errorMessages') . "<br>";
            } else {
                $nAktuelleSeite++;
            }
        } elseif (isset($_POST['back'])) {
            $nAktuelleSeite--;
        }

        $cSQL .= " LIMIT " . $oNavi_arr[$nAktuelleSeite - 1]->nVon . ", " . $oNavi_arr[$nAktuelleSeite - 1]->nAnzahl;
    }
    // Fragen zur Umfrage holen
    $oUmfrageFrage_arr = Shop::DB()->query(
        "SELECT *
            FROM tumfragefrage
            WHERE kUmfrage = " . $kUmfrage . "
            ORDER BY nSort" . $cSQL, 2
    );

    if (is_array($oUmfrageFrage_arr) && count($oUmfrageFrage_arr) > 0) {
        foreach ($oUmfrageFrage_arr as $i => $oUmfrageFrage) {
            if ($oUmfrageFrage->cTyp !== 'text_klein' || $oUmfrageFrage->cTyp !== 'text_gross' || $oUmfrageFrage->cTyp !== 'text_statisch') {
                $oUmfrageFrage_arr[$i]->oUmfrageFrageAntwort_arr = array();
                $oUmfrageFrage_arr[$i]->oUmfrageMatrixOption_arr = array();
                $oUmfrageFrage_arr[$i]->oUmfrageFrageAntwort_arr = Shop::DB()->query(
                    "SELECT *
                        FROM tumfragefrageantwort
                        WHERE kUmfrageFrage = " . (int) $oUmfrageFrage->kUmfrageFrage . "
                        ORDER BY nSort", 2
                );

                if ($oUmfrageFrage->cTyp === 'matrix_single' || $oUmfrageFrage->cTyp === 'matrix_multi') {
                    $oUmfrageFrage->oUmfrageMatrixOption_arr = Shop::DB()->query(
                        "SELECT *
                            FROM tumfragematrixoption
                            WHERE kUmfrageFrage = " . (int) $oUmfrageFrage->kUmfrageFrage . "
                            ORDER BY nSort", 2
                    );
                }
            }
        }

        $oUmfrage->oUmfrageFrage_arr = $oUmfrageFrage_arr;
        $smarty->assign('nSessionFragenWerte_arr', findeFragenInSession($oUmfrageFrage_arr));
    }
}
