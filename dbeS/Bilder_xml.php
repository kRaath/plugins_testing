<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */
require_once dirname(__FILE__) . '/syncinclude.php';
$return        = 3;
$Einstellungen = null;
$oBranding_arr = null;
if (auth()) {
    checkFile();
    $Einstellungen = Shop::getSettings(array(CONF_BILDER));
    // Branding Einstellungen pro Bildkategorie
    $oBranding_arr = holeBilderEinstellungen();

    if (!$Einstellungen['bilder']['bilder_kategorien_breite']) {
        $Einstellungen['bilder']['bilder_kategorien_breite'] = 100;
    }
    if (!$Einstellungen['bilder']['bilder_kategorien_hoehe']) {
        $Einstellungen['bilder']['bilder_kategorien_hoehe'] = 100;
    }
    if (!$Einstellungen['bilder']['bilder_variationen_gross_breite']) {
        $Einstellungen['bilder']['bilder_variationen_gross_breite'] = 800;
    }
    if (!$Einstellungen['bilder']['bilder_variationen_gross_hoehe']) {
        $Einstellungen['bilder']['bilder_variationen_gross_hoehe'] = 800;
    }
    if (!$Einstellungen['bilder']['bilder_variationen_breite']) {
        $Einstellungen['bilder']['bilder_variationen_breite'] = 210;
    }
    if (!$Einstellungen['bilder']['bilder_variationen_hoehe']) {
        $Einstellungen['bilder']['bilder_variationen_hoehe'] = 210;
    }
    if (!$Einstellungen['bilder']['bilder_variationen_mini_breite']) {
        $Einstellungen['bilder']['bilder_variationen_mini_breite'] = 30;
    }
    if (!$Einstellungen['bilder']['bilder_variationen_mini_hoehe']) {
        $Einstellungen['bilder']['bilder_variationen_mini_hoehe'] = 30;
    }
    if (!$Einstellungen['bilder']['bilder_artikel_gross_breite']) {
        $Einstellungen['bilder']['bilder_artikel_gross_breite'] = 800;
    }
    if (!$Einstellungen['bilder']['bilder_artikel_gross_hoehe']) {
        $Einstellungen['bilder']['bilder_artikel_gross_hoehe'] = 800;
    }
    if (!$Einstellungen['bilder']['bilder_artikel_normal_breite']) {
        $Einstellungen['bilder']['bilder_artikel_normal_breite'] = 210;
    }
    if (!$Einstellungen['bilder']['bilder_artikel_normal_hoehe']) {
        $Einstellungen['bilder']['bilder_artikel_normal_hoehe'] = 210;
    }
    if (!$Einstellungen['bilder']['bilder_artikel_klein_breite']) {
        $Einstellungen['bilder']['bilder_artikel_klein_breite'] = 80;
    }
    if (!$Einstellungen['bilder']['bilder_artikel_klein_hoehe']) {
        $Einstellungen['bilder']['bilder_artikel_klein_hoehe'] = 80;
    }
    if (!$Einstellungen['bilder']['bilder_artikel_mini_breite']) {
        $Einstellungen['bilder']['bilder_artikel_mini_breite'] = 30;
    }
    if (!$Einstellungen['bilder']['bilder_artikel_mini_hoehe']) {
        $Einstellungen['bilder']['bilder_artikel_mini_hoehe'] = 30;
    }
    if (!$Einstellungen['bilder']['bilder_hersteller_normal_breite']) {
        $Einstellungen['bilder']['bilder_hersteller_normal_breite'] = 100;
    }
    if (!$Einstellungen['bilder']['bilder_hersteller_normal_hoehe']) {
        $Einstellungen['bilder']['bilder_hersteller_normal_hoehe'] = 100;
    }
    if (!$Einstellungen['bilder']['bilder_hersteller_klein_breite']) {
        $Einstellungen['bilder']['bilder_hersteller_klein_breite'] = 40;
    }
    if (!$Einstellungen['bilder']['bilder_hersteller_klein_hoehe']) {
        $Einstellungen['bilder']['bilder_hersteller_klein_hoehe'] = 40;
    }
    if (!$Einstellungen['bilder']['bilder_merkmal_normal_breite']) {
        $Einstellungen['bilder']['bilder_merkmal_normal_breite'] = 100;
    }
    if (!$Einstellungen['bilder']['bilder_merkmal_normal_hoehe']) {
        $Einstellungen['bilder']['bilder_merkmal_normal_hoehe'] = 100;
    }
    if (!$Einstellungen['bilder']['bilder_merkmal_klein_breite']) {
        $Einstellungen['bilder']['bilder_merkmal_klein_breite'] = 20;
    }
    if (!$Einstellungen['bilder']['bilder_merkmal_klein_hoehe']) {
        $Einstellungen['bilder']['bilder_merkmal_klein_hoehe'] = 20;
    }
    if (!$Einstellungen['bilder']['bilder_merkmalwert_normal_breite']) {
        $Einstellungen['bilder']['bilder_merkmalwert_normal_breite'] = 100;
    }
    if (!$Einstellungen['bilder']['bilder_merkmalwert_normal_hoehe']) {
        $Einstellungen['bilder']['bilder_merkmalwert_normal_hoehe'] = 100;
    }
    if (!$Einstellungen['bilder']['bilder_merkmalwert_klein_breite']) {
        $Einstellungen['bilder']['bilder_merkmalwert_klein_breite'] = 20;
    }
    if (!$Einstellungen['bilder']['bilder_merkmalwert_klein_hoehe']) {
        $Einstellungen['bilder']['bilder_merkmalwert_klein_hoehe'] = 20;
    }
    if (!$Einstellungen['bilder']['bilder_konfiggruppe_klein_breite']) {
        $Einstellungen['bilder']['bilder_konfiggruppe_klein_breite'] = 130;
    }
    if (!$Einstellungen['bilder']['bilder_konfiggruppe_klein_hoehe']) {
        $Einstellungen['bilder']['bilder_konfiggruppe_klein_hoehe'] = 130;
    }
    if (!$Einstellungen['bilder']['bilder_jpg_quali']) {
        $Einstellungen['bilder']['bilder_jpg_quali'] = 80;
    }
    if (!$Einstellungen['bilder']['bilder_dateiformat']) {
        $Einstellungen['bilder']['bilder_dateiformat'] = 'PNG';
    }
    if (!$Einstellungen['bilder']['bilder_hintergrundfarbe']) {
        $Einstellungen['bilder']['bilder_hintergrundfarbe'] = '#ffffff';
    }
    if (!$Einstellungen['bilder']['bilder_skalieren']) {
        $Einstellungen['bilder']['bilder_skalieren'] = 'N';
    }
    // tseo Sprache
    $oSprache = Shop::DB()->query("SELECT kSprache FROM tsprache WHERE cShopStandard = 'Y'", 1);
    $cSQL     = '';
    if (!$oSprache->kSprache) {
        $oSprache->kSprache = $_SESSION['kSprache'];
        $cSQL               = "AND tseo.kSprache = " . $oSprache->kSprache;
    }
    if ($oSprache->kSprache > 0) {
        $cSQL = " AND tseo.kSprache = " . $oSprache->kSprache;
    }
    $return  = 2;
    $archive = new PclZip($_FILES['data']['tmp_name']);
    if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
        Jtllog::writeLog('Entpacke: ' . $_FILES['data']['tmp_name'], JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
    }
    if ($list = $archive->listContent()) {
        $unzipPath = PFAD_ROOT . PFAD_DBES . PFAD_SYNC_TMP . basename($_FILES['data']['tmp_name']) . '_' . date('dhis');
        mkdir($unzipPath);
        $unzipPath .= '/';
        if ($archive->extract(PCLZIP_OPT_PATH, $unzipPath)) {
            if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                Jtllog::writeLog('Zip entpackt in ' . $unzipPath, JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
            }
            $return = 0;
            foreach ($list as $zip) {
                switch ($zip['filename']) {
                    case 'bilder_ka.xml':
                    case 'bilder_a.xml':
                    case 'bilder_k.xml':
                    case 'bilder_v.xml':
                    case 'bilder_m.xml':
                    case 'bilder_mw.xml':
                    case 'bilder_h.xml':
                        if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                            Jtllog::writeLog('bearbeite: ' . $unzipPath . $zip['filename'] . ' size: ' . filesize($unzipPath . $zip['filename']), JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
                        }
                        $d   = file_get_contents($unzipPath . $zip['filename']);
                        $xml = XML_unserialize($d);
                        bearbeite($xml, $unzipPath);
                        removeTemporaryFiles($unzipPath . $zip['filename']);
                        break;

                    case 'del_bilder_ka.xml':
                    case 'del_bilder_a.xml':
                    case 'del_bilder_k.xml':
                    case 'del_bilder_v.xml':
                    case 'del_bilder_m.xml':
                    case 'del_bilder_mw.xml':
                    case 'del_bilder_h.xml':
                        if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                            Jtllog::writeLog('bearbeite: ' . $unzipPath . $zip['filename'] . ' size: ' . filesize($unzipPath . $zip['filename']), JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
                        }
                        $d   = file_get_contents($unzipPath . $zip['filename']);
                        $xml = XML_unserialize($d);
                        bearbeiteDeletes($xml);
                        removeTemporaryFiles($unzipPath . $zip['filename']);
                        break;

                }
            }
            removeTemporaryFiles(substr($unzipPath, 0, -1), true);
        } elseif (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
            Jtllog::writeLog('EXTRACT Error : ' . $archive->errorInfo(true), JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
        }
    } elseif (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
        Jtllog::writeLog('FILE LIST Error : ' . $archive->errorInfo(true), JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
    }
}

if ($return == 1) {
    syncException('RET Error : ' . $archive->errorInfo(true));
}
echo $return;

if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
    Jtllog::writeLog('BEENDE: ' . $_FILES['data']['tmp_name'], JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
}

/**
 * @param array $xml
 * @param string $unzipPath
 */
function bearbeite($xml, $unzipPath)
{
    $img_arr                 = mapArray($xml['bilder'], 'tartikelpict', $GLOBALS['mArtikelPict']);
    $kategoriebild_arr       = mapArray($xml['bilder'], 'tkategoriepict', $GLOBALS['mKategoriePict']);
    $eigenschaftwertbild_arr = mapArray($xml['bilder'], 'teigenschaftwertpict', $GLOBALS['mEigenschaftWertPict']);
    $herstellerbild_arr      = mapArray($xml['bilder'], 'therstellerbild', $GLOBALS['mEigenschaftWertPict']);
    $merkmalwertbild_arr     = mapArray($xml['bilder'], 'tmerkmalwertbild', $GLOBALS['mEigenschaftWertPict']);
    $merkmalbild_arr         = mapArray($xml['bilder'], 'tMerkmalbild', $GLOBALS['mEigenschaftWertPict']);
    $konfigartikelbild_arr   = mapArray($xml['bilder'], 'tkonfiggruppebild', $GLOBALS['mKonfiggruppePict']);

    executeHook(
        HOOK_BILDER_XML_BEARBEITE, array(
            'Pfad'             => $unzipPath,
            'Artikel'          => &$img_arr,
            'Kategorie'        => &$kategoriebild_arr,
            'Eigenschaftswert' => &$eigenschaftwertbild_arr,
            'Hersteller'       => &$herstellerbild_arr,
            'Merkmalwert'      => &$merkmalwertbild_arr,
            'Merkmal'          => &$merkmalbild_arr,
            'Konfiggruppe'     => &$konfigartikelbild_arr
        )
    );
    //Artikelbilder
    foreach ($img_arr as $img) {
        if (strlen($img->cPfad) > 0) {
            $img->nNr           = intval($img->nNr);
            $imgFilename        = $img->cPfad;
            $Bildformat         = gibBildformat($unzipPath . $imgFilename);
            if (!$Bildformat) {
                if (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
                    Jtllog::writeLog('Bildformat des Artikelbildes konnte nicht ermittelt werden. Datei keine Bilddatei?: ' . $imgFilename, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
                }
                continue;
            }

            //first delete by kArtikelPict
            loescheArtikelPict($img->kArtikelPict, 0);

            // then delete by kArtikel + nNr since Wawi > .99923 has changed all kArtikelPict keys
            //@todo: undefined var $Artikelbild
            if (isset($Artikelbild->nNr) && $Artikelbild->nNr > 0) {
                loescheArtikelPict($img->kArtikel, $img->nNr);
            }

            if ($img->kMainArtikelBild > 0) {
                $oMainArtikelBild = Shop::DB()->query("SELECT cPfad FROM tartikelpict WHERE kArtikelPict = " . (int)$img->kMainArtikelBild, 1);
                if (isset($oMainArtikelBild->cPfad) && strlen($oMainArtikelBild->cPfad) > 0) {
                    $img->cPfad = neuerDateiname($oMainArtikelBild->cPfad);
                    DBUpdateInsert('tartikelpict', array($img), 'kArtikel', 'kArtikelpict');
                } else {
                    erstelleArtikelBild($img, $Bildformat, $unzipPath, $imgFilename);
                }
            } else {
                $oArtikelBild = Shop::DB()->query("SELECT * FROM tartikelpict WHERE kArtikelPict = " . (int)$img->kArtikelPict, 1);
                //update all references, if img is used by other products
                if (isset($oArtikelBild->cPfad) && strlen($oArtikelBild->cPfad) > 0) {
                    Shop::DB()->query("UPDATE tartikelpict SET cPfad = '{$oArtikelBild->cPfad}' WHERE kMainArtikelBild = " . (int)$oArtikelBild->kArtikelPict, 3);
                }
                erstelleArtikelBild($img, $Bildformat, $unzipPath, $imgFilename);
            }
        }
    }
    //Tmp Artikelbilder löschen
    if (count($img_arr) > 0) {
        $dir_handle = @opendir($unzipPath);
        while (false !== ($file = readdir($dir_handle))) {
            if ($file !== '.' && $file !== '..' && $file !== 'bilder_a.xml') {
                if (file_exists($unzipPath . $file)) {
                    if (unlink($unzipPath . $file)) {
                        if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                            Jtllog::writeLog('Artikelbild wurde geloescht: ' . $file, JTLLOG_LEVEL_DEBUG);
                        }
                    } elseif (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
                        Jtllog::writeLog('Artikelbild konnte nicht geloescht werden: ' . $file, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
                    }
                }
            }
        }
        @closedir($dir_handle);
    }
    //Kategoriebilder
    foreach ($kategoriebild_arr as $Kategoriebild) {
        if (strlen($Kategoriebild->cPfad) > 0) {
            $imgFilename  = $Kategoriebild->cPfad;
            $Bildformat   = gibBildformat($unzipPath . $imgFilename);
            if (!$Bildformat) {
                if (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
                    Jtllog::writeLog('Bildformat des Kategoriebildes konnte nicht ermittelt werden. Datei keine Bilddatei?: ' . $imgFilename, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
                }
                continue;
            }
            $Bildname = gibKategoriebildname($Kategoriebild, $Bildformat);

            $Kategoriebild->cPfad = $Bildname;
            $Kategoriebild->cPfad = neuerDateiname($Kategoriebild->cPfad);
            if (erstelleThumbnail(
                $GLOBALS['oBranding_arr']['Kategorie'],
                $unzipPath . $imgFilename,
                PFAD_KATEGORIEBILDER . $Kategoriebild->cPfad,
                $GLOBALS['Einstellungen']['bilder']['bilder_kategorien_breite'],
                $GLOBALS['Einstellungen']['bilder']['bilder_kategorien_hoehe'],
                1,
                $GLOBALS['Einstellungen']['bilder']['bilder_jpg_quali'],
                1,
                $GLOBALS['Einstellungen']['bilder']['container_verwenden']
            )) {
                DBUpdateInsert('tkategoriepict', array($Kategoriebild), 'kKategorie');
            }
            unlink($unzipPath . $imgFilename);
        }
    }
    //Variationsbilder
    foreach ($eigenschaftwertbild_arr as $Eigenschaftwertbild) {
        if (strlen($Eigenschaftwertbild->cPfad) > 0) {
            $imgFilename  = $Eigenschaftwertbild->cPfad;
            $Bildformat   = gibBildformat($unzipPath . $imgFilename);
            if (!$Bildformat) {
                if (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
                    Jtllog::writeLog('Bildformat des Eigenschaftwertbildes konnte nicht ermittelt werden. Datei keine Bilddatei?: ' . $imgFilename, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
                }
                continue;
            }
            $Bildname                   = gibEigenschaftwertbildname($Eigenschaftwertbild, $Bildformat);
            $Eigenschaftwertbild->cPfad = $Bildname;
            $Eigenschaftwertbild->cPfad = neuerDateiname($Eigenschaftwertbild->cPfad);
            erstelleThumbnail(
                $GLOBALS['oBranding_arr']['Variationen'],
                $unzipPath . $imgFilename,
                PFAD_VARIATIONSBILDER_GROSS . $Eigenschaftwertbild->cPfad,
                $GLOBALS['Einstellungen']['bilder']['bilder_variationen_gross_breite'],
                $GLOBALS['Einstellungen']['bilder']['bilder_variationen_gross_hoehe'],
                0,
                $GLOBALS['Einstellungen']['bilder']['bilder_jpg_quali'],
                1,
                $GLOBALS['Einstellungen']['bilder']['container_verwenden']
            );
            erstelleThumbnailBranded(
                PFAD_ROOT . PFAD_VARIATIONSBILDER_GROSS . $Eigenschaftwertbild->cPfad,
                PFAD_VARIATIONSBILDER_NORMAL . $Eigenschaftwertbild->cPfad,
                $GLOBALS['Einstellungen']['bilder']['bilder_variationen_breite'],
                $GLOBALS['Einstellungen']['bilder']['bilder_variationen_hoehe'],
                $GLOBALS['Einstellungen']['bilder']['bilder_jpg_quali'],
                $GLOBALS['Einstellungen']['bilder']['container_verwenden']
            );
            if (erstelleThumbnailBranded(
                PFAD_ROOT . PFAD_VARIATIONSBILDER_GROSS . $Eigenschaftwertbild->cPfad,
                PFAD_VARIATIONSBILDER_MINI . $Eigenschaftwertbild->cPfad,
                $GLOBALS['Einstellungen']['bilder']['bilder_variationen_mini_breite'],
                $GLOBALS['Einstellungen']['bilder']['bilder_variationen_mini_hoehe'],
                $GLOBALS['Einstellungen']['bilder']['bilder_jpg_quali'],
                $GLOBALS['Einstellungen']['bilder']['container_verwenden']
            )) {
                DBUpdateInsert('teigenschaftwertpict', array($Eigenschaftwertbild), 'kEigenschaftWert');
            }
            unlink($unzipPath . $imgFilename);
        }
    }
    //Herstellerbilder
    foreach ($herstellerbild_arr as $Herstellerbild) {
        $Herstellerbild->kHersteller = intval($Herstellerbild->kHersteller);
        if (strlen($Herstellerbild->cPfad) > 0 && $Herstellerbild->kHersteller > 0) {
            $imgFilename  = $Herstellerbild->cPfad;
            $Bildformat   = gibBildformat($unzipPath . $imgFilename);
            if (!$Bildformat) {
                if (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
                    Jtllog::writeLog('Bildformat des Herstellerbildes konnte nicht ermittelt werden. Datei keine Bilddatei?: ' . $imgFilename, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
                }
                continue;
            }
            $Hersteller = Shop::DB()->query(
                "SELECT cSeo
                    FROM thersteller
                    WHERE kHersteller = " . (int)$Herstellerbild->kHersteller, 1
            );
            if (isset($Hersteller->cSeo) && strlen($Hersteller->cSeo) > 0) {
                $Herstellerbild->cPfad = $Hersteller->cSeo . '.' . $Bildformat;
            } else {
                $Herstellerbild->cPfad .= '.' . $Bildformat;
            }
            $Herstellerbild->cPfad = neuerDateiname($Herstellerbild->cPfad);
            erstelleThumbnail(
                $GLOBALS['oBranding_arr']['Hersteller'],
                $unzipPath . $imgFilename,
                PFAD_HERSTELLERBILDER_NORMAL . $Herstellerbild->cPfad,
                $GLOBALS['Einstellungen']['bilder']['bilder_hersteller_normal_breite'],
                $GLOBALS['Einstellungen']['bilder']['bilder_hersteller_normal_hoehe'],
                0,
                $GLOBALS['Einstellungen']['bilder']['bilder_jpg_quali'],
                1,
                $GLOBALS['Einstellungen']['bilder']['container_verwenden']
            );
            if (erstelleThumbnailBranded(
                PFAD_ROOT . PFAD_HERSTELLERBILDER_NORMAL . $Herstellerbild->cPfad,
                PFAD_HERSTELLERBILDER_KLEIN . $Herstellerbild->cPfad,
                $GLOBALS['Einstellungen']['bilder']['bilder_hersteller_klein_breite'],
                $GLOBALS['Einstellungen']['bilder']['bilder_hersteller_klein_hoehe'],
                $GLOBALS['Einstellungen']['bilder']['bilder_jpg_quali'],
                $GLOBALS['Einstellungen']['bilder']['container_verwenden']
            )) {
                //thersteller updaten
                Shop::DB()->query("UPDATE thersteller SET cBildpfad = '" . $Herstellerbild->cPfad . "' WHERE kHersteller = " . (int)$Herstellerbild->kHersteller, 4);
            }
            unlink($unzipPath . $imgFilename);
        }
    }
    //Merkmalbilder
    foreach ($merkmalbild_arr as $Merkmalbild) {
        $Merkmalbild->kMerkmal = intval($Merkmalbild->kMerkmal);
        if (strlen($Merkmalbild->cPfad) > 0 && $Merkmalbild->kMerkmal > 0) {
            $imgFilename  = $Merkmalbild->cPfad;
            $Bildformat   = gibBildformat($unzipPath . $imgFilename);
            if (!$Bildformat) {
                if (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
                    Jtllog::writeLog('Bildformat des Merkmalbildes konnte nicht ermittelt werden. Datei keine Bilddatei?: ' . $imgFilename, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
                }
                continue;
            }
            $Merkmalbild->cPfad .= '.' . $Bildformat;
            $Merkmalbild->cPfad = neuerDateiname($Merkmalbild->cPfad);
            erstelleThumbnail(
                $GLOBALS['oBranding_arr']['Merkmale'],
                $unzipPath . $imgFilename,
                PFAD_MERKMALBILDER_NORMAL . $Merkmalbild->cPfad,
                $GLOBALS['Einstellungen']['bilder']['bilder_merkmal_normal_breite'],
                $GLOBALS['Einstellungen']['bilder']['bilder_merkmal_normal_hoehe'],
                0,
                $GLOBALS['Einstellungen']['bilder']['bilder_jpg_quali'],
                1,
                $GLOBALS['Einstellungen']['bilder']['container_verwenden']
            );
            if (erstelleThumbnailBranded(
                PFAD_ROOT . PFAD_MERKMALBILDER_NORMAL . $Merkmalbild->cPfad,
                PFAD_MERKMALBILDER_KLEIN . $Merkmalbild->cPfad,
                $GLOBALS['Einstellungen']['bilder']['bilder_merkmal_klein_breite'],
                $GLOBALS['Einstellungen']['bilder']['bilder_merkmal_klein_hoehe'],
                $GLOBALS['Einstellungen']['bilder']['bilder_jpg_quali'],
                $GLOBALS['Einstellungen']['bilder']['container_verwenden']
            )) {
                //tmerkmal updaten
                Shop::DB()->query("UPDATE tmerkmal SET cBildpfad = '" . $Merkmalbild->cPfad . "' WHERE kMerkmal = " . (int)$Merkmalbild->kMerkmal, 4);
            }
            unlink($unzipPath . $imgFilename);
        }
    }
    //Merkmalbilder
    foreach ($merkmalwertbild_arr as $Merkmalwertbild) {
        $Merkmalwertbild->kMerkmalWert = intval($Merkmalwertbild->kMerkmalWert);
        if (strlen($Merkmalwertbild->cPfad) > 0 && $Merkmalwertbild->kMerkmalWert > 0) {
            $imgFilename  = $Merkmalwertbild->cPfad;
            $Bildformat   = gibBildformat($unzipPath . $imgFilename);
            if (!$Bildformat) {
                if (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
                    Jtllog::writeLog('Bildformat des Merkmalwertbildes konnte nicht ermittelt werden. Datei keine Bilddatei?: ' . $imgFilename, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
                }
                continue;
            }
            $Merkmalwertbild->cPfad .= '.' . $Bildformat;
            $Merkmalwertbild->cPfad = neuerDateiname($Merkmalwertbild->cPfad);
            erstelleThumbnail(
                $GLOBALS['oBranding_arr']['Merkmalwerte'],
                $unzipPath . $imgFilename,
                PFAD_MERKMALWERTBILDER_NORMAL . $Merkmalwertbild->cPfad,
                $GLOBALS['Einstellungen']['bilder']['bilder_merkmalwert_normal_breite'],
                $GLOBALS['Einstellungen']['bilder']['bilder_merkmalwert_normal_hoehe'],
                0,
                $GLOBALS['Einstellungen']['bilder']['bilder_jpg_quali'],
                1,
                $GLOBALS['Einstellungen']['bilder']['container_verwenden']
            );
            if (erstelleThumbnailBranded(
                PFAD_ROOT . PFAD_MERKMALWERTBILDER_NORMAL . $Merkmalwertbild->cPfad,
                PFAD_MERKMALWERTBILDER_KLEIN . $Merkmalwertbild->cPfad,
                $GLOBALS['Einstellungen']['bilder']['bilder_merkmalwert_klein_breite'],
                $GLOBALS['Einstellungen']['bilder']['bilder_merkmalwert_klein_hoehe'],
                $GLOBALS['Einstellungen']['bilder']['bilder_jpg_quali'],
                $GLOBALS['Einstellungen']['bilder']['container_verwenden']
            )) {
                //tmerkmalwert updaten
                Shop::DB()->query(
                    "UPDATE tmerkmalwert
                        SET cBildpfad = '" . $Merkmalwertbild->cPfad . "'
                        WHERE kMerkmalWert = " . (int)$Merkmalwertbild->kMerkmalWert, 4
                );
                $oMerkmalwertbild               = new stdClass();
                $oMerkmalwertbild->kMerkmalWert = (int)$Merkmalwertbild->kMerkmalWert;
                $oMerkmalwertbild->cBildpfad    = $Merkmalwertbild->cPfad;

                DBUpdateInsert('tmerkmalwertbild', array($oMerkmalwertbild), 'kMerkmalWert');
            }
            unlink($unzipPath . $imgFilename);
        }
    }
    //Konfiggruppebilder
    foreach ($konfigartikelbild_arr as $Konfigbild) {
        $oKonfig                = new stdClass();
        $oKonfig->cBildPfad     = $Konfigbild->cPfad;
        $oKonfig->kKonfiggruppe = $Konfigbild->kKonfiggruppe;

        if (strlen($oKonfig->cBildPfad) > 0) {
            $imgFilename  = $oKonfig->cBildPfad;
            $Bildformat   = gibBildformat($unzipPath . $imgFilename);
            if (!$Bildformat) {
                if (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
                    Jtllog::writeLog('Bildformat des Konfiggruppenbildes konnte nicht ermittelt werden. Datei keine Bilddatei?: ' . $imgFilename, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
                }
                continue;
            }
            $Bildname = $oKonfig->kKonfiggruppe . '.' . $Bildformat;

            $oKonfig->cBildPfad = $Bildname;
            $oKonfig->cBildPfad = neuerDateiname($oKonfig->cBildPfad);

            $oBranding                               = new stdClass();
            $oBranding->oBrandingEinstellung         = new stdClass();
            $oBranding->oBrandingEinstellung->nAktiv = 0;

            if (erstelleThumbnail(
                $oBranding, $unzipPath . $imgFilename,
                PFAD_KONFIGURATOR_KLEIN . $oKonfig->cBildPfad,
                $GLOBALS['Einstellungen']['bilder']['bilder_konfiggruppe_klein_breite'],
                $GLOBALS['Einstellungen']['bilder']['bilder_konfiggruppe_klein_hoehe'],
                1,
                $GLOBALS['Einstellungen']['bilder']['bilder_jpg_quali'],
                1,
                $GLOBALS['Einstellungen']['bilder']['container_verwenden']
            )) {
                Shop::DB()->query(
                    "UPDATE tkonfiggruppe
                        SET cBildPfad='" . $oKonfig->cBildPfad . "'
                        WHERE kKonfiggruppe='" . $oKonfig->kKonfiggruppe . "'", 4
                );
            }
            unlink($unzipPath . $imgFilename);
        }
    }

    executeHook(
        HOOK_BILDER_XML_BEARBEITE_ENDE, array(
                                          'Artikel'          => &$img_arr,
                                          'Kategorie'        => &$kategoriebild_arr,
                                          'Eigenschaftswert' => &$eigenschaftwertbild_arr,
                                          'Hersteller'       => &$herstellerbild_arr,
                                          'Merkmalwert'      => &$merkmalwertbild_arr,
                                          'Merkmal'          => &$merkmalbild_arr,
                                          'Konfiggruppe'     => &$konfigartikelbild_arr
                                      )
    );
}

/**
 * @param object $img
 * @param string $Bildformat
 * @param string $unzipPath
 * @param string $imgFilename
 */
function erstelleArtikelBild($img, $Bildformat, $unzipPath, $imgFilename)
{
    $conf               = Shop::getSettings(array(CONF_BILDER));
    $Bildname           = gibArtikelbildname($img, $conf['bilder']['container_verwenden'] === 'Y' ? 'png' : $Bildformat);
    $img->cPfad         = $Bildname;
    $img->cPfad         = neuerDateiname($img->cPfad);
    erstelleThumbnail(
        $GLOBALS['oBranding_arr']['Artikel'],
        $unzipPath . $imgFilename,
        PFAD_PRODUKTBILDER_GROSS . $img->cPfad,
        $conf['bilder']['bilder_artikel_gross_breite'],
        $conf['bilder']['bilder_artikel_gross_hoehe'],
        1,
        $conf['bilder']['bilder_jpg_quali'],
        1,
        $conf['bilder']['container_verwenden']
    );
    erstelleThumbnailBranded(
        PFAD_ROOT . PFAD_PRODUKTBILDER_GROSS . $img->cPfad,
        PFAD_PRODUKTBILDER_NORMAL . $img->cPfad,
        $conf['bilder']['bilder_artikel_normal_breite'],
        $conf['bilder']['bilder_artikel_normal_hoehe'],
        $conf['bilder']['bilder_jpg_quali'],
        $conf['bilder']['container_verwenden']
    );
    erstelleThumbnailBranded(
        PFAD_ROOT . PFAD_PRODUKTBILDER_GROSS . $img->cPfad,
        PFAD_PRODUKTBILDER_KLEIN . $img->cPfad,
        $conf['bilder']['bilder_artikel_klein_breite'],
        $conf['bilder']['bilder_artikel_klein_hoehe'],
        $conf['bilder']['bilder_jpg_quali'],
        $conf['bilder']['container_verwenden']
    );
    if (erstelleThumbnailBranded(
        $unzipPath . $imgFilename,
        PFAD_PRODUKTBILDER_MINI . $img->cPfad,
        $conf['bilder']['bilder_artikel_mini_breite'],
        $conf['bilder']['bilder_artikel_mini_hoehe'],
        $conf['bilder']['bilder_jpg_quali'],
        $conf['bilder']['container_verwenden']
    )) {
        DBUpdateInsert('tartikelpict', array($img), 'kArtikel', 'kArtikelPict');
    }

    if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
        Jtllog::writeLog('Artikelbild wurde erstellt: ' . print_r($img, true), JTLLOG_LEVEL_DEBUG);
    }
}

/**
 * @param object $Eigenschaftwertbild
 * @param string $Bildformat
 * @return mixed|string
 */
function gibEigenschaftwertbildname($Eigenschaftwertbild, $Bildformat)
{
    global $cSQL;

    if (!$GLOBALS['Einstellungen']['bilder']['bilder_variation_namen'] || !$Eigenschaftwertbild->kEigenschaftWert) {
        return $Eigenschaftwertbild->cPfad . '.' . $Bildformat;
    }
    $Eigenschaftwert = Shop::DB()->query(
        "SELECT kEigenschaftWert, cArtNr, cName, kEigenschaft
            FROM teigenschaftwert
            WHERE kEigenschaftWert = " . (int)$Eigenschaftwertbild->kEigenschaftWert, 1
    );
    $Bildname = $Eigenschaftwert->kEigenschaftWert;
    if ($Eigenschaftwert->cName) {
        switch ($GLOBALS['Einstellungen']['bilder']['bilder_variation_namen']) {
            case 1:
                if (!empty($Eigenschaftwert->cArtNr)) {
                    $Bildname = 'var' . gibAusgeschriebeneUmlaute($Eigenschaftwert->cArtNr);
                }
                break;

            case 2:
                $Artikel = Shop::DB()->query(
                    "SELECT tartikel.cArtNr, tartikel.cBarcode, tartikel.cName, tseo.cSeo
                        FROM teigenschaftwert, teigenschaft, tartikel
                        LEFT JOIN tseo
                            ON tseo.cKey = 'kArtikel'
                            AND tseo.kKey = tartikel.kArtikel
                            " . $cSQL . "
                        WHERE teigenschaftwert.kEigenschaft=teigenschaft.kEigenschaft
                            AND teigenschaft.kArtikel=tartikel.kArtikel
                            AND teigenschaftwert.kEigenschaftWert = " . (int)$Eigenschaftwertbild->kEigenschaftWert, 1
                );
                if (!empty($Artikel->cArtNr) && !empty($Eigenschaftwert->cArtNr)) {
                    $Bildname = gibAusgeschriebeneUmlaute($Artikel->cArtNr) . '_' . gibAusgeschriebeneUmlaute($Eigenschaftwert->cArtNr);
                }
                break;

            case 3:
                $Artikel = Shop::DB()->query(
                    "SELECT tartikel.cArtNr, tartikel.cBarcode, tartikel.cName, tseo.cSeo
                        FROM teigenschaftwert, teigenschaft, tartikel
                        LEFT JOIN tseo
                            ON tseo.cKey = 'kArtikel'
                            AND tseo.kKey = tartikel.kArtikel
                            " . $cSQL . "
                        WHERE teigenschaftwert.kEigenschaft=teigenschaft.kEigenschaft
                            AND teigenschaft.kArtikel=tartikel.kArtikel
                            AND teigenschaftwert.kEigenschaftWert = " . $Eigenschaftwertbild->kEigenschaftWert, 1
                );

                $Eigenschaft = Shop::DB()->query("SELECT cName FROM teigenschaft WHERE kEigenschaft=" . $Eigenschaftwert->kEigenschaft, 1);
                if ((!empty($Artikel->cSeo) || !empty($Artikel->cName)) &&  !empty($Eigenschaft->cName) && !empty($Eigenschaftwert->cName)) {
                    if ($Artikel->cSeo) {
                        $Bildname = $Artikel->cSeo . '_' . gibAusgeschriebeneUmlaute($Eigenschaft->cName) . '_' . gibAusgeschriebeneUmlaute($Eigenschaftwert->cName);
                    } else {
                        $Bildname = gibAusgeschriebeneUmlaute($Artikel->cName) . '_' . gibAusgeschriebeneUmlaute($Eigenschaft->cName) . '_' . gibAusgeschriebeneUmlaute($Eigenschaftwert->cName);
                    }
                }
                break;
        }
    }
    $Bildname = streicheSonderzeichen($Bildname) . '.' . $Bildformat;

    return $Bildname;
}

/**
 * @param object $Kategoriebild
 * @param string $Bildformat
 * @return mixed|string
 */
function gibKategoriebildname($Kategoriebild, $Bildformat)
{
    global $cSQL;

    if (!$GLOBALS['Einstellungen']['bilder']['bilder_kategorie_namen'] || !$Kategoriebild->kKategorie) {
        return $Kategoriebild->cPfad . '.' . $Bildformat;
    }
    $attr = Shop::DB()->query(
        "SELECT cWert
            FROM tkategorieattribut
            WHERE kKategorie = " . (int)$Kategoriebild->kKategorie . "
            AND cName = '" . KAT_ATTRIBUT_BILDNAME . "'", 1
    );
    if (isset($attr->cWert)) {
        return $attr->cWert . '.' . $Bildformat;
    }
    $Kategorie = Shop::DB()->query(
        "SELECT tseo.cSeo, tkategorie.cName
            FROM tkategorie
            LEFT JOIN tseo
                ON tseo.cKey = 'kKategorie'
                AND tseo.kKey = tkategorie.kKategorie
                " . $cSQL . "
            WHERE tkategorie.kKategorie = " . (int)$Kategoriebild->kKategorie, 1
    );
    $Bildname  = $Kategoriebild->cPfad;
    if ($Kategorie->cName) {
        switch ($GLOBALS['Einstellungen']['bilder']['bilder_kategorie_namen']) {
            case 1:
                if ($Kategorie->cSeo) {
                    $Bildname = $Kategorie->cSeo;
                } else {
                    $Bildname = gibAusgeschriebeneUmlaute($Kategorie->cName);
                }
                $Bildname = streicheSonderzeichen($Bildname) . '.' . $Bildformat;
                break;

            default:
                return $Kategoriebild->cPfad . '.' . $Bildformat;
                break;

        }
    }

    return $Bildname;
}

/**
 * @param $img
 * @param $Bildformat
 * @return mixed|string
 */
function gibArtikelbildname($img, $Bildformat)
{
    global $cSQL;

    if ($img->kArtikel) {
        //Bildname Attribut als Funktionsattribut beim Artikel?
        $attr = Shop::DB()->query(
            "SELECT cWert
                FROM tartikelattribut
                WHERE kArtikel = " . (int)$img->kArtikel . "
                    AND cName = '" . FKT_ATTRIBUT_BILDNAME . "'", 1
        );
        if (isset($attr->cWert)) {
            if ($img->nNr > 1) {
                $attr->cWert .= '_' . $img->nNr;
            }

            return $attr->cWert . '.' . $Bildformat;
        }
    }

    if (!$GLOBALS['Einstellungen']['bilder']['bilder_artikel_namen'] || !$img->kArtikel) { //keine Einstellung bzw Einstellung = PK vom Artikel oder kein PK gesetzt
        return $img->cPfad . '.' . $Bildformat;
    }
    //Einstellung der Bildbenennung ist nicht PK
    $Artikel  = Shop::DB()->query(
        "SELECT tartikel.cArtNr, tseo.cSeo, tartikel.cName, tartikel.cBarcode
            FROM tartikel
            LEFT JOIN tseo
                ON tseo.cKey = 'kArtikel'
                AND tseo.kKey = tartikel.kArtikel
                " . $cSQL . "
            WHERE tartikel.kArtikel = " . (int)$img->kArtikel, 1
    );
    $Bildname = $img->cPfad;
    if ($Artikel->cName) {
        switch ($GLOBALS['Einstellungen']['bilder']['bilder_artikel_namen']) {
            case 1:
                if ($Artikel->cArtNr) {
                    $Bildname = gibAusgeschriebeneUmlaute($Artikel->cArtNr);
                }
                break;

            case 2:
                if ($Artikel->cSeo) {
                    $Bildname = $Artikel->cSeo;
                } else {
                    $Bildname = gibAusgeschriebeneUmlaute($Artikel->cName);
                }
                break;

            case 3:
                if ($Artikel->cArtNr) {
                    $Bildname = gibAusgeschriebeneUmlaute($Artikel->cArtNr) . '_';
                }
                if ($Artikel->cSeo) {
                    $Bildname .= $Artikel->cSeo;
                } else {
                    $Bildname .= gibAusgeschriebeneUmlaute($Artikel->cName);
                }
                break;

            case 4:
                if ($Artikel->cBarcode) {
                    $Bildname = gibAusgeschriebeneUmlaute($Artikel->cBarcode);
                }
                break;
            default:
                return $img->cPfad . '.' . $Bildformat;
                break;

        }
    } else {
        return $img->cPfad . '.' . $Bildformat;
    }

    if ($img->nNr > 1 && $Bildname != $img->cPfad) {
        $Bildname .= '_b' . $img->nNr;
    }
    if ($GLOBALS['Einstellungen']['bilder']['bilder_artikel_namen'] != 5 && $Bildname != $img->cPfad) {
        $Bildname = streicheSonderzeichen($Bildname) . '.' . $Bildformat;
    } else {
        $Bildname .= '.' . $Bildformat;
    }

    return $Bildname;
}

/**
 * @param string $str
 * @return mixed
 */
function gibAusgeschriebeneUmlaute($str)
{
    $src = array('ä', 'ö', 'ü', 'ß', 'Ä', 'Ö', 'Ü', utf8_decode('ä'), utf8_decode('ö'), utf8_decode('ü'), utf8_decode('ß'), utf8_decode('Ä'), utf8_decode('Ö'), utf8_decode('Ü'));
    $rpl = array('ae', 'oe', 'ue', 'ss', 'AE', 'OE', 'UE', 'ae', 'oe', 'ue', 'ss', 'AE', 'OE', 'UE');

    return str_replace($src, $rpl, $str);
}

/**
 * @param string $str
 * @return mixed
 */
function streicheSonderzeichen($str)
{
    $str = str_replace('/', '-', $str);
    $str = str_replace(' ', '-', $str);

    return preg_replace('/[^a-zA-Z0-9\.\-_]/', '', $str);
}

/**
 * @param string $imgFilename
 * @param string $zielbild
 * @param int    $breite
 * @param int    $hoehe
 * @param int    $quality
 * @param string $container
 * @return int
 */
function erstelleThumbnailBranded($imgFilename, $zielbild, $breite, $hoehe, $quality = 80, $container = 'N')
{
    $vergroessern = 0;
    if ($GLOBALS['Einstellungen']['bilder']['bilder_skalieren'] === 'Y') {
        $vergroessern = 1;
    }
    $ret                         = 0;
    $Bildformat                  = gibBildformat($imgFilename);
    list($width, $height, $type) = getimagesize($imgFilename);
    if ($width > 0 && $height > 0) {
        if (!$vergroessern && $width < $breite && $height < $hoehe) {
            if ($container === 'Y') {
                $im = imageload_container($imgFilename, $width, $height, $breite, $hoehe);
            } else {
                $im = imageload_alpha($imgFilename, $width, $height);
            }
            speichereBild($im, $Bildformat, PFAD_ROOT . $zielbild, $quality);
            @chmod(PFAD_ROOT . $zielbild, 0644);
            if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                Jtllog::writeLog('Bild erfolgreich erstellt: ' . $zielbild, JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
                Jtllog::writeLog('Reservierter Speicher: ' . convert(memory_get_usage()) . ' / ' . convert(memory_get_usage(true)), JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
            }

            return 1;
        }
        $ratio      = $width / $height;
        $new_width  = $breite;
        $new_height = round($new_width / $ratio);
        if ($new_height > $hoehe) {
            $new_height = $hoehe;
            $new_width  = round($new_height * $ratio);
        }
        if ($container === 'Y') {
            $im = imageload_container($imgFilename, $new_width, $new_height, $breite, $hoehe);
        } else {
            $im = imageload_alpha($imgFilename, $new_width, $new_height);
        }
        if (speichereBild($im, $Bildformat, PFAD_ROOT . $zielbild, $quality)) {
            $ret = 1;
            @chmod(PFAD_ROOT . $zielbild, 0644);
        } elseif (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
            Jtllog::writeLog('Bild konnte nicht erstellt werden. Fehler beim Speichern des Bildes: ' . $zielbild, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
        }
    } elseif (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
        Jtllog::writeLog('Bild konnte nicht erstellt werden. Fehler beim Speichern des Bildes: ' . $imgFilename, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
    }

    return $ret;
}

/**
 * @param object $oBranding
 * @param string $imgFilename
 * @param string $zielbild
 * @param int    $breite
 * @param int    $hoehe
 * @param int    $vergroessern
 * @param int    $quality
 * @param int    $brand
 * @param string $container
 * @return int
 */
function erstelleThumbnail($oBranding, $imgFilename, $zielbild, $breite, $hoehe, $vergroessern = 0, $quality = 80, $brand = 0, $container = 'N')
{
    $vergroessern = 0;
    if ($GLOBALS['Einstellungen']['bilder']['bilder_skalieren'] === 'Y') {
        $vergroessern = 1;
    }
    $ret        = 0;
    $Bildformat = gibBildformat($imgFilename);
    $im         = imageload_alpha($imgFilename);
    if ($im) {
        //bild skalieren
        list($width, $height, $type) = getimagesize($imgFilename);
        if (!$vergroessern && $width < $breite && $height < $hoehe) {
            //Bild nicht neu berechnen, nur verschieben
            if ($container === 'Y') {
                $im = imageload_container($imgFilename, $width, $height, $breite, $hoehe);
            } else {
                $im = imageload_alpha($imgFilename, $width, $height);
            }
            speichereBild(brandImage($im, $brand, $oBranding), $Bildformat, PFAD_ROOT . $zielbild, $quality);
            @chmod(PFAD_ROOT . $zielbild, 0644);
            if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                Jtllog::writeLog('Bild erfolgreich erstellt: ' . $zielbild, JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
                Jtllog::writeLog('Reservierter Speicher: ' . convert(memory_get_usage()) . ' / ' . convert(memory_get_usage(true)), JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
            }

            return 1;
        }
        $ratio      = $width / $height;
        $new_width  = $breite;
        $new_height = round($new_width / $ratio);
        if ($new_height > $hoehe) {
            $new_height = $hoehe;
            $new_width  = round($new_height * $ratio);
        }
        if ($container === 'Y') {
            $image_p = imageload_container($imgFilename, $new_width, $new_height, $breite, $hoehe);
        } else {
            $image_p = imageload_alpha($imgFilename, $new_width, $new_height);
        }
        if (speichereBild(brandImage($image_p, $brand, $oBranding), $Bildformat, PFAD_ROOT . $zielbild, $quality)) {
            $ret = 1;
            @chmod(PFAD_ROOT . $zielbild, 0644);
            if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                Jtllog::writeLog('Bild erfolgreich erstellt: ' . $zielbild, JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
                Jtllog::writeLog('Reservierter Speicher: ' . convert(memory_get_usage()) . ' / ' . convert(memory_get_usage(true)), JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
            }
        } elseif (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
            Jtllog::writeLog('Bild konnte nicht erstellt werden. Fehler beim Speichern des Bildes: ' . $zielbild, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
        }
    } elseif (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
        Jtllog::writeLog('Bild konnte nicht erstellt werden. Datei kein Bild?: ' . $imgFilename, JTLLOG_LEVEL_ERROR, false, 'Bilder_xml');
    }

    return $ret;
}

/**
 * @param $xml
 */
function bearbeiteDeletes($xml)
{
    executeHook(
        HOOK_BILDER_XML_BEARBEITEDELETES, array(
            'Artikel'          => (isset($xml['del_bilder']['kArtikelPict'])) ? $xml['del_bilder']['kArtikelPict'] : array(),
            'Kategorie'        => (isset($xml['del_bilder']['kKategoriePict'])) ? $xml['del_bilder']['kKategoriePict'] : array(),
            'KategoriePK'      => (isset($xml['del_bilder']['kKategorie'])) ? $xml['del_bilder']['kKategorie'] : array(),
            'Eigenschaftswert' => (isset($xml['del_bilder']['kEigenschaftWertPict'])) ? $xml['del_bilder']['kEigenschaftWertPict'] : array(),
            'Hersteller'       => (isset($xml['del_bilder']['kHersteller'])) ? $xml['del_bilder']['kHersteller'] : array(),
            'Merkmal'          => (isset($xml['del_bilder']['kMerkmal'])) ? $xml['del_bilder']['kMerkmal'] : array(),
            'Merkmalwert'      => (isset($xml['del_bilder']['kMerkmalWert'])) ? $xml['del_bilder']['kMerkmalWert'] : array(),
        )
    );

    //Artikelbilder löschen Wawi <= .99923
    if (isset($xml['del_bilder']['kArtikelPict'])) {
        if (is_array($xml['del_bilder']['kArtikelPict'])) {
            foreach ($xml['del_bilder']['kArtikelPict'] as $kArtikelPict) {
                if (intval($kArtikelPict) > 0) {
                    loescheArtikelPict((int)$kArtikelPict);
                }
            }
        } elseif (intval($xml['del_bilder']['kArtikelPict']) > 0) {
            loescheArtikelPict((int)$xml['del_bilder']['kArtikelPict']);
        }
    }
    //Artikelbilder löschen Wawi > .99923
    if (isset($xml['del_bilder']['tArtikelPict'])) {
        if (count($xml['del_bilder']['tArtikelPict']) > 1) {
            for ($i = 0; $i < (count($xml['del_bilder']['tArtikelPict']) / 2); $i++) {
                $index        = "{$i} attr";
                $oArtikelPict = (object)$xml['del_bilder']['tArtikelPict'][$index];
                loescheArtikelPict($oArtikelPict->kArtikel, $oArtikelPict->nNr);
            }
        } else {
            $oArtikelPict = (object)$xml['del_bilder']['tArtikelPict attr'];
            loescheArtikelPict($oArtikelPict->kArtikel, $oArtikelPict->nNr);
        }
    }
    //Kategoriebilder löschen Wawi <= .99923
    if (isset($xml['del_bilder']['kKategoriePict'])) {
        if (is_array($xml['del_bilder']['kKategoriePict'])) {
            foreach ($xml['del_bilder']['kKategoriePict'] as $kKategoriePict) {
                if (intval($kKategoriePict) > 0) {
                    loescheKategoriePict($kKategoriePict);
                }
            }
        } elseif (intval($xml['del_bilder']['kKategoriePict']) > 0) {
            loescheKategoriePict(intval($xml['del_bilder']['kKategoriePict']));
        }
    }
    //Kategoriebilder löschen Wawi > .99923
    if (isset($xml['del_bilder']['kKategorie'])) {
        foreach ((array)$xml['del_bilder']['kKategorie'] as $kKategorie) {
            if (intval($kKategorie) > 0) {
                loescheKategoriePict(null, $kKategorie);
            }
        }
    }
    //Variationsbilder löschen Wawi <= .99923
    if (isset($xml['del_bilder']['kEigenschaftWertPict'])) {
        if (is_array($xml['del_bilder']['kEigenschaftWertPict'])) {
            foreach ($xml['del_bilder']['kEigenschaftWertPict'] as $kEigenschaftWertPict) {
                if (intval($kEigenschaftWertPict) > 0) {
                    loescheEigenschaftwertPict($kEigenschaftWertPict);
                }
            }
        } elseif (intval($xml['del_bilder']['kEigenschaftWertPict']) > 0) {
            loescheEigenschaftwertPict(intval($xml['del_bilder']['kEigenschaftWertPict']));
        }
    }
    //Variationsbilder löschen Wawi > .99923
    if (isset($xml['del_bilder']['kEigenschaftWert'])) {
        if (is_array($xml['del_bilder']['kEigenschaftWert'])) {
            foreach ($xml['del_bilder']['kEigenschaftWert'] as $kEigenschaftWert) {
                if (intval($kEigenschaftWert) > 0) {
                    loescheEigenschaftwertPict(null, $kEigenschaftWert);
                }
            }
        } elseif (intval($xml['del_bilder']['kEigenschaftWert']) > 0) {
            loescheEigenschaftwertPict(null, intval($xml['del_bilder']['kEigenschaftWert']));
        }
    }
    //Herstellerbilder löschen
    if (isset($xml['del_bilder']['kHersteller'])) {
        if (is_array($xml['del_bilder']['kHersteller'])) {
            foreach ($xml['del_bilder']['kHersteller'] as $kHersteller) {
                if ((int)$kHersteller > 0) {
                    Shop::DB()->query("UPDATE thersteller SET cBildPfad = '' WHERE kHersteller = " . (int)$kHersteller, 4);
                }
            }
        } elseif ((int)$xml['del_bilder']['kHersteller'] > 0) {
            Shop::DB()->query("UPDATE thersteller SET cBildPfad = '' WHERE kHersteller = " . (int)$xml['del_bilder']['kHersteller'], 4);
        }
    }
    //Merkmalbilder löschen
    if (isset($xml['del_bilder']['kMerkmal'])) {
        if (is_array($xml['del_bilder']['kMerkmal'])) {
            foreach ($xml['del_bilder']['kMerkmal'] as $kMerkmal) {
                if ((int)$kMerkmal > 0) {
                    Shop::DB()->query("UPDATE tmerkmal SET cBildpfad = '' WHERE kMerkmal = " . (int)$kMerkmal, 4);
                }
            }
        } elseif (intval($xml['del_bilder']['kMerkmal']) > 0) {
            Shop::DB()->query("UPDATE tmerkmal SET cBildpfad = '' WHERE kMerkmal = " . (int)$xml['del_bilder']['kMerkmal'], 4);
        }
    }
    //Merkmalwertbilder löschen
    if (isset($xml['del_bilder']['kMerkmalWert'])) {
        if (is_array($xml['del_bilder']['kMerkmalWert'])) {
            foreach ($xml['del_bilder']['kMerkmalWert'] as $kMerkmalWert) {
                if ((int)$kMerkmalWert > 0) {
                    Shop::DB()->query("UPDATE tmerkmalwert SET cBildPfad = '' WHERE kMerkmalWert = " . (int)$kMerkmalWert, 4);
                    Shop::DB()->query("DELETE FROM tmerkmalwertbild WHERE kMerkmalWert = " . (int)$kMerkmalWert, 4);
                }
            }
        } elseif ((int)$xml['del_bilder']['kMerkmalWert'] > 0) {
            Shop::DB()->query("UPDATE tmerkmalwert SET cBildPfad = '' WHERE kMerkmalWert = " . intval($xml['del_bilder']['kMerkmalWert']), 4);
            Shop::DB()->query("DELETE FROM tmerkmalwertbild WHERE kMerkmalWert = " . intval($xml['del_bilder']['kMerkmalWert']), 4);
        }
    }
}

/**
 * @param int      $kArtikelPict
 * @param int|null $nNr
 */
function loescheArtikelPict($kArtikelPict, $nNr = null)
{
    $kArtikelPict = (int)$kArtikelPict;
    if ($kArtikelPict > 0) {
        $oArtikelPict = null;
        if (intval($nNr) > 0) {
            $oArtikelPict = Shop::DB()->select('tartikelpict', 'kArtikel', $kArtikelPict, 'nNr', (int)$nNr);
            $kArtikelPict = $oArtikelPict->kArtikelPict;
        }
        deleteArticleImage(null, 0, $kArtikelPict);
        if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
            Jtllog::writeLog('kArtikelPict gelöscht: ' . $kArtikelPict, JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
        }
    }
}

/**
 * @param int      $kKategoriePict
 * @param int|null $kKategorie
 */
function loescheKategoriePict($kKategoriePict, $kKategorie = null)
{
    if (!is_null($kKategoriePict)) {
        $kKategoriePict = intval($kKategoriePict);
        if ($kKategoriePict > 0) {
            Shop::DB()->delete('tkategoriepict', 'kKategoriePict', $kKategoriePict);
            if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                Jtllog::writeLog('kKategoriePict geloescht: ' . $kKategoriePict, JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
            }
        }
    } else {
        $kKategorie = intval($kKategorie);
        if ($kKategorie > 0) {
            Shop::DB()->delete('tkategoriepict', 'kKategorie', $kKategorie);
            if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                Jtllog::writeLog('kKategoriePict geloescht: ' . $kKategorie, JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
            }
        }
    }
}

/**
 * @param int $kEigenschaftwertPict
 * @param int $kEigenschaftwert
 */
function loescheEigenschaftwertPict($kEigenschaftwertPict, $kEigenschaftwert = null)
{
    $kEigenschaftwert     = (int)$kEigenschaftwert;
    $kEigenschaftwertPict = (int)$kEigenschaftwertPict;
    if ($kEigenschaftwert > 0) {
        Shop::DB()->delete('teigenschaftwertpict', 'kEigenschaftWert', $kEigenschaftwert);
        if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
            Jtllog::writeLog('kEigenschaftWert geloescht: ' . $kEigenschaftwert, JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
        }
    }

    if ($kEigenschaftwertPict > 0) {
        Shop::DB()->delete('teigenschaftwertpict', 'kEigenschaftwertPict', $kEigenschaftwertPict);
        if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
            Jtllog::writeLog('kEigenschaftwertPict geloescht: ' . $kEigenschaftwertPict, JTLLOG_LEVEL_DEBUG, false, 'Bilder_xml');
        }
    }
}

/**
 * @param object   $im
 * @param resource $brand
 * @param object   $oBranding
 * @return mixed
 */
function brandImage($im, $brand, $oBranding)
{
    if (!$brand || (isset($oBranding->oBrandingEinstellung->nAktiv) && $oBranding->oBrandingEinstellung->nAktiv == 0)) {
        return $im;
    }
    //file_exists will return true even if cBrandingBild is not set - check before to avoid warning
    if (!isset($oBranding->oBrandingEinstellung->cBrandingBild)) {
        return $im;
    }
    $brandingImage = PFAD_ROOT . PFAD_BRANDINGBILDER . $oBranding->oBrandingEinstellung->cBrandingBild;
    if (!file_exists($brandingImage)) {
        return $im;
    }

    $position     = $oBranding->oBrandingEinstellung->cPosition;
    $transparency = $oBranding->oBrandingEinstellung->dTransparenz;
    $brandingSize = $oBranding->oBrandingEinstellung->dGroesse;
    $randabstand  = $oBranding->oBrandingEinstellung->dRandabstand / 100;
    $branding     = imageload_alpha($brandingImage);

    if ($im && $branding) {
        $bildInfo           = getimagesize($brandingImage);
        $bildBreite         = imagesx($im);
        $bildHoehe          = imagesy($im);
        $brandingBreite     = imagesx($branding);
        $brandingHoehe      = imagesy($branding);
        $brandingNeueBreite = $brandingBreite;
        $brandingNeueHoehe  = $brandingHoehe;
        $image_branding     = $branding;
        //branding auf diese Breite skalieren
        if ($brandingSize > 0) {
            $brandingNeueBreite = round(($bildBreite * $brandingSize) / 100.0);
            $brandingNeueHoehe  = round(($brandingNeueBreite / $brandingBreite) * $brandingHoehe);

            $image_branding = imageload_alpha($brandingImage, $brandingNeueBreite, $brandingNeueHoehe);
        }
        //position bestimmen
        $brandingPosX = 0;
        $brandingPosY = 0;
        switch ($position) {
            case 'oben':
                $brandingPosX = $bildBreite / 2 - $brandingNeueBreite / 2;
                $brandingPosY = $bildHoehe * $randabstand;
                break;

            case 'oben-rechts':
                $brandingPosX = $bildBreite - $brandingNeueBreite - $bildBreite * $randabstand;
                $brandingPosY = $bildHoehe * $randabstand;
                break;

            case 'rechts':
                $brandingPosX = $bildBreite - $brandingNeueBreite - $bildBreite * $randabstand;
                $brandingPosY = $bildHoehe / 2 - $brandingNeueHoehe / 2;
                break;

            case 'unten-rechts':
                $brandingPosX = $bildBreite - $brandingNeueBreite - $bildBreite * $randabstand;
                $brandingPosY = $bildHoehe - $brandingNeueHoehe - $bildHoehe * $randabstand;
                break;

            case 'unten':
                $brandingPosX = $bildBreite / 2 - $brandingNeueBreite / 2;
                $brandingPosY = $bildHoehe - $brandingNeueHoehe - $bildHoehe * $randabstand;
                break;

            case 'unten-links':
                $brandingPosX = $bildBreite * $randabstand;
                $brandingPosY = $bildHoehe - $brandingNeueHoehe - $bildHoehe * $randabstand;
                break;

            case 'links':
                $brandingPosX = $bildBreite * $randabstand;
                $brandingPosY = $bildHoehe / 2 - $brandingNeueHoehe / 2;
                break;

            case 'oben-links':
                $brandingPosX = $bildBreite * $randabstand;
                $brandingPosY = $bildHoehe * $randabstand;
                break;

            case 'zentriert':
                $brandingPosX = $bildBreite / 2 - $brandingNeueBreite / 2;
                $brandingPosY = $bildHoehe / 2 - $brandingNeueHoehe / 2;
                break;

        }
        $brandingPosX = round($brandingPosX);
        $brandingPosY = round($brandingPosY);
        //bild mit branding composen
        imagealphablending($im, true);
        imagesavealpha($im, true);
        imagecopymerge_alpha(
            $im,
            $image_branding,
            $brandingPosX,
            $brandingPosY,
            0,
            0,
            $brandingNeueBreite,
            $brandingNeueHoehe,
            100 - $transparency
        );

        return $im;
    }

    return $im;
}

/**
 * @param $dst_im
 * @param $src_im
 * @param $dst_x
 * @param $dst_y
 * @param $src_x
 * @param $src_y
 * @param $src_w
 * @param $src_h
 * @param $pct
 * @return bool
 */
function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
{
    if (!isset($pct)) {
        return false;
    }
    $pct /= 100;
    // Get image width and height
    $w = imagesx($src_im);
    $h = imagesy($src_im);
    // Turn alpha blending off
    imagealphablending($src_im, false);
    // Find the most opaque pixel in the image (the one with the smallest alpha value)
    $minalpha = 127;
    /*
    for( $x = 0; $x < $w; $x++ )
    for( $y = 0; $y < $h; $y++ ){
        $alpha = ( imagecolorat( $src_im, $x, $y ) >> 24 ) & 0xFF;
        if( $alpha < $minalpha ){
            $minalpha = $alpha;
        }
    }
    */

    $minalpha = 0;

    //loop through image pixels and modify alpha for each
    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            //get current alpha value (represents the TANSPARENCY!)
            $colorxy = imagecolorat($src_im, $x, $y);
            $alpha   = ($colorxy >> 24) & 0xFF;
            //calculate new alpha
            if ($minalpha !== 127) {
                $alpha = 127 + 127 * $pct * ($alpha - 127) / (127 - $minalpha);
            } else {
                $alpha += 127 * $pct;
            }
            //get the color index with new alpha
            $alphacolorxy = imagecolorallocatealpha($src_im, ($colorxy >> 16) & 0xFF, ($colorxy >> 8) & 0xFF, $colorxy & 0xFF, $alpha);
            //set pixel with the new color + opacity
            if (!imagesetpixel($src_im, $x, $y, $alphacolorxy)) {
                return false;
            }
        }
    }
    // The image copy
    imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);

    return true;
}

/**
 * @param $dst_im
 * @param $src_im
 * @param $dst_x
 * @param $dst_y
 * @param $src_x
 * @param $src_y
 * @param $src_w
 * @param $src_h
 * @param $pct
 */
function imagecopymerge_alpha_fast($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
{
    $opacity = $pct;
    $cut     = imagecreatetruecolor($src_w, $src_h);

    imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
}

/**
 * @param string $imgFilename
 * @return bool|string
 */
function gibBildformat($imgFilename)
{
    if (file_exists($imgFilename)) {
        $size = getimagesize($imgFilename);
        $type = $size[2];
        switch ($type) {
            case IMAGETYPE_JPEG:
                return 'jpg';
                break;

            case IMAGETYPE_PNG:
                if (function_exists('imagecreatefrompng')) {
                    return 'png';
                }
                break;

            case IMAGETYPE_GIF:
                if (function_exists('imagecreatefromgif')) {
                    return 'gif';
                }
                break;

            case IMAGETYPE_BMP:
                if (function_exists('imagecreatefromwbmp')) {
                    return 'bmp';
                }
                break;

        }
    }

    return false;
}

/**
 * @param string $img
 * @param int    $nWidth
 * @param int    $nHeight
 * @param int    $nContainerWidth
 * @param int    $nContainerHeight
 * @return resource
 */
function imageload_container($img, $nWidth, $nHeight, $nContainerWidth, $nContainerHeight)
{
    $imgInfo = getimagesize($img);
    switch ($imgInfo[2]) {
        case 1:
            $im = imagecreatefromgif($img);
            break;

        case 2:
            $im = imagecreatefromjpeg($img);
            break;

        case 3:
            $im = imagecreatefrompng($img);
            break;

        default:
            $im = imagecreatefromjpeg($img);
            break;

    }

    if (($nWidth == 0 && $nHeight == 0)) {
        $nHeight = $imgInfo[1];
        $nWidth  = $imgInfo[0];
    }
    $nWidth  = round($nWidth);
    $nHeight = round($nHeight);
    $newImg  = imagecreatetruecolor($nContainerWidth, $nContainerHeight);
    // hintergrundfarbe
    $format = strtolower($GLOBALS['Einstellungen']['bilder']['bilder_dateiformat']);
    if ($format == 'jpg') {
        $rgb   = html2rgb($GLOBALS['Einstellungen']['bilder']['bilder_hintergrundfarbe']);
        $color = imagecolorallocate($newImg, $rgb[0], $rgb[1], $rgb[2]);
    } else {
        $color = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
    }
    imagealphablending($newImg, false);
    imagesavealpha($newImg, true);
    imagefilledrectangle($newImg, 0, 0, $nContainerWidth, $nContainerHeight, $color);

    $nPosX = ($nContainerWidth / 2) - ($nWidth / 2);
    $nPosY = ($nContainerHeight / 2) - ($nHeight / 2);

    imagecopyresampled($newImg, $im, $nPosX, $nPosY, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);

    return $newImg;
}

/**
 * @param string $img
 * @param int    $nWidth
 * @param int    $nHeight
 * @return resource
 */
function imageload_alpha($img, $nWidth = 0, $nHeight = 0)
{
    $imgInfo = getimagesize($img);
    switch ($imgInfo[2]) {
        case 1:
            $im = imagecreatefromgif($img);
            break;

        case 2:
            $im = imagecreatefromjpeg($img);
            break;

        case 3:
            $im = imagecreatefrompng($img);
            break;

    }

    if (($nWidth == 0 && $nHeight == 0)) {
        $nHeight = $imgInfo[1];
        $nWidth  = $imgInfo[0];
    }

    $nWidth  = round($nWidth);
    $nHeight = round($nHeight);
    $newImg  = imagecreatetruecolor($nWidth, $nHeight);

    if (!$newImg) {
        return $img;
    }

    // hintergrundfarbe
    $format = strtolower($GLOBALS['Einstellungen']['bilder']['bilder_dateiformat']);
    if ($format == 'jpg') {
        $rgb   = html2rgb($GLOBALS['Einstellungen']['bilder']['bilder_hintergrundfarbe']);
        $color = imagecolorallocate($newImg, $rgb[0], $rgb[1], $rgb[2]);
    } else {
        $color = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
    }

    imagealphablending($newImg, false);
    imagesavealpha($newImg, true);
    imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $color);
    //@todo: check. was:
//	imagefilledrectangle($newImg, 0, 0, $nContainerWidth, $nContainerHeight, $color);
    //but $nContainerWidth and $nContainerHeight are undefined.

    imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);

    return $newImg;
}

/**
 * @param $pfad
 * @return string
 */
function neuerDateiname($pfad)
{
    $format = strtolower($GLOBALS['Einstellungen']['bilder']['bilder_dateiformat']);
    $pfad   = substr($pfad, 0, -3);
    $pfad .= $format;

    return $pfad;
}

/**
 * @param resource $im
 * @param string   $format
 * @param string   $pfad
 * @param int      $quality
 * @return bool
 */
function speichereBild($im, $format, $pfad, $quality = 80)
{
    if (!$format || !$im) {
        return false;
    }

    $pfad = neuerDateiname($pfad);

    switch ($format) {
        case 'jpg':
            if (!function_exists('imagejpeg')) {
                return false;
            }

            return imagejpeg($im, $pfad, $quality);
            break;

        case 'png':
            if (!function_exists('imagepng')) {
                return false;
            }

            return imagepng($im, $pfad);
            break;

        case 'gif':
            if (!function_exists('imagegif')) {
                return false;
            }

            return imagegif($im, $pfad);
            break;

        case 'bmp':
            if (!function_exists('imagewbmp')) {
                return false;
            }

            return imagewbmp($im, $pfad);
            break;

    }

    return false;
}

/**
 * @return array
 */
function holeBilderEinstellungen()
{
    $oBranding_arr    = array();
    $oBrandingTMP_arr = Shop::DB()->query("SELECT * FROM tbranding", 2);

    if (is_array($oBrandingTMP_arr) && count($oBrandingTMP_arr) > 0) {
        foreach ($oBrandingTMP_arr as $oBrandingTMP) {
            $oBranding_arr[$oBrandingTMP->cBildKategorie] = $oBrandingTMP;
        }
        foreach ($oBranding_arr as $i => $oBranding) {
            $oBranding_arr[$i]->oBrandingEinstellung = Shop::DB()->query(
                "SELECT *
                    FROM tbrandingeinstellung
                    WHERE kBranding = " . (int)$oBranding->kBranding, 1
            );
        }
    }

    return $oBranding_arr;
}
