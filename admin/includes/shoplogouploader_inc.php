<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

/**
 * Speichert das aktuelle ShopLogo
 *
 * @param array $cFiles_arr
 * @return int
 * 1 = Alles O.K.
 * 2 = Dateiname leer
 * 3 = Dateityp entspricht nicht der Konvention (Nur jpg/gif/png/bmp/ Bilder) oder fehlt
 * 4 = Konnte nicht bewegen
 */
function saveShopLogo($cFiles_arr)
{
    if (!file_exists(PFAD_ROOT . PFAD_SHOPLOGO)) {
        mkdir(PFAD_ROOT . PFAD_SHOPLOGO);
    }
    // Prüfe Dateiname
    if (strlen($cFiles_arr['shopLogo']['name']) > 0) {
        // Prüfe Dateityp
        if ($cFiles_arr['shopLogo']['type'] !== 'image/jpeg' && $cFiles_arr['shopLogo']['type'] !== 'image/pjpeg' &&
            $cFiles_arr['shopLogo']['type'] !== 'image/gif' && $cFiles_arr['shopLogo']['type'] !== 'image/png' &&
            $cFiles_arr['shopLogo']['type'] !== 'image/bmp' && $cFiles_arr['shopLogo']['type'] !== 'image/x-png' &&
            $cFiles_arr['shopLogo']['type'] !== 'image/jpg'
        ) {
            return 3;
        } else { // Dateityp entspricht nicht der Konvention (Nur jpg/gif/png/bmp/ Bilder) oder fehlt
            $cUploadDatei = PFAD_ROOT . PFAD_SHOPLOGO . $cFiles_arr['shopLogo']['name'];
            $mvRes        = move_uploaded_file($cFiles_arr['shopLogo']['tmp_name'], $cUploadDatei);
            if ($mvRes === true) {
                $option                        = new stdClass();
                $option->kEinstellungenSektion = CONF_LOGO;
                $option->cName                 = 'shop_logo';
                $option->cWert                 = $cFiles_arr['shopLogo']['name'];
                Shop::DB()->update('teinstellungen', 'cName', 'shop_logo', $option);
                Shop::Cache()->flushTags(array(CACHING_GROUP_OPTION));

                return 1; // Alles O.K.
            }

            return 4;
        }
    } else {
        return 2; // Dateiname fehlt
    }
}

/**
 * @var string $logo
 * @return bool
 */
function deleteShopLogo($logo)
{
    if (is_file(PFAD_ROOT . $logo)) {
        return unlink(PFAD_ROOT . $logo);
    }

    return false;
}

/**
 * @return bool
 */
function loescheAlleShopBilder()
{
    if (is_dir(PFAD_ROOT . PFAD_SHOPLOGO)) {
        if ($dh = opendir(PFAD_ROOT . PFAD_SHOPLOGO)) {
            while (($file = readdir($dh)) !== false) {
                if ($file !== '.' && $file !== '..' && $file !== '.gitkeep') {
                    @unlink(PFAD_ROOT . PFAD_SHOPLOGO . $file);
                }
            }
            closedir($dh);

            return true;
        }
    }

    return false;
}

/**
 * @param string $cTyp
 * @return string
 */
function mappeFileTyp($cTyp)
{
    switch ($cTyp) {
        case 'image/jpeg':
            return '.jpg';
            break;
        case 'image/pjpeg':
            return '.jpg';
            break;
        case 'image/gif':
            return '.gif';
            break;
        case 'image/png':
            return '.png';
            break;
        case 'image/bmp':
            return '.bmp';
            break;
        // Adding MIME types that Internet Explorer returns
        case 'image/x-png':
            return '.png';
            break;
        case 'image/jpg':
            return '.jpg';
            break;
        //default jpg
        default:
            return '.jpg';
            break;
    }
}
