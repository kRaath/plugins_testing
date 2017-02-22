<?php

/**
 * Class Kupon
 *
 * @access public
 */
class Kupon
{
    /**
     * @access public
     * @var int
     */
    public $kKupon;

    /**
     * @access public
     * @var int
     */
    public $kKundengruppe;

    /**
     * @access public
     * @var int
     */
    public $kSteuerklasse;

    /**
     * @access public
     * @var string
     */
    public $cName;

    /**
     * @access public
     * @var float
     */
    public $fWert;

    /**
     * @access public
     * @var string
     */
    public $cWertTyp;

    /**
     * @access public
     * @var string
     */
    public $dGueltigAb;

    /**
     * @access public
     * @var string
     */
    public $dGueltigBis;

    /**
     * @access public
     * @var float
     */
    public $fMindestbestellwert;

    /**
     * @access public
     * @var string
     */
    public $cCode;

    /**
     * @access public
     * @var int
     */
    public $nVerwendungen;

    /**
     * @access public
     * @var int
     */
    public $nVerwendungenBisher;

    /**
     * @access public
     * @var int
     */
    public $nVerwendungenProKunde;

    /**
     * @access public
     * @var string
     */
    public $cArtikel;

    /**
     * @access public
     * @var string
     */
    public $cKategorien;

    /**
     * @access public
     * @var string
     */
    public $cKunden;

    /**
     * @access public
     * @var string
     */
    public $cKuponTyp;

    /**
     * @access public
     * @var string
     */
    public $cLieferlaender;

    /**
     * @access public
     * @var string
     */
    public $cZusatzgebuehren;

    /**
     * @access public
     * @var string
     */
    public $cAktiv;

    /**
     * @access public
     * @var string
     */
    public $dErstellt;

    /**
     * @access public
     * @var int
     */
    public $nGanzenWKRabattieren;

    /**
     * Constructor
     *
     * @param int $kKupon - primarykey
     * @access public
     */
    public function __construct($kKupon = 0)
    {
        if (intval($kKupon) > 0) {
            $this->loadFromDB($kKupon);
        }
    }

    /**
     * Loads database member into class member
     *
     * @param int $kKupon
     * @return $this
     * @access private
     */
    private function loadFromDB($kKupon = 0)
    {
        $oObj = Shop::DB()->query(
            "SELECT *
              FROM tkupon
              WHERE kKupon = " . intval($kKupon), 1
        );

        if ($oObj->kKupon > 0) {
            $cMember_arr = array_keys(get_object_vars($oObj));
            foreach ($cMember_arr as $cMember) {
                $this->$cMember = $oObj->$cMember;
            }
        }

        return $this;
    }

    /**
     * Store the class in the database
     *
     * @param bool $bPrim - Controls the return of the method
     * @return bool|int
     * @access public
     */
    public function save($bPrim = true)
    {
        $oObj        = new stdClass();
        $cMember_arr = array_keys(get_object_vars($this));
        if (is_array($cMember_arr) && count($cMember_arr) > 0) {
            foreach ($cMember_arr as $cMember) {
                $oObj->$cMember = $this->$cMember;
            }
        }

        unset($oObj->kKupon);

        $kPrim = Shop::DB()->insert('tkupon', $oObj);

        if ($kPrim > 0) {
            return $bPrim ? $kPrim : true;
        }

        return false;
    }

    /**
     * Update the class in the database
     *
     * @return int
     * @access public
     */
    public function update()
    {
        $_upd                        = new stdClass();
        $_upd->kKundengruppe         = $this->kKundengruppe;
        $_upd->kSteuerklasse         = $this->kSteuerklasse;
        $_upd->cName                 = $this->cName;
        $_upd->fWert                 = $this->fWert;
        $_upd->cWertTyp              = $this->cWertTyp;
        $_upd->dGueltigAb            = $this->dGueltigAb;
        $_upd->dGueltigBis           = $this->dGueltigBis;
        $_upd->fMindestbestellwert   = $this->fMindestbestellwert;
        $_upd->cCode                 = $this->cCode;
        $_upd->nVerwendungen         = $this->nVerwendungen;
        $_upd->nVerwendungenBisher   = $this->nVerwendungenBisher;
        $_upd->nVerwendungenProKunde = $this->nVerwendungenProKunde;
        $_upd->cArtikel              = $this->cArtikel;
        $_upd->cKategorien           = $this->cKategorien;
        $_upd->cKunden               = $this->cKunden;
        $_upd->cKuponTyp             = $this->cKuponTyp;
        $_upd->cLieferlaender        = $this->cLieferlaender;
        $_upd->cZusatzgebuehren      = $this->cZusatzgebuehren;
        $_upd->cAktiv                = $this->cAktiv;
        $_upd->dErstellt             = $this->dErstellt;
        $_upd->nGanzenWKRabattieren  = $this->nGanzenWKRabattieren;

        return Shop::DB()->update('tkupon', 'kKupon', (int)$this->kKupon, $_upd);
    }

    /**
     * Delete the class in the database
     *
     * @return int
     * @access public
     */
    public function delete()
    {
        return Shop::DB()->delete('tkupon', 'kKupon', (int)$this->kKupon);
    }

