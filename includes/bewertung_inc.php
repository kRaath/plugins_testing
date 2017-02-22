<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

/**
 * Fügt für einen bestimmten Artikel, in einer bestimmten Sprache eine Bewertung hinzu.
 *
 * @param int    $kArtikel
 * @param int    $kKunde
 * @param int    $kSprache
 * @param string $cTitel
 * @param string $cText
 * @param int    $nSterne
 * @return bool
 */
function speicherBewertung($kArtikel, $kKunde, $kSprache, $cTitel, $cText, $nSterne)
{
    $kArtikel = intval($kArtikel);
    $kKunde   = intval($kKunde);
    $kSprache = intval($kSprache);
    $nSterne  = intval($nSterne);
    // Prüfe ob Kunde eingeloggt
    if ($kKunde > 0) {
        $conf = Shop::getSettings(array(CONF_BEWERTUNG));
        // Sollen Bewertungen überhaupt aktiv sein
        if ($conf['bewertung']['bewertung_anzeigen'] === 'Y') {
            $cTitel = StringHandler::htmlentities(StringHandler::filterXSS($cTitel));
            $cText  = StringHandler::htmlentities(StringHandler::filterXSS($cText));

            if ($kArtikel > 0 && $kSprache > 0 && $cTitel !== '' && $cText !== '' && $nSterne > 0) {
                unset($oBewertungBereitsVorhanden);

                // Prüfe ob die Einstellung (Bewertung nur bei bereits gekauftem Artikel) gesetzt ist und der Kunde den Artikel bereits gekauft hat
                if (pruefeKundeArtikelGekauft($kArtikel, $_SESSION['Kunde']->kKunde)) {
                    header("Location: index.php?a={$kArtikel}&bewertung_anzeigen=1&cFehler=f03");
                    exit;
                }
                unset($oBewertung);
                $fBelohnung                  = 0.0;
                $oBewertung                  = new stdClass();
                $oBewertung->kArtikel        = $kArtikel;
                $oBewertung->kKunde          = $kKunde;
                $oBewertung->kSprache        = $kSprache;
                $oBewertung->cName           = $_SESSION['Kunde']->cVorname . ' ' . substr($_SESSION['Kunde']->cNachname, 0, 1);
                $oBewertung->cTitel          = $cTitel;
                $oBewertung->cText           = strip_tags($cText);
                $oBewertung->nHilfreich      = 0;
                $oBewertung->nNichtHilfreich = 0;
                $oBewertung->nSterne         = $nSterne;
                $oBewertung->nAktiv          = 0;
                $oBewertung->dDatum          = date('Y-m-d H:i:s', time());

                if ($conf['bewertung']['bewertung_freischalten'] === 'N') {
                    $oBewertung->nAktiv = 1;
                }

                executeHook(HOOK_BEWERTUNG_INC_SPEICHERBEWERTUNG, array('rating' => &$oBewertung));
                // Speicher Bewertung
                $kBewertung    = Shop::DB()->insert('tbewertung', $oBewertung);
                $nFreischalten = 1;
                if ($conf['bewertung']['bewertung_freischalten'] === 'N') {
                    $nFreischalten = 0;
                    aktualisiereDurchschnitt($kArtikel, $conf['bewertung']['bewertung_freischalten']);
                    $fBelohnung = checkeBewertungGuthabenBonus($kBewertung, $conf);
                    // Clear Cache
                    Shop::Cache()->flushTags(array(CACHING_GROUP_ARTICLE . '_' . $kArtikel));
                }
                unset($oBewertungBereitsVorhanden);
                if ($nFreischalten == 0) {
                    if ($fBelohnung > 0) {
                        header('Location: index.php?a=' . $kArtikel . '&bewertung_anzeigen=1&fB=' . $fBelohnung . '&cHinweis=h04', true, 301);
                        exit;
                    } else {
                        header('Location: index.php?a=' . $kArtikel . '&bewertung_anzeigen=1&cHinweis=h01', true, 303);
                        exit;
                    }
                } else {
                    header('Location: index.php?a=' . $kArtikel . '&bewertung_anzeigen=1&cHinweis=h05', true, 303);
                    exit;
                }
            } else {
                header('Location: index.php?a=' . $kArtikel . '&bewertung_anzeigen=1&cFehler=f01', true, 303);
                exit;
            }
        }
    }

    return false;
}

