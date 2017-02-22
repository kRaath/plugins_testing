<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

/**
 * Class CheckBox
 */
class CheckBox
{
    /**
     * @var int
     */
    public $kCheckBox;

    /**
     * @var int
     */
    public $kLink;

    /**
     * @var int
     */
    public $kCheckBoxFunktion;

    /**
     * @var string
     */
    public $cName;

    /**
     * @var string
     */
    public $cKundengruppe;

    /**
     * @var string
     */
    public $cAnzeigeOrt;

    /**
     * @var int
     */
    public $nAktiv;

    /**
     * @var int
     */
    public $nPflicht;

    /**
     * @var int
     */
    public $nLogging;

    /**
     * @var int
     */
    public $nSort;

    /**
     * @var string
     */
    public $dErstellt;

    /**
     * @var array
     */
    public $oCheckBoxSprache_arr;

    /**
     * @var stdClass
     */
    public $oCheckBoxFunktion;

    /**
     * @var array
     */
    public $cKundengruppeAssoc_arr;

    /**
     * @var array
     */
    public $kKundengruppe_arr;

    /**
     * @var array
     */
    public $kAnzeigeOrt_arr;

    /**
     * @var string
     */
    public $cID;

    /**
     * @var string
     */
    public $cLink;

    /**
     * @var stdClass
     */
    public $oLink;

    /**
     * @param int  $kCheckBox
     * @param bool $bSprachWerte
     */
    public function __construct($kCheckBox = 0, $bSprachWerte = false)
    {
        $this->loadFromDB($kCheckBox, $bSprachWerte);
    }

