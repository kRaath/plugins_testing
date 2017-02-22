<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

/**
 * Class WarenkorbPersPos
 */
class WarenkorbPersPos
{
    /**
     * @var int
     */
    public $kWarenkorbPersPos;

    /**
     * @var int
     */
    public $kWarenkorbPers;

    /**
     * @var int
     */
    public $kArtikel;

    /**
     * @var float
     */
    public $fAnzahl;

    /**
     * @var string
     */
    public $cArtikelName;

    /**
     * @var string
     */
    public $dHinzugefuegt;

    /**
     * @var string
     */
    public $dHinzugefuegt_de;

    /**
     * @var string
     */
    public $cUnique;

    /**
     * @var int
     */
    public $kKonfigitem;

    /**
     * @var array
     */
    public $oWarenkorbPersPosEigenschaft_arr = array();

    /**
     * @var string
     */
    public $cKommentar;

    /**
     * @var Artikel
     */
    public $Artikel;

    /**
     * @param int    $kArtikel
     * @param string $cArtikelName
     * @param float  $fAnzahl
     * @param int    $kWarenkorbPers
     * @param string $cUnique
     * @param int    $kKonfigitem
     */
    public function __construct($kArtikel, $cArtikelName, $fAnzahl, $kWarenkorbPers, $cUnique = '', $kKonfigitem = 0)
    {
        $this->kArtikel       = intval($kArtikel);
        $this->cArtikelName   = $cArtikelName;
        $this->fAnzahl        = $fAnzahl;
        $this->dHinzugefuegt  = 'now()';
        $this->kWarenkorbPers = intval($kWarenkorbPers);
        $this->cUnique        = $cUnique;
        $this->kKonfigitem    = $kKonfigitem;
    }

    /**
     * @param array $oEigenschaftwerte_arr
     * @return $this
     */
    public function erstellePosEigenschaften($oEigenschaftwerte_arr)
    {
        foreach ($oEigenschaftwerte_arr as $oEigenschaftwerte) {
            $oWarenkorbPersPosEigenschaft = new WarenkorbPersPosEigenschaft(
                $oEigenschaftwerte->kEigenschaft,
                ((isset($oEigenschaftwerte->kEigenschaftWert)) ? $oEigenschaftwerte->kEigenschaftWert : null),
                ((isset($oEigenschaftwerte->cFreifeldWert)) ? $oEigenschaftwerte->cFreifeldWert : null),
                ((isset($oEigenschaftwerte->cEigenschaftName)) ? $oEigenschaftwerte->cEigenschaftName : null),
                ((isset($oEigenschaftwerte->cEigenschaftWertName)) ? $oEigenschaftwerte->cEigenschaftWertName : null),
                $this->kWarenkorbPersPos
            );
            $oWarenkorbPersPosEigenschaft->schreibeDB();
            $this->oWarenkorbPersPosEigenschaft_arr[] = $oWarenkorbPersPosEigenschaft;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function schreibeDB()
    {
        $oTemp                   = new stdClass();
        $oTemp->kWarenkorbPers   = $this->kWarenkorbPers;
        $oTemp->kArtikel         = $this->kArtikel;
        $oTemp->cArtikelName     = $this->cArtikelName;
        $oTemp->fAnzahl          = $this->fAnzahl;
        $oTemp->dHinzugefuegt    = $this->dHinzugefuegt;
        $oTemp->cUnique          = $this->cUnique;
        $oTemp->kKonfigitem      = $this->kKonfigitem;
        $this->kWarenkorbPersPos = Shop::DB()->insert('twarenkorbperspos', $oTemp);

        return $this;
    }

    /**
     * @return int
     */
    public function updateDB()
    {
        $oTemp                    = new stdClass();
        $oTemp->kWarenkorbPersPos = $this->kWarenkorbPersPos;
        $oTemp->kWarenkorbPers    = $this->kWarenkorbPers;
        $oTemp->kArtikel          = $this->kArtikel;
        $oTemp->cArtikelName      = $this->cArtikelName;
        $oTemp->fAnzahl           = $this->fAnzahl;
        $oTemp->dHinzugefuegt     = $this->dHinzugefuegt;
        $oTemp->cUnique           = $this->cUnique;
        $oTemp->kKonfigitem       = $this->kKonfigitem;

        return Shop::DB()->update('twarenkorbperspos', 'kWarenkorbPersPos', $this->kWarenkorbPersPos, $oTemp);
    }

    /**
     * @param int $kEigenschaft
     * @param int $kEigenschaftWert
     * @return bool
     */
    public function istEigenschaftEnthalten($kEigenschaft, $kEigenschaftWert)
    {
        foreach ($this->oWarenkorbPersPosEigenschaft_arr as $oWarenkorbPersPosEigenschaft) {
            if ($oWarenkorbPersPosEigenschaft->kEigenschaft == $kEigenschaft && $oWarenkorbPersPosEigenschaft->kEigenschaftWert == $kEigenschaftWert) {
                return true;
            }
        }

        return false;
    }
}