    /**
     * Sets the kKupon
     *
     * @access public
     * @param int $kKupon
     * @return $this
     */
    public function setKupon($kKupon)
    {
        $this->kKupon = (int)$kKupon;

        return $this;
    }

    /**
     * Sets the kKundengruppe
     *
     * @access public
     * @param int $kKundengruppe
     * @return $this
     */
    public function setKundengruppe($kKundengruppe)
    {
        $this->kKundengruppe = (int)$kKundengruppe;

        return $this;
    }

    /**
     * Sets the kSteuerklasse
     *
     * @access public
     * @param int $kSteuerklasse
     * @return $this
     */
    public function setSteuerklasse($kSteuerklasse)
    {
        $this->kSteuerklasse = (int)$kSteuerklasse;

        return $this;
    }

    /**
     * Sets the cName
     *
     * @access public
     * @param string $cName
     */
    public function setName($cName)
    {
        $this->cName = Shop::DB()->escape($cName);
    }

    /**
     * Sets the fWert
     *
     * @access public
     * @param float $fWert
     */
    public function setWert($fWert)
    {
        $this->fWert = floatval($fWert);
    }

    /**
     * Sets the cWertTyp
     *
     * @access public
     * @param string $cWertTyp
     * @return $this
     */
    public function setWertTyp($cWertTyp)
    {
        $this->cWertTyp = Shop::DB()->escape($cWertTyp);

        return $this;
    }

    /**
     * Sets the dGueltigAb
     *
     * @access public
     * @param string $dGueltigAb
     * @return $this
     */
    public function setGueltigAb($dGueltigAb)
    {
        $this->dGueltigAb = Shop::DB()->escape($dGueltigAb);

        return $this;
    }

    /**
     * Sets the dGueltigBis
     *
     * @access public
     * @param string $dGueltigBis
     * @return $this
     */
    public function setGueltigBis($dGueltigBis)
    {
        $this->dGueltigBis = Shop::DB()->escape($dGueltigBis);

        return $this;
    }

    /**
     * Sets the fMindestbestellwert
     *
     * @access public
     * @param float $fMindestbestellwert
     * @return $this
     */
    public function setMindestbestellwert($fMindestbestellwert)
    {
        $this->fMindestbestellwert = floatval($fMindestbestellwert);

        return $this;
    }

    /**
     * Sets the cCode
     *
     * @access public
     * @param string $cCode
     * @return $this
     */
    public function setCode($cCode)
    {
        $this->cCode = Shop::DB()->escape($cCode);

        return $this;
    }

    /**
     * Sets the nVerwendungen
     *
     * @access public
     * @param int $nVerwendungen
     * @return $this
     */
    public function setVerwendungen($nVerwendungen)
    {
        $this->nVerwendungen = (int)$nVerwendungen;

        return $this;
    }

    /**
     * Sets the nVerwendungenBisher
     *
     * @access public
     * @param int $nVerwendungenBisher
     * @return $this
     */
    public function setVerwendungenBisher($nVerwendungenBisher)
    {
        $this->nVerwendungenBisher = (int)$nVerwendungenBisher;

        return $this;
    }

    /**
     * Sets the nVerwendungenProKunde
     *
     * @access public
     * @param int $nVerwendungenProKunde
     * @return $this
     */
    public function setVerwendungenProKunde($nVerwendungenProKunde)
    {
        $this->nVerwendungenProKunde = (int)$nVerwendungenProKunde;

        return $this;
    }

    /**
     * Sets the cArtikel
     *
     * @access public
     * @param string $cArtikel
     * @return $this
     */
    public function setArtikel($cArtikel)
    {
        $this->cArtikel = Shop::DB()->escape($cArtikel);

        return $this;
    }

    /**
     * Sets the cKategorien
     *
     * @access public
     * @param string $cKategorien
     * @return $this
     */
    public function setKategorien($cKategorien)
    {
        $this->cKategorien = Shop::DB()->escape($cKategorien);

        return $this;
    }

    /**
     * Sets the cKunden
     *
     * @access public
     * @param string $cKunden
     * @return $this
     */
    public function setKunden($cKunden)
    {
        $this->cKunden = Shop::DB()->escape($cKunden);

        return $this;
    }

    /**
     * Sets the cKuponTyp
     *
     * @access public
     * @param string $cKuponTyp
     * @return $this
     */
    public function setKuponTyp($cKuponTyp)
    {
        $this->cKuponTyp = Shop::DB()->escape($cKuponTyp);

        return $this;
    }

    /**
     * Sets the cLieferlaender
     *
     * @access public
     * @param string $cLieferlaender
     * @return $this
     */
    public function setLieferlaender($cLieferlaender)
    {
        $this->cLieferlaender = Shop::DB()->escape($cLieferlaender);

        return $this;
    }

    /**
     * Sets the cZusatzgebuehren
     *
     * @access public
     * @param string $cZusatzgebuehren
     * @return $this
     */
    public function setZusatzgebuehren($cZusatzgebuehren)
    {
        $this->cZusatzgebuehren = Shop::DB()->escape($cZusatzgebuehren);

        return $this;
    }