    /**
     * @param int  $kCheckBox
     * @param bool $bSprachWerte
     * @return $this
     */
    private function loadFromDB($kCheckBox, $bSprachWerte)
    {
        $kCheckBox = (int)$kCheckBox;
        if ($kCheckBox > 0) {
            $oCheckBox = Shop::DB()->query("SELECT *, DATE_FORMAT(dErstellt, '%d.%m.%Y %H:%i:%s') AS dErstellt_DE FROM tcheckbox WHERE kCheckBox = " . $kCheckBox, 1);
            if (isset($oCheckBox->kCheckBox) && $oCheckBox->kCheckBox > 0) {
                $cMember_arr = array_keys(get_object_vars($oCheckBox));
                if (is_array($cMember_arr) && count($cMember_arr) > 0) {
                    foreach ($cMember_arr as $cMember) {
                        $this->$cMember = $oCheckBox->$cMember;
                    }
                }
                // Global Identifier
                $this->cID               = 'CheckBox_' . $this->kCheckBox;
                $this->kKundengruppe_arr = gibKeyArrayFuerKeyString($oCheckBox->cKundengruppe, ';');
                $this->kAnzeigeOrt_arr   = gibKeyArrayFuerKeyString($oCheckBox->cAnzeigeOrt, ';');
                // CheckBoxFunktion
                // Falls mal kCheckBoxFunktion gesetzt war aber diese Funktion nicht mehr existiert (deinstallation vom Plugin)
                // wird kCheckBoxFunktion auf 0 gesetzt
                if ($this->kCheckBoxFunktion > 0) {
                    $oCheckBoxFunktion = Shop::DB()->query("SELECT * FROM tcheckboxfunktion WHERE kCheckBoxFunktion = " . (int)$this->kCheckBoxFunktion, 1);
                    if (isset($oCheckBoxFunktion->kCheckBoxFunktion) && $oCheckBoxFunktion->kCheckBoxFunktion > 0) {
                        $this->oCheckBoxFunktion = $oCheckBoxFunktion;
                    } else {
                        $this->kCheckBoxFunktion = 0;
                        Shop::DB()->query("UPDATE tcheckbox SET kCheckBoxFunktion = 0 WHERE kCheckBox = " . (int)$this->kCheckBox, 3);
                    }
                }
                // Mapping Kundengruppe
                if (is_array($this->kKundengruppe_arr) && count($this->kKundengruppe_arr) > 0) {
                    $this->cKundengruppeAssoc_arr = array();
                    foreach ($this->kKundengruppe_arr as $kKundengruppe) {
                        $oKundengruppe = Shop::DB()->query("SELECT cName FROM tkundengruppe WHERE kKundengruppe = " . (int)$kKundengruppe, 1);
                        if (isset($oKundengruppe->cName) && strlen($oKundengruppe->cName) > 0) {
                            $this->cKundengruppeAssoc_arr[$kKundengruppe] = $oKundengruppe->cName;
                        }
                    }
                }
                // Mapping Link
                if ($this->kLink > 0) {
                    $oLink = Shop::DB()->query("SELECT kLink, cName, nLinkart FROM tlink WHERE kLink = " . (int)$this->kLink, 1);
                    if (isset($oLink->kLink) && $oLink->kLink > 0) {
                        $this->oLink = $oLink;
                    }
                } else {
                    $this->cLink = 'kein interner Link';
                }
                // Hole Sprachen
                if ($bSprachWerte) {
                    $oCheckBoxSpracheTMP_arr = Shop::DB()->query("SELECT * FROM tcheckboxsprache WHERE kCheckBox = " . (int)$this->kCheckBox, 2);
                    if (count($oCheckBoxSpracheTMP_arr) > 0) {
                        foreach ($oCheckBoxSpracheTMP_arr as $oCheckBoxSpracheTMP) {
                            $this->oCheckBoxSprache_arr[$oCheckBoxSpracheTMP->kSprache] = $oCheckBoxSpracheTMP;
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param int  $nAnzeigeOrt
     * @param int  $kKundengruppe
     * @param bool $bAktiv
     * @param bool $bSprache
     * @param bool $bSpecial
     * @param bool $bLogging
     * @return array
     */
    public function getCheckBoxFrontend($nAnzeigeOrt, $kKundengruppe = 0, $bAktiv = false, $bSprache = false, $bSpecial = false, $bLogging = false)
    {
        if (!$kKundengruppe) {
            if (isset($_SESSION['Kundengruppe']->kKundengruppe)) {
                $kKundengruppe = $_SESSION['Kundengruppe']->kKundengruppe;
            } else {
                $kKundengruppe = Kundengruppe::getDefaultGroupID();
            }
        }
        $kKundengruppe = (int)$kKundengruppe;
        $oCheckBox_arr = array();
        $cSQL          = '';
        if ($bAktiv) {
            $cSQL .= ' AND nAktiv = 1';
        }
        if ($bSpecial) {
            $cSQL .= ' AND kCheckBoxFunktion > 0';
        }
        if ($bLogging) {
            $cSQL .= ' AND nLogging = 1';
        }
        $oCheckBoxTMP_arr = Shop::DB()->query(
            "SELECT kCheckBox FROM tcheckbox
                WHERE cAnzeigeOrt LIKE '%;" . intval($nAnzeigeOrt) . ";%'
                    AND cKundengruppe LIKE  '%;" . $kKundengruppe . ";%'
                    " . $cSQL . "
                ORDER BY nSort", 2
        );

        if (count($oCheckBoxTMP_arr) > 0) {
            foreach ($oCheckBoxTMP_arr as $oCheckBoxTMP) {
                $oCheckBox_arr[] = new self($oCheckBoxTMP->kCheckBox, $bSprache);
            }
        }
        executeHook(
            HOOK_CHECKBOX_CLASS_GETCHECKBOXFRONTEND, array(
                'oCheckBox_arr' => &$oCheckBox_arr,
                'nAnzeigeOrt'   => $nAnzeigeOrt,
                'kKundengruppe' => $kKundengruppe,
                'bAktiv'        => $bAktiv,
                'bSprache'      => $bSprache,
                'bSpecial'      => $bSpecial,
                'bLogging'      => $bLogging
            )
        );

        return $oCheckBox_arr;
    }

    /**
     * @param int   $nAnzeigeOrt
     * @param int   $kKundengruppe
     * @param array $cPost_arr
     * @param bool  $bAktiv
     * @return array
     */
    public function validateCheckBox($nAnzeigeOrt, $kKundengruppe = 0, $cPost_arr, $bAktiv = false)
    {
        $oCheckBox_arr = $this->getCheckBoxFrontend($nAnzeigeOrt, $kKundengruppe, $bAktiv);
        $cPlausi_arr   = array();
        if (count($oCheckBox_arr) > 0) {
            foreach ($oCheckBox_arr as $oCheckBox) {
                if (intval($oCheckBox->nPflicht) === 1) {
                    if (!isset($cPost_arr[$oCheckBox->cID])) {
                        $cPlausi_arr[$oCheckBox->cID] = 1;
                    }
                }
            }
        }

        return $cPlausi_arr;
    }

    /**
     * @param int   $nAnzeigeOrt
     * @param int   $kKundengruppe
     * @param bool  $bAktiv
     * @param array $cPost_arr
     * @param array $xParamas_arr
     * @return $this
     */
    public function triggerSpecialFunction($nAnzeigeOrt, $kKundengruppe = 0, $bAktiv = false, $cPost_arr, $xParamas_arr = array())
    {
        $oCheckBox_arr = $this->getCheckBoxFrontend($nAnzeigeOrt, $kKundengruppe, $bAktiv, true, true);
        if (count($oCheckBox_arr) > 0) {
            foreach ($oCheckBox_arr as $oCheckBox) {
                if (isset($cPost_arr[$oCheckBox->cID])) {
                    if ($oCheckBox->oCheckBoxFunktion->kPlugin > 0) {
                        $xParamas_arr['oCheckBox'] = $oCheckBox;
                        executeHook(HOOK_CHECKBOX_CLASS_TRIGGERSPECIALFUNCTION, $xParamas_arr);
                    } else {
                        // Festdefinierte Shopfunktionen
                        switch ($oCheckBox->oCheckBoxFunktion->cID) {
                            case 'jtl_newsletter': // Newsletteranmeldung
                                $xParamas_arr['oKunde'] = kopiereMembers($xParamas_arr['oKunde']);
                                $this->sfCheckBoxNewsletter($xParamas_arr['oKunde']);
                                break;

                            case 'jtl_adminmail': // CheckBoxMail
                                $xParamas_arr['oKunde'] = kopiereMembers($xParamas_arr['oKunde']);
                                $this->sfCheckBoxMailToAdmin($xParamas_arr['oKunde'], $oCheckBox, $nAnzeigeOrt);
                                break;

                            default:
                                break;
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param int   $nAnzeigeOrt
     * @param int   $kKundengruppe
     * @param array $cPost_arr
     * @param bool  $bAktiv
     * @return $this
     */
    public function checkLogging($nAnzeigeOrt, $kKundengruppe = 0, $cPost_arr, $bAktiv = false)
    {
        $oCheckBox_arr = $this->getCheckBoxFrontend($nAnzeigeOrt, $kKundengruppe, $bAktiv, false, false, true);

        if ($oCheckBox_arr !== false && count($oCheckBox_arr) > 0) {
            foreach ($oCheckBox_arr as $oCheckBox) {
                //@todo: casting to bool does not seem to be a good idea.
                //$cPost_arr looks like this: array ( [CheckBox_31] => Y, [CheckBox_24] => Y, [abschluss] => 1)
                $checked                       = (isset($cPost_arr[$oCheckBox->cID])) ? (bool)$cPost_arr[$oCheckBox->cID] : false;
                $checked                       = ($checked === true) ? 1 : 0;
                $oCheckBoxLogging              = new stdClass();
                $oCheckBoxLogging->kCheckBox   = $oCheckBox->kCheckBox;
                $oCheckBoxLogging->kBesucher   = (int) $_SESSION['oBesucher']->kBesucher;
                $oCheckBoxLogging->kBestellung = (isset($_SESSION['kBestellung'])) ? (int)$_SESSION['kBestellung'] : 0;
                $oCheckBoxLogging->bChecked    = $checked;
                $oCheckBoxLogging->dErstellt   = 'now()';

                Shop::DB()->insert('tcheckboxlogging', $oCheckBoxLogging);
            }
        }

        return $this;
    }

    /**
     * @param string $cLimitSQL
     * @param bool   $bAktiv
     * @param bool   $bSprache
     * @return array
     */
    public function getAllCheckBox($cLimitSQL = '', $bAktiv = false, $bSprache = false)
    {
        $oCheckBox_arr = array();
        $cSQL          = '';
        if ($bAktiv) {
            $cSQL = ' WHERE nAktiv = 1';
        }
        $oCheckBoxTMP_arr = Shop::DB()->query("SELECT kCheckBox FROM tcheckbox" . $cSQL . " ORDER BY nSort " . $cLimitSQL, 2);
        if (count($oCheckBoxTMP_arr) > 0) {
            foreach ($oCheckBoxTMP_arr as $i => $oCheckBoxTMP) {
                $oCheckBox_arr[$i] = new self($oCheckBoxTMP->kCheckBox, $bSprache);
            }
        }

        return $oCheckBox_arr;
    }

    /**
     * @param bool $bAktiv
     * @return mixed
     */
    public function getAllCheckBoxCount($bAktiv = false)
    {
        $cSQL = '';
        if ($bAktiv) {
            $cSQL = ' WHERE nAktiv = 1';
        }
        $oCheckBoxCount = Shop::DB()->query("SELECT count(*) AS nAnzahl FROM tcheckbox" . $cSQL, 1);

        return (isset($oCheckBoxCount->nAnzahl)) ? $oCheckBoxCount->nAnzahl : 0;
    }

    /**
     * @param array $kCheckBox_arr
     * @return bool
     */
    public function aktivateCheckBox($kCheckBox_arr)
    {
        if (is_array($kCheckBox_arr) && count($kCheckBox_arr) > 0) {
            foreach ($kCheckBox_arr as $kCheckBox) {
                Shop::DB()->query("UPDATE tcheckbox SET nAktiv = 1 WHERE kCheckBox = " . (int)$kCheckBox, 3);
            }

            return true;
        }

        return false;
    }

    /**
     * @param array $kCheckBox_arr
     * @return bool
     */
    public function deaktivateCheckBox($kCheckBox_arr)
    {
        if (is_array($kCheckBox_arr) && count($kCheckBox_arr) > 0) {
            foreach ($kCheckBox_arr as $kCheckBox) {
                Shop::DB()->query("UPDATE tcheckbox SET nAktiv = 0 WHERE kCheckBox = " . (int)$kCheckBox, 3);
            }

            return true;
        }

        return false;
    }

    /**
     * @param array $kCheckBox_arr
     * @return bool
     */
    public function deleteCheckBox($kCheckBox_arr)
    {
        if (is_array($kCheckBox_arr) && count($kCheckBox_arr) > 0) {
            foreach ($kCheckBox_arr as $kCheckBox) {
                Shop::DB()->query(
                    "DELETE tcheckbox, tcheckboxsprache
                        FROM tcheckbox
                        LEFT JOIN tcheckboxsprache ON tcheckboxsprache.kCheckBox = tcheckbox.kCheckBox
                        WHERE tcheckbox.kCheckBox = " . (int)$kCheckBox, 3
                );
            }

            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getCheckBoxFunctions()
    {
        return Shop::DB()->query("SELECT * FROM tcheckboxfunktion ORDER BY cName", 2);
    }

    /**
     * @param array $cTextAssoc_arr
     * @param array $cBeschreibungAssoc_arr
     * @return $this
     */
    public function insertDB($cTextAssoc_arr, $cBeschreibungAssoc_arr)
    {
        if (is_array($cTextAssoc_arr) && count($cTextAssoc_arr) > 0) {
            $oCheckBox = kopiereMembers($this);
            unset($oCheckBox->cID);
            unset($oCheckBox->kKundengruppe_arr);
            unset($oCheckBox->kAnzeigeOrt_arr);
            unset($oCheckBox->oCheckBoxFunktion);
            unset($oCheckBox->dErstellt_DE);
            unset($oCheckBox->oLink);
            unset($oCheckBox->cKundengruppeAssoc_arr);
            unset($oCheckBox->oCheckBoxSprache_arr);
            unset($oCheckBox->cLink);
            unset($oCheckBox->kCheckBox);

            $kCheckBox       = Shop::DB()->insert('tcheckbox', $oCheckBox);
            $this->kCheckBox = !empty($oCheckBox->kCheckBox) ? $oCheckBox->kCheckBox : $kCheckBox;
            $this->insertDBSprache($cTextAssoc_arr, $cBeschreibungAssoc_arr);
        }

        return $this;
    }

    /**
     * @param array $cTextAssoc_arr
     * @param array $cBeschreibungAssoc_arr
     * @return $this
     */
    private function insertDBSprache($cTextAssoc_arr, $cBeschreibungAssoc_arr)
    {
        $this->oCheckBoxSprache_arr = array();

        foreach ($cTextAssoc_arr as $cISO => $cTextAssoc) {
            if (strlen($cTextAssoc) > 0) {
                $this->oCheckBoxSprache_arr[$cISO]                = new stdClass();
                $this->oCheckBoxSprache_arr[$cISO]->kCheckBox     = $this->kCheckBox;
                $this->oCheckBoxSprache_arr[$cISO]->kSprache      = $this->getSprachKeyByISO($cISO);
                $this->oCheckBoxSprache_arr[$cISO]->cText         = $cTextAssoc;
                $this->oCheckBoxSprache_arr[$cISO]->cBeschreibung = '';
                if (isset($cBeschreibungAssoc_arr[$cISO]) && strlen($cBeschreibungAssoc_arr[$cISO]) > 0) {
                    $this->oCheckBoxSprache_arr[$cISO]->cBeschreibung = $cBeschreibungAssoc_arr[$cISO];
                }
                $this->oCheckBoxSprache_arr[$cISO]->kCheckBoxSprache = Shop::DB()->insert('tcheckboxsprache', $this->oCheckBoxSprache_arr[$cISO]);
            }
        }

        return $this;
    }

    /**
     * @param string $cISO
     * @return int
     */
    private function getSprachKeyByISO($cISO)
    {
        if (strlen($cISO) > 0) {
            $oSprache = Shop::DB()->select('tsprache', 'cISO', StringHandler::filterXSS($cISO));
            if (isset($oSprache->kSprache) && intval($oSprache->kSprache) > 0) {
                return $oSprache->kSprache;
            }
        }

        return 0;
    }

    /**
     * @param object $knd
     * @return bool
     */
    private function sfCheckBoxNewsletter($knd)
    {
        require_once PFAD_ROOT . PFAD_INCLUDES . 'newsletter_inc.php';

        if (!is_object($knd)) {
            return false;
        }
        $oKundeTMP            = new stdClass();
        $oKundeTMP->cAnrede   = $knd->cAnrede;
        $oKundeTMP->cVorname  = $knd->cVorname;
        $oKundeTMP->cNachname = $knd->cNachname;
        $oKundeTMP->cEmail    = $knd->cMail;

        fuegeNewsletterEmpfaengerEin($oKundeTMP, false);

        return true;
    }

    /**
     * @param object $oKunde
     * @param object $oCheckBox
     * @param int    $nAnzeigeOrt
     * @return bool
     */
    public function sfCheckBoxMailToAdmin($oKunde, $oCheckBox, $nAnzeigeOrt)
    {
        if (!isset($oKunde->cVorname) || !isset($oKunde->cNachname) || !isset($oKunde->cMail)) {
            return false;
        }
        $Einstellungen = Shop::getSettings(array(CONF_EMAILS));
        if (isset($Einstellungen['emails']['email_master_absender']) && strlen($Einstellungen['emails']['email_master_absender']) > 0) {
            require_once PFAD_ROOT . PFAD_INCLUDES . 'mailTools.php';
            $oObj                = new stdClass();
            $oObj->oCheckBox     = $oCheckBox;
            $oObj->oKunde        = $oKunde;
            $oObj->tkunde        = $oKunde;
            $oObj->cAnzeigeOrt   = $this->mappeCheckBoxOrte($nAnzeigeOrt);
            $oObj->mail->toEmail = $Einstellungen['emails']['email_master_absender'];

            sendeMail(MAILTEMPLATE_CHECKBOX_SHOPBETREIBER, $oObj);
        }

        return true;
    }

    /**
     * @param int $nAnzeigeOrt
     * @return string
     */
    public function mappeCheckBoxOrte($nAnzeigeOrt)
    {
        $cAnzeigeOrt_arr = self::gibCheckBoxAnzeigeOrte();

        return (isset($cAnzeigeOrt_arr[$nAnzeigeOrt])) ?
            $cAnzeigeOrt_arr[$nAnzeigeOrt] :
            '';
    }

    /**
     * @return array
     */
    public static function gibCheckBoxAnzeigeOrte()
    {
        return array(
            CHECKBOX_ORT_REGISTRIERUNG        => 'Registrierung',
            CHECKBOX_ORT_BESTELLABSCHLUSS     => 'Bestellabschluss',
            CHECKBOX_ORT_NEWSLETTERANMELDUNG  => 'Newsletteranmeldung',
            CHECKBOX_ORT_KUNDENDATENEDITIEREN => 'Editieren von Kundendaten',
            CHECKBOX_ORT_KONTAKT              => 'Kontaktformular');
    }
}