/**
 * Speichert für eine bestimmte Bewertung und bestimmten Kunden ab, ob sie hilfreich oder nicht hilfreich war.
 *
 * @param int $kArtikel
 * @param int $kKunde
 * @param int $kSprache
 * @param int $bewertung_seite
 * @param int $bewertung_sterne
 */
function speicherHilfreich($kArtikel, $kKunde, $kSprache, $bewertung_seite = 1, $bewertung_sterne = 0)
{
    $kArtikel   = (int)$kArtikel;
    $kKunde     = (int)$kKunde;
    $kSprache   = (int)$kSprache;
    $bHilfreich = 0;
    // Prüfe ob Kunde eingeloggt
    if ($kKunde > 0) {
        // Sollen Bewertungen überhaupt aktiv sein
        $conf = Shop::getSettings(array(CONF_BEWERTUNG));
        if ($conf['bewertung']['bewertung_anzeigen'] === 'Y') {
            // Sollen Bewertungen hilfreich überhaupt aktiv sein
            if ($conf['bewertung']['bewertung_hilfreich_anzeigen'] === 'Y') {
                if ($kArtikel > 0 && $kSprache > 0) {
                    // Hole alle Bewertungen für den auktuellen Artikel und Sprache
                    $oBewertung_arr = Shop::DB()->query(
                        "SELECT kBewertung
                            FROM tbewertung
                            WHERE kArtikel = " . $kArtikel . "
                            AND kSprache = " . $kSprache, 2
                    );
                    if (is_array($oBewertung_arr) && count($oBewertung_arr) > 0) {
                        $kBewertung = 0;
                        foreach ($oBewertung_arr as $oBewertung) {
                            // Prüf ob die Bewertung als Hilfreich gemarkt ist
                            if (isset($_POST['hilfreich_' . $oBewertung->kBewertung])) {
                                $kBewertung = (int)$oBewertung->kBewertung;
                                $bHilfreich = 1;
                            }
                            // Prüf ob die Bewertung als nicht Hilfreich gemarkt ist
                            if (isset($_POST['nichthilfreich_' . $oBewertung->kBewertung])) {
                                $kBewertung = (int)$oBewertung->kBewertung;
                                $bHilfreich = 0;
                            }
                        }
                        // Weiterleitungsstring bauen
                        $cWeiterleitung = '&btgseite=' . $bewertung_seite . '&btgsterne=' . $bewertung_sterne;
                        // Hole alle Einträge aus tbewertunghilfreich für eine bestimmte Bewertung und einen bestimmten Kunde
                        $oBewertungHilfreich = Shop::DB()->query(
                            "SELECT kKunde, nBewertung
                                FROM tbewertunghilfreich
                                WHERE kBewertung = " . $kBewertung . "
                                AND kKunde = " . $kKunde, 1
                        );
                        // Hat der Kunde für diese Bewertung noch keine hilfreich flag gesetzt?
                        if (intval($oBewertungHilfreich->kKunde) === 0) {
                            unset($oBewertungHilfreich);
                            $oBewertung = Shop::DB()->query(
                                "SELECT kKunde
                                    FROM tbewertung
                                    WHERE kBewertung = " . $kBewertung, 1
                            );

                            if ($oBewertung->kKunde != $_SESSION['Kunde']->kKunde) {
                                $oBewertungHilfreich             = new stdClass();
                                $oBewertungHilfreich->kBewertung = $kBewertung;
                                $oBewertungHilfreich->kKunde     = $kKunde;
                                $oBewertungHilfreich->nBewertung = 0;
                                // Wenn Hilfreich neu für eine Bewertung eingetragen wird und diese positiv ist
                                if ($bHilfreich == 1) {
                                    $oBewertungHilfreich->nBewertung = 1;
                                    Shop::DB()->query(
                                        "UPDATE tbewertung
                                            SET nHilfreich = nHilfreich+1
                                            WHERE kBewertung = " . $kBewertung, 3
                                    );
                                } else {
                                    // Wenn Hilfreich neu für eine Bewertung eingetragen wird und diese negativ ist
                                    $oBewertungHilfreich->nBewertung = 0;
                                    Shop::DB()->query(
                                        "UPDATE tbewertung
                                            SET nNichtHilfreich = nNichtHilfreich+1
                                            WHERE kBewertung = " . $kBewertung, 3
                                    );
                                }

                                executeHook(HOOK_BEWERTUNG_INC_SPEICHERBEWERTUNGHILFREICH, array('rating' => &$oBewertungHilfreich));

                                Shop::DB()->insert('tbewertunghilfreich', $oBewertungHilfreich);
                                header('Location: index.php?a=' . $kArtikel . '&bewertung_anzeigen=1&cHinweis=h02' . $cWeiterleitung, true, 303);
                                exit;
                            }
                        } elseif (intval($oBewertungHilfreich->kKunde) > 0) {
                            // Wenn Hilfreich nicht neu (wechsel) für eine Bewertung eingetragen wird und diese positiv ist
                            if ($bHilfreich == 1 && intval($oBewertungHilfreich->nBewertung) != $bHilfreich) {
                                Shop::DB()->query(
                                    "UPDATE tbewertung
                                        SET nHilfreich = nHilfreich+1, nNichtHilfreich = nNichtHilfreich-1
                                        WHERE kBewertung = " . $kBewertung, 3
                                );
                            } // Wenn Hilfreich neu für (wechsel) eine Bewertung eingetragen wird und diese negativ ist
                            elseif ($bHilfreich == 0 && intval($oBewertungHilfreich->nBewertung) != $bHilfreich) {
                                Shop::DB()->query(
                                    "UPDATE tbewertung
                                        SET nHilfreich = nHilfreich-1, nNichtHilfreich = nNichtHilfreich+1
                                        WHERE kBewertung = " . $kBewertung, 3
                                );
                            }

                            Shop::DB()->query(
                                "UPDATE tbewertunghilfreich
                                    SET nBewertung = " . $bHilfreich . "
                                    WHERE kBewertung = " . $kBewertung . "
                                        AND kKunde = " . $kKunde, 3
                            );

                            unset($oBewertungHilfreich);

                            header('Location: index.php?a=' . $kArtikel . '&bewertung_anzeigen=1&cHinweis=h03' . $cWeiterleitung, true, 303);
                            exit;
                        }
                    }
                }
            }
        }
    }
}

