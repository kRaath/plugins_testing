<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */
$oNice = Nice::getInstance();
if ($oNice->checkErweiterung(SHOP_ERWEITERUNG_UPLOADS)) {
    /**
     * Class Upload
     */
    class Upload
    {
        /**
         * @param int $kArtikel
         * @return bool
         */
        public static function gibArtikelUploads($kArtikel)
        {
            $oUploadSchema = new UploadSchema();
            $oUploads_arr  = $oUploadSchema->fetchAll($kArtikel, UPLOAD_TYP_WARENKORBPOS);
            if (is_array($oUploads_arr) && count($oUploads_arr) > 0) {
                foreach ($oUploads_arr as &$oUpload) {
                    $oUpload->cUnique       = self::uniqueDateiname($oUpload);
                    $oUpload->cDateiTyp_arr = self::formatTypen($oUpload->cDateiTyp);
                    $oUpload->cDateiListe   = implode(';', $oUpload->cDateiTyp_arr);
                    $oUpload->bVorhanden    = is_file(PFAD_UPLOADS . $oUpload->cUnique);
                    $oUploadDatei           = (isset($_SESSION['Uploader'][$oUpload->cUnique])) ? $_SESSION['Uploader'][$oUpload->cUnique] : null;
                    if (is_object($oUploadDatei)) {
                        $oUpload->cDateiname    = $oUploadDatei->cName;
                        $oUpload->cDateigroesse = self::formatGroesse($oUploadDatei->nBytes);
                    }
                }

                return $oUploads_arr;
            }

            return false;
        }

        /**
         * Deletes all uploaded files for an article with ID (kArtikel)
         *
         * @param  int    $kArtikel
         * @return void
         */
        public static function deleteArtikelUploads($kArtikel)
        {
            $oUploads_arr = self::gibArtikelUploads(intval($kArtikel));

            if (is_array($oUploads_arr) && count($oUploads_arr) > 0) {
                foreach ($oUploads_arr as &$oUpload) {
                    if ($oUpload->bVorhanden) {
                        unlink(PFAD_UPLOADS . $oUpload->cUnique);
                    }
                }
            }
        }

        /**
         * @param Warenkorb $oWarenkorb
         * @return array|null
         */
        public static function gibWarenkorbUploads($oWarenkorb)
        {
            $oUploads_arr = null;
            if (count($oWarenkorb->PositionenArr) > 0) {
                foreach ($oWarenkorb->PositionenArr as &$oPosition) {
                    if ($oPosition->nPosTyp == C_WARENKORBPOS_TYP_ARTIKEL && isset($oPosition->Artikel->kArtikel)) {
                        $oUpload              = new stdClass();
                        $oUpload->cName       = $oPosition->Artikel->cName;
                        $oUpload->oUpload_arr = self::gibArtikelUploads($oPosition->Artikel->kArtikel);
                        if ($oUpload->oUpload_arr) {
                            $oUploads_arr[] = $oUpload;
                        }
                    }
                }
            }

            return $oUploads_arr;
        }

        /**
         * @param int $kBestellung
         * @return mixed
         */
        public static function gibBestellungUploads($kBestellung)
        {
            $oUploadDatei     = new UploadDatei();
            $oUploadDatei_arr = $oUploadDatei->fetchAll($kBestellung, UPLOAD_TYP_BESTELLUNG);

            return $oUploadDatei_arr;
        }

        /**
         * @param Warenkorb $oWarenkorb
         * @return bool
         */
        public static function pruefeWarenkorbUploads($oWarenkorb)
        {
            $oUploadSchema_arr = self::gibWarenkorbUploads($oWarenkorb);
            if (is_array($oUploadSchema_arr)) {
                foreach ($oUploadSchema_arr as &$oUploadSchema) {
                    foreach ($oUploadSchema->oUpload_arr as &$oUpload) {
                        if ($oUpload->nPflicht && !$oUpload->bVorhanden) {
                            return false;
                        }
                    }
                }
            }

            return true;
        }

        /**
         * @param int $nErrorCode
         */
        public static function redirectWarenkorb($nErrorCode)
        {
            header('Location: warenkorb.php?fillOut=' . $nErrorCode, true, 303);
        }

        /**
         * @param Warenkorb $oWarenkorb
         * @param int       $kBestellung
         */
        public static function speicherUploadDateien($oWarenkorb, $kBestellung)
        {
            $kBestellung       = intval($kBestellung);
            $oUploadSchema_arr = self::gibWarenkorbUploads($oWarenkorb);
            if (is_array($oUploadSchema_arr)) {
                foreach ($oUploadSchema_arr as &$oUploadSchema) {
                    foreach ($oUploadSchema->oUpload_arr as &$oUploadDatei) {
                        $oUploadInfo = (isset($_SESSION['Uploader'][$oUploadDatei->cUnique])) ? $_SESSION['Uploader'][$oUploadDatei->cUnique] : null;
                        if (is_object($oUploadInfo)) {
                            self::setzeUploadQueue($kBestellung, $oUploadDatei->kCustomID);
                            self::setzeUploadDatei($kBestellung, UPLOAD_TYP_BESTELLUNG, $oUploadInfo->cName, $oUploadDatei->cUnique, $oUploadInfo->nBytes);
                        }
                        unset($_SESSION['Uploader'][$oUploadDatei->cUnique]);
                    }
                }
                session_regenerate_id();
            }
            unset($_SESSION['Uploader']);
        }

        /**
         * @param int    $kCustomID
         * @param int    $nTyp
         * @param string $cName
         * @param string $cPfad
         * @param int    $nBytes
         */
        public static function setzeUploadDatei($kCustomID, $nTyp, $cName, $cPfad, $nBytes)
        {
            $oUploadDatei            = new stdClass();
            $oUploadDatei->kCustomID = $kCustomID;
            $oUploadDatei->nTyp      = $nTyp;
            $oUploadDatei->cName     = $cName;
            $oUploadDatei->cPfad     = $cPfad;
            $oUploadDatei->nBytes    = $nBytes;
            $oUploadDatei->dErstellt = 'now()';

            Shop::DB()->insert('tuploaddatei', $oUploadDatei);
        }

        /**
         * @param int $kBestellung
         * @param int $kCustomID
         */
        public static function setzeUploadQueue($kBestellung, $kCustomID)
        {
            $oUploadQueue              = new stdClass();
            $oUploadQueue->kBestellung = $kBestellung;
            $oUploadQueue->kArtikel    = $kCustomID;

            Shop::DB()->insert('tuploadqueue', $oUploadQueue);
        }

        /**
         * @return int|mixed
         */
        public static function uploadMax()
        {
            $nMaxUpload   = intval(ini_get('upload_max_filesize'));
            $nMaxPost     = intval(ini_get('post_max_size'));
            $nMemoryLimit = intval(ini_get('memory_limit'));
            $nUploadMax   = min($nMaxUpload, $nMaxPost, $nMemoryLimit);
            $nUploadMax *= (1024 * 1024);

            return $nUploadMax;
        }

        /**
         * @param int $nFileSize
         * @return string
         */
        public static function formatGroesse($nFileSize)
        {
            if (is_numeric($nFileSize)) {
                $nStep       = 0;
                $nDecr       = 1024;
                $cPrefix_arr = array('Byte', 'KB', 'MB', 'GB', 'TB', 'PB');

                while (($nFileSize / $nDecr) > 0.9) {
                    $nFileSize = $nFileSize / $nDecr;
                    $nStep++;
                }

                return round($nFileSize, 2) . ' ' . $cPrefix_arr[$nStep];
            }

            return '---';
        }

        /**
         * @param object $oUpload
         * @return string
         */
        public static function uniqueDateiname($oUpload)
        {
            return md5($oUpload->kUploadSchema . $oUpload->kCustomID . $oUpload->nTyp . session_id());
        }

        /**
         * @param string $cDateiTyp
         * @return array
         */
        public static function formatTypen($cDateiTyp)
        {
            $cDateiTyp_arr = explode(',', $cDateiTyp);
            foreach ($cDateiTyp_arr as &$cTyp) {
                $cTyp = '*' . $cTyp;
            }

            return $cDateiTyp_arr;
        }

        /**
         * @param string $cName
         * @return bool
         */
        public static function vorschauTyp($cName)
        {
            $cPath_arr = pathinfo($cName);
            if (is_array($cPath_arr)) {
                return in_array(
                    $cPath_arr['extension'],
                    array('gif', 'png', 'jpg', 'jpeg', 'bmp', 'jpe')
                );
            }

            return false;
        }
    }
}
