<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */
require_once dirname(__FILE__) . '/includes/admininclude.php';

$oAccount->permission('ORDER_PACKAGE_VIEW', true, true);

require_once PFAD_ROOT . PFAD_ADMIN . PFAD_INCLUDES . 'toolsajax_inc.php';

$cHinweis     = '';
$cFehler      = '';
$step         = 'zusatzverpackung';
$oSprache_arr = gibAlleSprachen();
// Zusatzverpackung speichern
if (isset($_POST['eintragen']) && intval($_POST['eintragen']) === 1 && validateToken()) {
    $kVerpackung         = intval($_POST['kVerpackung']);
    $fBrutto             = (isset($_POST['fBrutto'])) ? doubleval($_POST['fBrutto']) : 0;
    $fMindestbestellwert = (isset($_POST['fMindestbestellwert'])) ? doubleval($_POST['fMindestbestellwert']) : 0;
    $fKostenfrei         = (isset($_POST['fKostenfrei'])) ? doubleval($_POST['fKostenfrei']) : 0;
    $kSteuerklasse       = (isset($_POST['kSteuerklasse'])) ? intval($_POST['kSteuerklasse']) : 0;
    $kKundengruppe_arr   = (isset($_POST['kKundengruppe'])) ? $_POST['kKundengruppe'] : null;
    $nAktiv              = (isset($_POST['nAktiv'])) ? intval($_POST['nAktiv']) : 0;

    if (isset($_POST['cName_' . $oSprache_arr[0]->cISO]) && strlen($_POST['cName_' . $oSprache_arr[0]->cISO]) > 0) {
        if (is_array($kKundengruppe_arr) && count($kKundengruppe_arr) > 0) {
            if (!isset($oVerpackung)) {
                $oVerpackung = new stdClass();
            }
            $oVerpackung->kSteuerklasse = $kSteuerklasse;
            $oVerpackung->cName         = strip_tags(trim($_POST['cName_' . $oSprache_arr[0]->cISO]));

            if ($kKundengruppe_arr[0] == '-1') {
                $oVerpackung->cKundengruppe = '-1';
            } else {
                $oVerpackung->cKundengruppe = ';' . implode(';', $kKundengruppe_arr) . ';';
            }
            $oVerpackung->fBrutto             = $fBrutto;
            $oVerpackung->fMindestbestellwert = $fMindestbestellwert;
            $oVerpackung->fKostenfrei         = $fKostenfrei;
            $oVerpackung->nAktiv              = $nAktiv;
            // Update?
            if ($kVerpackung > 0) {
                Shop::DB()->query(
                    "DELETE tverpackung, tverpackungsprache
                        FROM tverpackung
                        LEFT JOIN tverpackungsprache ON tverpackungsprache.kVerpackung = tverpackung.kVerpackung
                        WHERE tverpackung.kVerpackung = " . $kVerpackung, 3
                );

                $oVerpackung->kVerpackung = $kVerpackung;
                Shop::DB()->insert('tverpackung', $oVerpackung);
            } else {
                $kVerpackung = Shop::DB()->insert('tverpackung', $oVerpackung);
            }
            // In tverpackungsprache adden
            if (is_array($oSprache_arr) && count($oSprache_arr) > 0) {
                foreach ($oSprache_arr as $i => $oSprache) {
                    $oVerpackungSprache              = new stdClass();
                    $oVerpackungSprache->kVerpackung = $kVerpackung;
                    $oVerpackungSprache->cISOSprache = $oSprache->cISO;

                    if (strlen($_POST['cName_' . $oSprache->cISO]) > 0) {
                        $oVerpackungSprache->cName = $_POST['cName_' . $oSprache->cISO];
                    } else {
                        $oVerpackungSprache->cName = $_POST['cName_' . $oSprache_arr[0]->cISO];
                    }
                    if (strlen($_POST['cBeschreibung_' . $oSprache->cISO]) > 0) {
                        $oVerpackungSprache->cBeschreibung = $_POST['cBeschreibung_' . $oSprache->cISO];
                    } else {
                        $oVerpackungSprache->cBeschreibung = $_POST['cBeschreibung_' . $oSprache_arr[0]->cISO];
                    }
                    Shop::DB()->insert('tverpackungsprache', $oVerpackungSprache);
                    unset($oVerpackungSprache);
                }
            }

            unset($oVerpackung);
            $cHinweis .= 'Die Verpackung "' . $_POST['cName_' . $oSprache_arr[0]->cISO] . '" wurde erfolgreich gespeichert.<br />';
        } else {
            $cFehler .= 'Fehler: Bitte w&auml;hlen Sie mindestens eine Kundengruppe aus.<br />';
        }
    } else {
        $cFehler .= 'Fehler: Bitte geben Sie der Verpackung einen Namen.<br />';
    }
} elseif (isset($_POST['bearbeiten']) && intval($_POST['bearbeiten']) === 1 && validateToken()) { // Verpackungen bearbeiten (aktualisieren / loeschen)
    if (isset($_POST['loeschen']) && ($_POST['loeschen'] === 'Löschen' || utf8_decode($_POST['loeschen'] === 'Löschen') || $_POST['loeschen'] === utf8_decode('Löschen'))) {
        if (is_array($_POST['kVerpackung']) && count($_POST['kVerpackung']) > 0) {
            foreach ($_POST['kVerpackung'] as $kVerpackung) {
                $kVerpackung = intval($kVerpackung);
                // tverpackung loeschen
                Shop::DB()->delete('tverpackung', 'kVerpackung', $kVerpackung);
                // tverpackungsprache loeschen
                Shop::DB()->delete('tverpackungsprache', 'kVerpackung', $kVerpackung);
            }

            $cHinweis .= 'Die markierten Verpackungen wurden erfolgreich gel&ouml;scht.<br />';
        } else {
            $cFehler .= 'Fehler: Bitte markieren Sie mindestens eine Verpackung.<br />';
        }
    } elseif (isset($_POST['aktualisieren']) && $_POST['aktualisieren'] === 'Aktualisieren' && validateToken()) { // Aktualisieren
        // Alle Verpackungen deaktivieren
        Shop::DB()->query("UPDATE tverpackung SET nAktiv = 0", 3);
        if (is_array($_POST['nAktiv']) && count($_POST['nAktiv']) > 0) {
            foreach ($_POST['nAktiv'] as $kVerpackung) {
                $kVerpackung = (int)$kVerpackung;
                Shop::DB()->query("UPDATE tverpackung SET nAktiv = 1 WHERE kVerpackung = " . $kVerpackung, 3);
            }

            $cHinweis .= 'Ihre markierten Verpackungen wurden erfolgreich aktualisiert.<br />';
        }
    }
} elseif (verifyGPCDataInteger('edit') > 0 && validateToken()) { // Editieren
    $kVerpackung = verifyGPCDataInteger('edit');
    $oVerpackung = Shop::DB()->select('tverpackung', 'kVerpackung', $kVerpackung);

    if ($oVerpackung->kVerpackung > 0) {
        $oVerpackung->oSprach_arr = array();
        $oVerpackungSprach_arr    = Shop::DB()->query("SELECT cISOSprache, cName, cBeschreibung FROM tverpackungsprache WHERE kVerpackung = " . $kVerpackung, 2);
        if (is_array($oVerpackungSprach_arr) && count($oVerpackungSprach_arr) > 0) {
            foreach ($oVerpackungSprach_arr as $oVerpackungSprach) {
                $oVerpackung->oSprach_arr[$oVerpackungSprach->cISOSprache] = $oVerpackungSprach;
            }
        }
        $oKundengruppe                  = gibKundengruppeObj($oVerpackung->cKundengruppe);
        $oVerpackung->kKundengruppe_arr = $oKundengruppe->kKundengruppe_arr;
        $oVerpackung->cKundengruppe_arr = $oKundengruppe->cKundengruppe_arr;
    }

    $smarty->assign('kVerpackung', $oVerpackung->kVerpackung)
           ->assign('oVerpackungEdit', $oVerpackung);
}