/**
 * @param int    $kArtikel
 * @param string $cFreischalten
 * @return bool
 */
function aktualisiereDurchschnitt($kArtikel, $cFreischalten)
{
    $cFreiSQL = '';
    $kArtikel = (int)$kArtikel;
    if ($cFreischalten === 'Y') {
        $cFreiSQL = ' AND nAktiv=1';
    }

    $oAnzahlBewertung = Shop::DB()->query(
        "SELECT count(*) AS nAnzahl
            FROM tbewertung
            WHERE kArtikel = " . $kArtikel . $cFreiSQL, 1
    );

    if (isset($oAnzahlBewertung->nAnzahl) && $oAnzahlBewertung->nAnzahl == 1) {
        $cFreiSQL = '';
    } elseif (isset($oAnzahlBewertung->nAnzahl) && $oAnzahlBewertung->nAnzahl == 0) {
        Shop::DB()->delete('tartikelext', 'kArtikel', $kArtikel);

        return false;
    }

    $oBewDurchschnitt = Shop::DB()->query(
        "SELECT (sum(nSterne) / count(*)) AS fDurchschnitt
            FROM tbewertung
            WHERE kArtikel = " . $kArtikel . $cFreiSQL, 1
    );

    if (isset($oBewDurchschnitt->fDurchschnitt) && $oBewDurchschnitt->fDurchschnitt > 0) {
        Shop::DB()->delete('tartikelext', 'kArtikel', $kArtikel);
        $oArtikelExt                          = new stdClass();
        $oArtikelExt->kArtikel                = $kArtikel;
        $oArtikelExt->fDurchschnittsBewertung = doubleval($oBewDurchschnitt->fDurchschnitt);

        Shop::DB()->insert('tartikelext', $oArtikelExt);
    }

    return true;
}

/**
 * @param int $kArtikel
 * @param int $kKunde
 * @return int
 */
function pruefeKundeArtikelBewertet($kArtikel, $kKunde)
{
    // Pürfen ob der Bewerter schon diesen Artikel bewertet hat
    if ($kKunde > 0) {
        $oBewertung = Shop::DB()->query(
            "SELECT tbewertung.kKunde
                FROM tbewertung
                WHERE tbewertung.kKunde = " . (int)$kKunde . "
                AND tbewertung.kArtikel = " . (int)$kArtikel . "
                AND tbewertung.kSprache = " . (int)Shop::$kSprache . "
                LIMIT 1", 1
        );

        // Kunde hat den Artikel schon bewertet
        if (isset($oBewertung->kKunde) && $oBewertung->kKunde > 0) {
            return 1;
        }
    }

    return 0;
}