    /**
     * Sets the cAktiv
     *
     * @access public
     * @param string $cAktiv
     * @return $this
     */
    public function setAktiv($cAktiv)
    {
        $this->cAktiv = Shop::DB()->escape($cAktiv);

        return $this;
    }

    /**
     * Sets the dErstellt
     *
     * @access public
     * @param string $dErstellt
     * @return $this
     */
    public function setErstellt($dErstellt)
    {
        $this->dErstellt = Shop::DB()->escape($dErstellt);

        return $this;
    }

    /**
     * Sets the nGanzenWKRabattieren
     *
     * @access public
     * @param int $nGanzenWKRabattieren
     * @return $this
     */
    public function setGanzenWKRabattieren($nGanzenWKRabattieren)
    {
        $this->nGanzenWKRabattieren = intval($nGanzenWKRabattieren);

        return $this;
    }

    /**
     * Gets the kKupon
     *
     * @access public
     * @return int
     */
    public function getKupon()
    {
        return $this->kKupon;
    }

    /**
     * Gets the kKundengruppe
     *
     * @access public
     * @return int
     */
    public function getKundengruppe()
    {
        return $this->kKundengruppe;
    }

    /**
     * Gets the kSteuerklasse
     *
     * @access public
     * @return int
     */
    public function getSteuerklasse()
    {
        return $this->kSteuerklasse;
    }

    /**
     * Gets the cName
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->cName;
    }

    /**
     * Gets the fWert
     *
     * @access public
     * @return float
     */
    public function getWert()
    {
        return $this->fWert;
    }

    /**
     * Gets the cWertTyp
     *
     * @access public
     * @return string
     */
    public function getWertTyp()
    {
        return $this->cWertTyp;
    }

    /**
     * Gets the dGueltigAb
     *
     * @access public
     * @return string
     */
    public function getGueltigAb()
    {
        return $this->dGueltigAb;
    }

    /**
     * Gets the dGueltigBis
     *
     * @access public
     * @return string
     */
    public function getGueltigBis()
    {
        return $this->dGueltigBis;
    }

    /**
     * Gets the fMindestbestellwert
     *
     * @access public
     * @return float
     */
    public function getMindestbestellwert()
    {
        return $this->fMindestbestellwert;
    }

    /**
     * Gets the cCode
     *
     * @access public
     * @return string
     */
    public function getCode()
    {
        return $this->cCode;
    }

    /**
     * Gets the nVerwendungen
     *
     * @access public
     * @return int
     */
    public function getVerwendungen()
    {
        return $this->nVerwendungen;
    }

    /**
     * Gets the nVerwendungenBisher
     *
     * @access public
     * @return int
     */
    public function getVerwendungenBisher()
    {
        return $this->nVerwendungenBisher;
    }

    /**
     * Gets the nVerwendungenProKunde
     *
     * @access public
     * @return int
     */
    public function getVerwendungenProKunde()
    {
        return $this->nVerwendungenProKunde;
    }

    /**
     * Gets the cArtikel
     *
     * @access public
     * @return string
     */
    public function getArtikel()
    {
        return $this->cArtikel;
    }

    /**
     * Gets the cKategorien
     *
     * @access public
     * @return string
     */
    public function getKategorien()
    {
        return $this->cKategorien;
    }

    /**
     * Gets the cKunden
     *
     * @access public
     * @return string
     */
    public function getKunden()
    {
        return $this->cKunden;
    }

    /**
     * Gets the cKuponTyp
     *
     * @access public
     * @return string
     */
    public function getKuponTyp()
    {
        return $this->cKuponTyp;
    }

    /**
     * Gets the cLieferlaender
     *
     * @access public
     * @return string
     */
    public function getLieferlaender()
    {
        return $this->cLieferlaender;
    }

    /**
     * Gets the cZusatzgebuehren
     *
     * @access public
     * @return string
     */
    public function getZusatzgebuehren()
    {
        return $this->cZusatzgebuehren;
    }

    /**
     * Gets the cAktiv
     *
     * @access public
     * @return string
     */
    public function getAktiv()
    {
        return $this->cAktiv;
    }

    /**
     * Gets the dErstellt
     *
     * @access public
     * @return string
     */
    public function getErstellt()
    {
        return $this->dErstellt;
    }

    /**
     * Gets the nGanzenWKRabattieren
     *
     * @access public
     * @return int
     */
    public function getGanzenWKRabattieren()
    {
        return $this->nGanzenWKRabattieren;
    }

    /**
     * @param int $stellen
     * @return string
     */
    public function generateCode($stellen = 7)
    {
        $nResult = strtoupper(substr(time() / 1000 + rand(123, 9999999), 0, $stellen));
        while (Shop::DB()->query("SELECT COUNT(*) FROM tkupon WHERE cCode = '" . $nResult . "'", 1)) {
            $nResult = strtoupper(substr(time() / 1000 + rand(123, 9999999), 0, $stellen));
        }

        return $nResult;
    }
}