// tverpackungsprache anzeigen
if (isset($_GET['a']) && intval($_GET['a']) > 0 && validateToken()) {
    $step                   = 'anzeigen';
    $kVerpackung            = intval($_GET['a']);
    $oVerpackungSprache_arr = Shop::DB()->query("SELECT * FROM tverpackungsprache WHERE kVerpackung = " . $kVerpackung, 2);
    $smarty->assign('oVerpackungSprache_arr', $oVerpackungSprache_arr);
} else {
    // Kundengruppen holen
    $oKundengruppe_arr = Shop::DB()->query("SELECT kKundengruppe, cName FROM tkundengruppe", 2);
    // Steuerklassen
    $oSteuerklasse_arr = Shop::DB()->query("SELECT * FROM tsteuerklasse", 2);
    // Verpackung aus der DB holen und assignen
    $oVerpackung_arr = Shop::DB()->query("SELECT * FROM tverpackung", 2);

    // cKundengruppe exploden
    if (is_array($oVerpackung_arr) && count($oVerpackung_arr) > 0) {
        foreach ($oVerpackung_arr as $i => $oVerpackung) {
            $oKundengruppe                          = gibKundengruppeObj($oVerpackung->cKundengruppe);
            $oVerpackung_arr[$i]->kKundengruppe_arr = $oKundengruppe->kKundengruppe_arr;
            $oVerpackung_arr[$i]->cKundengruppe_arr = $oKundengruppe->cKundengruppe_arr;
        }
    }
    $smarty->assign('oKundengruppe_arr', $oKundengruppe_arr)
           ->assign('oSteuerklasse_arr', $oSteuerklasse_arr)
           ->assign('oVerpackung_arr', $oVerpackung_arr);
}
$smarty->assign('hinweis', $cHinweis)
       ->assign('fehler', $cFehler)
       ->assign('step', $step)
       ->assign('oSprache_arr', $oSprache_arr)
       ->display('zusatzverpackung.tpl');