/**
 * @param int $kArtikel
 * @param int $kKunde
 * @return int
 */
function pruefeKundeArtikelGekauft($kArtikel, $kKunde)
{
    $kArtikel = (int)$kArtikel;
    $kKunde   = (int)$kKunde;
    // Prüfen ob der Bewerter diesen Artikel bereits gekauft hat
    if ($kKunde > 0 && $kArtikel > 0) {
        $conf = Shop::getSettings(array(CONF_BEWERTUNG));
        if ($conf['bewertung']['bewertung_artikel_gekauft'] === 'Y') {
            $oBestellung = Shop::DB()->query(
                "SELECT tbestellung.kBestellung
                    FROM tbestellung
                    LEFT JOIN tartikel ON tartikel.kVaterArtikel = {$kArtikel}
                    JOIN twarenkorb ON twarenkorb.kWarenkorb = tbestellung.kWarenkorb
                    JOIN twarenkorbpos ON twarenkorbpos.kWarenkorb = twarenkorb.kWarenkorb
                    WHERE tbestellung.kKunde = {$kKunde}
                        AND (twarenkorbpos.kArtikel = {$kArtikel} OR twarenkorbpos.kArtikel = tartikel.kArtikel)", 1
            );

            if (!isset($oBestellung->kBestellung) || !$oBestellung->kBestellung) {
                return 1; // Kunde hat diesen Artikel noch nicht gekauft und darf somit laut Einstellung keine Bewertung abgeben
            }
        }
    }

    return 0;
}

/**
 * @param int   $kBewertung
 * @param array $Einstellungen
 * @return float
 */
function checkeBewertungGuthabenBonus($kBewertung, $Einstellungen)
{
    $fBelohnung = 0.0;
    $kBewertung = intval($kBewertung);
    // Ist Guthaben freigeschaltet? Wenn ja, schreibe dem Kunden den richtigen Betrag gut
    if ($Einstellungen['bewertung']['bewertung_guthaben_nutzen'] === 'Y') {
        // Hole Kunden und cText der Bewertung
        unset($oBewertung);
        $oBewertung = Shop::DB()->query(
            "SELECT kKunde, cText
                FROM tbewertung
                WHERE kBewertung = " . $kBewertung, 1
        );
        $kKunde                  = intval($oBewertung->kKunde);
        $oBewertungGuthabenBonus = Shop::DB()->query(
            "SELECT sum(fGuthabenBonus) AS fGuthabenProMonat
                FROM tbewertungguthabenbonus
                WHERE kKunde=" . $kKunde . "
                    AND MONTH(dDatum)=" . date('m'), 1
        );
        if (doubleval($oBewertungGuthabenBonus->fGuthabenProMonat) <= doubleval($Einstellungen['bewertung']['bewertung_max_guthaben'])) {
            // Reichen die Zeichen in der Bewertung, um das Stufe 2 Guthaben zu erhalten?
            if ($Einstellungen['bewertung']['bewertung_stufe2_anzahlzeichen'] <= strlen($oBewertung->cText)) {
                // Prüfen ob die max. Belohnung + das aktuelle Guthaben, das Max des Monats überscchreitet
                // Falls ja, nur die Differenz von Kundenguthaben zu Max im Monat auszahlen
                if ((doubleval($oBewertungGuthabenBonus->fGuthabenProMonat) + doubleval($Einstellungen['bewertung']['bewertung_stufe2_guthaben'])) >
                    doubleval($Einstellungen['bewertung']['bewertung_max_guthaben'])) {
                    $fBelohnung = doubleval($Einstellungen['bewertung']['bewertung_max_guthaben']) - doubleval($oBewertungGuthabenBonus->fGuthabenProMonat);
                } else {
                    $fBelohnung = $Einstellungen['bewertung']['bewertung_stufe2_guthaben'];
                }
                // tkunde Guthaben updaten
                Shop::DB()->query(
                    "UPDATE tkunde
                        SET fGuthaben=fGuthaben+" . doubleval($fBelohnung) . "
                            WHERE kKunde=" . $kKunde, 3
                );

                // tbewertungguthabenbonus eintragen
                unset($oBewertungGuthabenBonus);
                $oBewertungGuthabenBonus                 = new stdClass();
                $oBewertungGuthabenBonus->kBewertung     = $kBewertung;
                $oBewertungGuthabenBonus->kKunde         = $kKunde;
                $oBewertungGuthabenBonus->fGuthabenBonus = doubleval($fBelohnung);
                $oBewertungGuthabenBonus->dDatum         = 'now()';
                Shop::DB()->insert('tbewertungguthabenbonus', $oBewertungGuthabenBonus);
            } else {
                // Prüfen ob die max. Belohnung + das aktuelle Guthaben, das Max des Monats überscchreitet
                // Falls ja, nur die Differenz von Kundenguthaben zu Max im Monat auszahlen
                if ((doubleval($oBewertungGuthabenBonus->fGuthabenProMonat) + doubleval($Einstellungen['bewertung']['bewertung_stufe1_guthaben'])) >
                    doubleval($Einstellungen['bewertung']['bewertung_max_guthaben'])) {
                    $fBelohnung = doubleval($Einstellungen['bewertung']['bewertung_max_guthaben']) - doubleval($oBewertungGuthabenBonus->fGuthabenProMonat);
                } else {
                    $fBelohnung = $Einstellungen['bewertung']['bewertung_stufe1_guthaben'];
                }
                // tkunde Guthaben updaten
                Shop::DB()->query(
                    "UPDATE tkunde
                        SET fGuthaben = fGuthaben+" . doubleval($fBelohnung) . "
                        WHERE kKunde = " . $kKunde, 3
                );

                // tbewertungguthabenbonus eintragen
                $oBewertungGuthabenBonus                 = new stdClass();
                $oBewertungGuthabenBonus->kBewertung     = $kBewertung;
                $oBewertungGuthabenBonus->kKunde         = $kKunde;
                $oBewertungGuthabenBonus->fGuthabenBonus = doubleval($fBelohnung);
                $oBewertungGuthabenBonus->dDatum         = 'now()';
                Shop::DB()->insert('tbewertungguthabenbonus', $oBewertungGuthabenBonus);
            }
            require_once PFAD_ROOT . PFAD_INCLUDES . 'mailTools.php';
            $oKunde                       = new Kunde($oBewertungGuthabenBonus->kKunde);
            $obj                          = new stdClass();
            $obj->tkunde                  = $oKunde;
            $obj->oBewertungGuthabenBonus = $oBewertungGuthabenBonus;
            sendeMail(MAILTEMPLATE_BEWERTUNG_GUTHABEN, $obj);
        }
    }

    return $fBelohnung;
}

