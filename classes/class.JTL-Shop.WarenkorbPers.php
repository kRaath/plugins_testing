<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

/**
 * Class WarenkorbPers
 */
class WarenkorbPers
{
    /**
     * @var int
     */
    public $kWarenkorbPers;

    /**
     * @var int
     */
    public $kKunde;

    /**
     * @var string
     */
    public $dErstellt;

    /**
     * @var array
     */
    public $oWarenkorbPersPos_arr = array();

    /**
     * @var string
     */
    public $cWarenwertLocalized;

    /**
     * @param int  $kKunde
     * @param bool $bArtikel
     */
    public function __construct($kKunde = 0, $bArtikel = false)
    {
        if ($kKunde > 0) {
            $this->kKunde = intval($kKunde);
            $this->ladeWarenkorbPers($bArtikel);
        }
    }

    /**
     * fügt eine Position zur WarenkorbPers hinzu
     *
     * @param int    $kArtikel
     * @param string $cArtikelName
     * @param array  $oEigenschaftwerte_arr
     * @param float  $fAnzahl
     * @param string $cUnique
     * @param int    $kKonfigitem
     * @return $this
     */
    public function fuegeEin($kArtikel, $cArtikelName, $oEigenschaftwerte_arr, $fAnzahl, $cUnique = '', $kKonfigitem = 0)
    {
        $bBereitsEnthalten = false;
        $nPosition         = 0;

        if (is_array($this->oWarenkorbPersPos_arr) && count($this->oWarenkorbPersPos_arr) > 0) {
            foreach ($this->oWarenkorbPersPos_arr as $i => $oWarenkorbPersPos) {
                if ($bBereitsEnthalten) {
                    break;
                }
                if ($oWarenkorbPersPos->kArtikel == $kArtikel && count($oWarenkorbPersPos->oWarenkorbPersPosEigenschaft_arr) > 0) {
                    $nPosition         = $i;
                    $bBereitsEnthalten = true;
                    foreach ($oEigenschaftwerte_arr as $oEigenschaftwerte) {
                        //kEigenschaftsWert is not set when using free text variations
                        if (!$oWarenkorbPersPos->istEigenschaftEnthalten($oEigenschaftwerte->kEigenschaft, (isset($oEigenschaftwerte->kEigenschaftWert) ?
                            $oEigenschaftwerte->kEigenschaftWert :
                            null))) {
                            $bBereitsEnthalten = false;
                            break;
                        }
                    }
                } elseif ($oWarenkorbPersPos->kArtikel == $kArtikel && $cUnique !== '' && $oWarenkorbPersPos->cUnique === $cUnique) {
                    $nPosition         = $i;
                    $bBereitsEnthalten = true;
                    break;
                }
            }
        }
        if ($bBereitsEnthalten) {
            $this->oWarenkorbPersPos_arr[$nPosition]->fAnzahl += $fAnzahl;
            $this->oWarenkorbPersPos_arr[$nPosition]->updateDB();
        } else {
            $oWarenkorbPersPos = new WarenkorbPersPos($kArtikel, $cArtikelName, $fAnzahl, $this->kWarenkorbPers, $cUnique, $kKonfigitem);
            $oWarenkorbPersPos->schreibeDB();
            $oWarenkorbPersPos->erstellePosEigenschaften($oEigenschaftwerte_arr);
            $this->oWarenkorbPersPos_arr[] = $oWarenkorbPersPos;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function entferneAlles()
    {
        if (is_array($this->oWarenkorbPersPos_arr) && count($this->oWarenkorbPersPos_arr) > 0) {
            foreach ($this->oWarenkorbPersPos_arr as $oWarenkorbPersPos) {
                // Eigenschaften löschen
                Shop::DB()->delete('twarenkorbpersposeigenschaft', 'kWarenkorbPersPos', (int) $oWarenkorbPersPos->kWarenkorbPersPos);
                // Postitionen löschen
                Shop::DB()->delete('twarenkorbperspos', 'kWarenkorbPers', (int) $oWarenkorbPersPos->kWarenkorbPers);
            }
        }

        $this->oWarenkorbPersPos_arr = array();

        return $this;
    }

    /**
     * @return bool
     */
    public function entferneSelf()
    {
        if ($this->kWarenkorbPers > 0) {
            // Entferne Pos und PosEigenschaft
            $this->entferneAlles();
            // Entferne Pers
            Shop::DB()->delete('twarenkorbpers', 'kWarenkorbPers', (int) $this->kWarenkorbPers);

            return true;
        }

        return false;
    }

    /**
     * @param int $kWarenkorbPersPos
     * @return $this
     */
    public function entfernePos($kWarenkorbPersPos)
    {
        $kWarenkorbPersPos = intval($kWarenkorbPersPos);
        $oKunde            = Shop::DB()->query(
            "SELECT twarenkorbpers.kKunde
                FROM twarenkorbpers
                JOIN twarenkorbperspos ON twarenkorbpers.kWarenkorbPers = twarenkorbperspos.kWarenkorbPers
                WHERE twarenkorbperspos.kWarenkorbPersPos = " . $kWarenkorbPersPos, 1
        );
        // Prüfen ob der eingeloggte Kunde auch der Besitzer der zu löschenden WarenkorbPersPos ist
        if ($oKunde->kKunde == $_SESSION['Kunde']->kKunde && $oKunde->kKunde) {
            // Alle Eigenschaften löschen
            Shop::DB()->delete('twarenkorbpersposeigenschaft', 'kWarenkorbPersPos', $kWarenkorbPersPos);
            // Die Position mit ID $kWarenkorbPersPos löschen
            Shop::DB()->delete('twarenkorbperspos', 'kWarenkorbPersPos', $kWarenkorbPersPos);
            // WarenkorbPers Position aus der Session löschen
            if (isset($_SESSION['WarenkorbPers']->oWarenkorbPersPos_arr) && is_array($_SESSION['WarenkorbPers']->oWarenkorbPersPos_arr) &&
                count($_SESSION['WarenkorbPers']->oWarenkorbPersPos_arr) > 0) {
                foreach ($_SESSION['WarenkorbPers']->oWarenkorbPersPos_arr as $i => $oWarenkorbPersPos) {
                    if ($oWarenkorbPersPos->kWarenkorbPersPos == $kWarenkorbPersPos) {
                        unset($_SESSION['WarenkorbPers']->oWarenkorbPersPos_arr[$i]);
                    }
                }
                // Positionen Array in der WarenkorbPers neu nummerieren
                $_SESSION['WarenkorbPers']->oWarenkorbPersPos_arr = array_merge($_SESSION['WarenkorbPers']->oWarenkorbPersPos_arr);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function schreibeDB()
    {
        $oTemp                = new stdClass();
        $oTemp->kKunde        = $this->kKunde;
        $oTemp->dErstellt     = $this->dErstellt;
        $this->kWarenkorbPers = Shop::DB()->insert('twarenkorbpers', $oTemp);
        unset($oTemp);

        return $this;
    }

    /**
     * @param bool $bArtikel
     * @return $this
     */
    public function ladeWarenkorbPers($bArtikel)
    {
        // Prüfe ob die WarenkorbPers dem eingeloggten Kunden gehört
        $oWarenkorbPers = Shop::DB()->query("SELECT * FROM twarenkorbpers WHERE kKunde = " . intval($this->kKunde), 1);

        if (!isset($oWarenkorbPers->kWarenkorbPers) || $oWarenkorbPers->kWarenkorbPers == 0) {
            $this->dErstellt = 'now()';
            $this->schreibeDB();
        }

        if ($oWarenkorbPers !== false && $oWarenkorbPers !== null) {
            $this->kWarenkorbPers = (isset($oWarenkorbPers->kWarenkorbPers)) ? $oWarenkorbPers->kWarenkorbPers : null;
            $this->kKunde         = (isset($oWarenkorbPers->kKunde)) ? $oWarenkorbPers->kKunde : 0;
            $this->dErstellt      = (isset($oWarenkorbPers->dErstellt)) ? $oWarenkorbPers->dErstellt : null;

            if ($this->kWarenkorbPers > 0) {
                // Hole alle Positionen für eine WarenkorbPers
                $oWarenkorbPersPos_arr = Shop::DB()->query(
                    "SELECT *, date_format(dHinzugefuegt, '%d.%m.%Y %H:%i') AS dHinzugefuegt_de
                        FROM twarenkorbperspos
                        WHERE kWarenkorbPers = " . (int) $this->kWarenkorbPers . "
                        ORDER BY kKonfigitem, kWarenkorbPersPos", 2
                );
                // Wenn Positionen vorhanden sind
                if (is_array($oWarenkorbPersPos_arr) && count($oWarenkorbPersPos_arr) > 0) {
                    $fWarenwert       = 0.0;
                    $oArtikelOptionen = Artikel::getDefaultOptions();
                    if (!isset($_SESSION['Steuersatz'])) {
                        setzeSteuersaetze();
                    }
                    // Hole alle Eigenschaften für eine Position
                    foreach ($oWarenkorbPersPos_arr as $oWarenkorbPersPosTMP) {
                        $oWarenkorbPersPos = new WarenkorbPersPos(
                            $oWarenkorbPersPosTMP->kArtikel,
                            $oWarenkorbPersPosTMP->cArtikelName,
                            $oWarenkorbPersPosTMP->fAnzahl,
                            $oWarenkorbPersPosTMP->kWarenkorbPers,
                            $oWarenkorbPersPosTMP->cUnique,
                            $oWarenkorbPersPosTMP->kKonfigitem
                        );

                        $oWarenkorbPersPos->kWarenkorbPersPos = $oWarenkorbPersPosTMP->kWarenkorbPersPos;
                        $oWarenkorbPersPos->cKommentar        = (isset($oWarenkorbPersPosTMP->cKommentar)) ? $oWarenkorbPersPosTMP->cKommentar : null;
                        $oWarenkorbPersPos->dHinzugefuegt     = $oWarenkorbPersPosTMP->dHinzugefuegt;
                        $oWarenkorbPersPos->dHinzugefuegt_de  = $oWarenkorbPersPosTMP->dHinzugefuegt_de;

                        $oWarenkorbPersPosEigenschaft_arr = Shop::DB()->query(
                            "SELECT *
                                FROM twarenkorbpersposeigenschaft
                                WHERE kWarenkorbPersPos = " . (int) $oWarenkorbPersPosTMP->kWarenkorbPersPos, 2
                        );
                        if (count($oWarenkorbPersPosEigenschaft_arr) > 0) {
                            foreach ($oWarenkorbPersPosEigenschaft_arr as $oWarenkorbPersPosEigenschaftTMP) {
                                $oWarenkorbPersPosEigenschaft = new WarenkorbPersPosEigenschaft(
                                    $oWarenkorbPersPosEigenschaftTMP->kEigenschaft,
                                    $oWarenkorbPersPosEigenschaftTMP->kEigenschaftWert,
                                    ((isset($oWarenkorbPersPosEigenschaftTMP->cFreiFeldWert)) ? $oWarenkorbPersPosEigenschaftTMP->cFreiFeldWert : null),
                                    $oWarenkorbPersPosEigenschaftTMP->cEigenschaftName,
                                    $oWarenkorbPersPosEigenschaftTMP->cEigenschaftWertName,
                                    $oWarenkorbPersPosEigenschaftTMP->kWarenkorbPersPos
                                );
                                $oWarenkorbPersPos->oWarenkorbPersPosEigenschaft_arr[] = $oWarenkorbPersPosEigenschaft;
                            }
                        }
                        if ($bArtikel) {
                            $oWarenkorbPersPos->Artikel = new Artikel();
                            $oWarenkorbPersPos->Artikel->fuelleArtikel($oWarenkorbPersPos->kArtikel, $oArtikelOptionen);
                            $oWarenkorbPersPos->cArtikelName = $oWarenkorbPersPos->Artikel->cName;

                            $fWarenwert += $oWarenkorbPersPos->Artikel->Preise->fVK[$oWarenkorbPersPos->Artikel->kSteuerklasse];
                        }
                        $this->oWarenkorbPersPos_arr[] = $oWarenkorbPersPos;
                    }
                    $this->cWarenwertLocalized = gibPreisStringLocalized($fWarenwert);
                }
            }
        }

        return $this;
    }

    /**
     * @param bool $bForceDelete
     * @return string
     */
    public function ueberpruefePositionen($bForceDelete = false)
    {
        $cArtikel_arr = array();
        $kArtikel_arr = array();
        $hinweis      = '';
        if (count($this->oWarenkorbPersPos_arr) > 0) {
            foreach ($this->oWarenkorbPersPos_arr as $WarenkorbPersPos) {
                // Hat die Position einen Artikel
                if ($WarenkorbPersPos->kArtikel > 0) {
                    // Prüfe auf kArtikel
                    $oArtikelVorhanden = Shop::DB()->query(
                        "SELECT kArtikel, cName
                            FROM tartikel
                            WHERE kArtikel = " . (int) $WarenkorbPersPos->kArtikel, 1
                    );
                    // Falls Artikel vorhanden
                    if (isset($oArtikelVorhanden->kArtikel) && $oArtikelVorhanden->kArtikel > 0) {
                        // Sichtbarkeit Prüfen
                        $oSichtbarkeit = Shop::DB()->query(
                            "SELECT kArtikel
                                FROM tartikelsichtbarkeit
                                WHERE kArtikel = " . (int) $WarenkorbPersPos->kArtikel . "
                                AND kKundengruppe = " . (int) $_SESSION['Kundengruppe']->kKundengruppe, 1
                        );
                        if ($oSichtbarkeit === false || $oSichtbarkeit === null || !isset($oSichtbarkeit->kArtikel) || !$oSichtbarkeit->kArtikel) {
                            // Prüfe welche kEigenschaft gesetzt ist
                            $oEigenschaft_arr = Shop::DB()->query(
                                "SELECT kEigenschaft, cName, cTyp
                                    FROM teigenschaft
                                    WHERE kArtikel = " . (int) $WarenkorbPersPos->kArtikel, 2
                            );
                            if (is_array($oEigenschaft_arr) && count($oEigenschaft_arr) > 0) {
                                foreach ($oEigenschaft_arr as $oEigenschaft) {
                                    if ($oEigenschaft->cTyp !== 'FREIFELD' && $oEigenschaft->cTyp !== 'PFLICHT-FREIFELD') {
                                        if (count($WarenkorbPersPos->oWarenkorbPersPosEigenschaft_arr) > 0) {
                                            foreach ($WarenkorbPersPos->oWarenkorbPersPosEigenschaft_arr as $oWarenkorbPersPosEigenschaft) {
                                                if ($oWarenkorbPersPosEigenschaft->kEigenschaft === $oEigenschaft->kEigenschaft) {
                                                    $oEigenschaftWertVorhanden = Shop::DB()->query(
                                                        "SELECT kEigenschaftWert
                                                            FROM teigenschaftwert
                                                            WHERE kEigenschaftWert = " . (int) $oWarenkorbPersPosEigenschaft->kEigenschaftWert . "
                                                            AND kEigenschaft = " . (int) $oEigenschaft->kEigenschaft, 1
                                                    );
                                                    // Prüfe ob die Eigenschaft vorhanden ist
                                                    if (!isset($oEigenschaftWertVorhanden->kEigenschaftWert) || !$oEigenschaftWertVorhanden->kEigenschaftWert) {
                                                        Shop::DB()->delete('twarenkorbperspos', 'kWarenkorbPersPos', $WarenkorbPersPos->kWarenkorbPersPos);
                                                        Shop::DB()->delete('twarenkorbpersposeigenschaft', 'kWarenkorbPersPos', $WarenkorbPersPos->kWarenkorbPersPos);
                                                        $cArtikel_arr[] = $WarenkorbPersPos->cArtikelName;
                                                        $hinweis .= '<br />' . Shop::Lang()->get('noProductWishlist', 'messages');
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            $kArtikel_arr[] = $oArtikelVorhanden->kArtikel;
                        }
                    }
                }
            }
            // Artikel aus dem Array Löschen, die nicht mehr Gültig sind
            if ($bForceDelete) {
                foreach ($this->oWarenkorbPersPos_arr as $i => $WarenkorbPersPos) {
                    if (!in_array($WarenkorbPersPos->kArtikel, $kArtikel_arr)) {
                        $this->entfernePos($WarenkorbPersPos->kWarenkorbPersPos);
                        Jtllog::writeLog(
                            'Der Artikel ' . $WarenkorbPersPos->kArtikel . ' ist vom persistenten Warenkorb gelöscht worden.',
                            JTLLOG_LEVEL_NOTICE,
                            false,
                            'kWarenkorbPersPos',
                            $WarenkorbPersPos->kWarenkorbPersPos
                        );
                        unset($this->oWarenkorbPersPos_arr[$i]);
                    }
                }
                $this->oWarenkorbPersPos_arr = array_merge($this->oWarenkorbPersPos_arr);
            }
        }
        // Artikel die nicht mehr Gültig sind aufführen und an den Hinweis hängen
        $tmp_str = '';
        if (count($cArtikel_arr) > 0) {
            foreach ($cArtikel_arr as $cArtikel) {
                $tmp_str .= $cArtikel . ', ';
            }
        }
        $hinweis .= substr($tmp_str, 0, strlen($tmp_str) - 2);

        return $hinweis;
    }

    /**
     * return $this
     */
    public function bauePersVonSession()
    {
        if (is_array($_SESSION['Warenkorb']->PositionenArr) && count($_SESSION['Warenkorb']->PositionenArr) > 0) {
            foreach ($_SESSION['Warenkorb']->PositionenArr as $oPosition) {
                if ($oPosition->nPosTyp == C_WARENKORBPOS_TYP_ARTIKEL) {
                    $oEigenschaftwerte_arr = array();
                    if (is_array($oPosition->WarenkorbPosEigenschaftArr) && count($oPosition->WarenkorbPosEigenschaftArr) > 0) {
                        foreach ($oPosition->WarenkorbPosEigenschaftArr as $oWarenkorbPosEigenschaft) {
                            unset($oEigenschaftwerte);
                            $oEigenschaftwerte                       = new stdClass();
                            $oEigenschaftwerte->kEigenschaftWert     = $oWarenkorbPosEigenschaft->kEigenschaftWert;
                            $oEigenschaftwerte->kEigenschaft         = $oWarenkorbPosEigenschaft->kEigenschaft;
                            $oEigenschaftwerte->cEigenschaftName     = $oWarenkorbPosEigenschaft->cEigenschaftName[$_SESSION['cISOSprache']];
                            $oEigenschaftwerte->cEigenschaftWertName = $oWarenkorbPosEigenschaft->cEigenschaftWertName[$_SESSION['cISOSprache']];
                            if ($oWarenkorbPosEigenschaft->cTyp === 'FREIFELD' || $oWarenkorbPosEigenschaft->cTyp === 'PFLICHT-FREIFELD') {
                                $oEigenschaftwerte->cFreifeldWert = $oWarenkorbPosEigenschaft->cEigenschaftWertName[$_SESSION['cISOSprache']];
                            }

                            $oEigenschaftwerte_arr[] = $oEigenschaftwerte;
                        }
                    }

                    $this->fuegeEin(
                        $oPosition->kArtikel,
                        (isset($oPosition->Artikel->cName)) ? $oPosition->Artikel->cName : null,
                        $oEigenschaftwerte_arr,
                        $oPosition->nAnzahl,
                        $oPosition->cUnique,
                        $oPosition->kKonfigitem
                    );
                }
            }
        }

        return $this;
    }
}