/**
 * @param string $cKundengruppe
 * @return null
 */
function gibKundengruppeObj($cKundengruppe)
{
    $oKundengruppe        = new stdClass();
    $kKundengruppeTMP_arr = array();
    $cKundengruppeTMP_arr = array();

    if (strlen($cKundengruppe) > 0) {
        // Kundengruppen holen
        $oKundengruppe_arr = Shop::DB()->query("SELECT kKundengruppe, cName FROM tkundengruppe", 2);
        $kKundengruppe_arr = explode(';', $cKundengruppe);
        if (is_array($kKundengruppe_arr) && count($kKundengruppe_arr) > 0) {
            if (!in_array('-1', $kKundengruppe_arr)) {
                foreach ($kKundengruppe_arr as $kKundengruppe) {
                    $kKundengruppe          = intval($kKundengruppe);
                    $kKundengruppeTMP_arr[] = $kKundengruppe;
                    if (is_array($oKundengruppe_arr) && count($oKundengruppe_arr) > 0) {
                        foreach ($oKundengruppe_arr as $oKundengruppe) {
                            if ($oKundengruppe->kKundengruppe == $kKundengruppe) {
                                $cKundengruppeTMP_arr[] = $oKundengruppe->cName;
                                break;
                            }
                        }
                    }
                }
            } else {
                if (count($oKundengruppe_arr) > 0) {
                    foreach ($oKundengruppe_arr as $oKundengruppe) {
                        $kKundengruppeTMP_arr[] = $oKundengruppe->kKundengruppe;
                        $cKundengruppeTMP_arr[] = $oKundengruppe->cName;
                    }
                }
            }
        }
    }
    $oKundengruppe->kKundengruppe_arr = $kKundengruppeTMP_arr;
    $oKundengruppe->cKundengruppe_arr = $cKundengruppeTMP_arr;

    return $oKundengruppe;
}
