<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

ob_start();
require_once dirname(__FILE__) . '/syncinclude.php';

$return = 3;
if (auth()) {
    checkFile();
    $return  = 2;
    $archive = new PclZip($_FILES['data']['tmp_name']);
    if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
        Jtllog::writeLog('Entpacke: ' . $_FILES['data']['tmp_name'], JTLLOG_LEVEL_DEBUG, false, 'img_upload_xml');
    }
    if ($list = $archive->listContent()) {
        $count = count($list);
        if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
            Jtllog::writeLog('Anzahl Dateien im Zip: ' . $count, JTLLOG_LEVEL_DEBUG, false, 'img_upload_xml');
        }

        $newTmpDir = PFAD_SYNC_TMP . uniqid("images_") . '/';
        mkdir($newTmpDir, 0777, true);

        if ($archive->extract(PCLZIP_OPT_PATH, $newTmpDir)) {
            $return = 0;
            $found = false;
            foreach ($list as $elem) {
                if ($elem['filename'] === 'images.xml') {
                    $found = true;
                }
                elseif (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                    Jtllog::writeLog('Received image: ' . $newTmpDir . $elem['filename'] . ' size: ' . filesize($newTmpDir . $elem['filename']), JTLLOG_LEVEL_DEBUG, false, 'img_upload_xml');
                }
            }
            
            if ($found) {
                $xml = simplexml_load_file($newTmpDir . 'images.xml');
                images_xml($newTmpDir, $xml);

                if ($count <= 1) {
                    if (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                        Jtllog::writeLog('Zip-File contains zero images', JTLLOG_LEVEL_DEBUG, false, 'img_upload_xml');
                    }
                }
            }
            elseif (Jtllog::doLog(JTLLOG_LEVEL_DEBUG)) {
                Jtllog::writeLog('Missing images.xml', JTLLOG_LEVEL_DEBUG, false, 'img_upload_xml');
            }
            removeTemporaryFiles($newTmpDir);
        } elseif (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
            Jtllog::writeLog('Error: ' . $archive->errorInfo(true), JTLLOG_LEVEL_ERROR, false, 'img_upload_xml');
        }
    } elseif (Jtllog::doLog(JTLLOG_LEVEL_ERROR)) {
        Jtllog::writeLog('Error: ' . $archive->errorInfo(true), JTLLOG_LEVEL_ERROR, false, 'img_upload_xml');
    }
}
echo $return;

/*
$xmlString = <<<XML
<?xml version='1.0'?>
<bilder>
   <bild kBild="136" cHash="800_800_1a0b9fc820b03727b996be74c4867cba_62ad684d97bba865b7105a486120b8fb718feb1fe62a9a2ea0a46deb754424de2a26096788151100f3e938c3e4d7eea023e2488e774a0fa66f2e49f323492f62.jpg"/>
   <bild kBild="144" cHash="800_800_1a0b9fc820b03727b996be74c4867cba_62ad684d97bba865b7105a486120b8fb718feb1fe62a9a2ea0a46deb754424de2a26096788151100f3e938c3e4d7eea023e2488e774a0fa66f2e49f323492f62.jpg"/>
</bilder>
XML;

$xml = simplexml_load_string($xmlString);
images_xml($xml);
*/

/**
 * @param string           $tmpDir
 * @param SimpleXMLElement $xml
 */
function images_xml($tmpDir, SimpleXMLElement $xml)
{
    $items = get_array($xml);
    foreach ($items as $item) {
        $tmpfile = $tmpDir . $item->kBild;
        if (file_exists($tmpfile)) {
            if (copy($tmpfile, PFAD_ROOT . PFAD_MEDIA_IMAGE_STORAGE . $item->cPfad)) {
                DBUpdateInsert('tbild', array($item), 'kBild');
                Shop::DB()->query("UPDATE tartikelpict SET cPfad = '{$item->cPfad}' WHERE kBild = " . (int)$item->kBild, 4);
            }
            else {
                Jtllog::writeLog(sprintf('Copy "%s" to "%s"', $tmpfile, PFAD_ROOT . PFAD_MEDIA_IMAGE_STORAGE . $item->cPfad), JTLLOG_LEVEL_ERROR, false, 'img_upload_xml');
            }
        }
    }
}

/**
 * @param SimpleXMLElement $xml
 * @return array
 */
function get_array(SimpleXMLElement $xml)
{
    $items = array();
    foreach ($xml->children() as $child) {
        $items[] = (object)array(
            'kBild' => (int)$child->attributes()->kBild,
            'cPfad' => (string)$child->attributes()->cHash
        );
    }

    return $items;
}