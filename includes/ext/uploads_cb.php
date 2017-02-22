<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license       http://jtl-url.de/jtlshoplicense
 */
require_once '../globalinclude.php';
require_once PFAD_ROOT . PFAD_INCLUDES_EXT . 'class.JTL-Shop.UploadDatei.php';

/**
 * output
 *
 * @param $bOk
 */
function retCode($bOk)
{
    die($bOk ? json_encode(array('status' => 'ok')) : json_encode(array('status' => 'error')));
}

// session
if (!isset($_REQUEST['sid'])) {
    retCode(0);
}

$_COOKIE['JTLSHOP'] = $_REQUEST['sid'];
$session            = Session::getInstance();

if (!validateToken()) {
    retCode(0);
}
// upload file
if (!empty($_FILES)) {
    $cUnique = (isset($_REQUEST['uniquename'])) ?
        $_REQUEST['uniquename'] :
        null;
    $cTargetFile = PFAD_UPLOADS . $cUnique;
    $fileData    = (isset($_FILES['Filedata']['tmp_name'])) ?
        $_FILES['Filedata'] :
        $_FILES['file_data'];
    $cTempFile = $fileData['tmp_name'];

    if (isset($fileData['error']) && $fileData['error'] == 0) {
        if (move_uploaded_file($cTempFile, $cTargetFile)) {
            $oFile         = new stdClass();
            $oFile->cName  = $fileData['name'];
            $oFile->nBytes = $fileData['size'];
            $oFile->cKB    = round($fileData['size'] / 1024, 2);

            if (!isset($_SESSION['Uploader'])) {
                $_SESSION['Uploader'] = array();
            }

            $_SESSION['Uploader'][$cUnique] = $oFile;
            if (isset($_REQUEST['uploader'])) {
                die(json_encode($oFile));
            }
            retCode(1);
        }
    }
}
// handle file
if (!empty($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'remove':
            $cUnique   = $_REQUEST['uniquename'];
            $cFilePath = PFAD_UPLOADS . $cUnique;

            unset($_SESSION['Uploader'][$cUnique]);
            if (file_exists($cFilePath)) {
                retCode(@unlink($cFilePath));
            }
            break;

        case 'exists':
            $cFilePath = PFAD_UPLOADS . $_REQUEST['uniquename'];
            retCode(file_exists($cFilePath));
            break;

        case 'preview':
            $oUpload   = new UploadDatei();
            $kKunde    = intval($_SESSION['Kunde']->kKunde);
            $cFilePath = PFAD_ROOT . BILD_UPLOAD_ZUGRIFF_VERWEIGERT;
            $kUpload   = intval(entschluesselXTEA(rawurldecode($_REQUEST['secret'])));

            if ($kUpload > 0 && $kKunde > 0 && $oUpload->loadFromDB($kUpload)) {
                $cTmpFilePath = PFAD_UPLOADS . $oUpload->cPfad;
                if (file_exists($cTmpFilePath)) {
                    $cFilePath = $cTmpFilePath;
                }
            }
            header('Cache-Control: max-age=3600, public');
            header('Content-type: Image');

            readfile($cFilePath);
            exit;

        default:
            break;
    }
}

retCode(0);