/**
 * @param int $kBewertung
 * @return bool
 */
function BewertungsGuthabenBonusLoeschen($kBewertung)
{
    $kBewertung = (int)$kBewertung;
    if ($kBewertung > 0) {
        $oBewertung = Shop::DB()->query(
            "SELECT kBewertung, kKunde
                FROM tbewertung
                WHERE kBewertung = " . $kBewertung, 1
        );
        if (isset($oBewertung->kBewertung) && $oBewertung->kBewertung > 0) {
            $oBewertungGuthabenBonus = Shop::DB()->query(
                "SELECT fGuthabenBonus, kBewertungGuthabenBonus
                    FROM tbewertungguthabenbonus
                    WHERE kBewertung = " . (int)$oBewertung->kBewertung . "
                        AND kKunde = " . (int)$oBewertung->kKunde, 1
            );
            if (isset($oBewertungGuthabenBonus->kBewertungGuthabenBonus) && $oBewertungGuthabenBonus->kBewertungGuthabenBonus > 0) {
                $oKunde = Shop::DB()->query("SELECT fGuthaben FROM tkunde WHERE kKunde = " . (int)$oBewertung->kKunde, 1);
                if (is_object($oKunde)) {
                    Shop::DB()->delete('tbewertungguthabenbonus', 'kBewertungGuthabenBonus', $oBewertungGuthabenBonus->kBewertungGuthabenBonus);
                    $fGuthaben = $oKunde->fGuthaben - doubleval($oBewertungGuthabenBonus->fGuthabenBonus);
                    Shop::DB()->query(
                        "UPDATE tkunde
                            SET fGuthaben = " . (($fGuthaben > 0) ? $fGuthaben : 0) . "
                            WHERE kKunde = " . (int)$oBewertung->kKunde, 3
                    );

                    return true;
                }
            }
        }
    }

    return false;
}
