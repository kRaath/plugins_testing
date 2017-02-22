<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

/**
 * @param string             $seite
 * @param KategorieListe|int $KategorieListe
 * @param Artikel|int        $Artikel
 * @param string             $linkname
 * @param string             $linkURL
 * @param int                $kLink
 * @return string
 */
function createNavigation($seite, $KategorieListe = 0, $Artikel = 0, $linkname = '', $linkURL = '', $kLink = 0)
{
    $brotnavi          = array();
    $SieSindHierString = Shop::Lang()->get('youarehere', 'breadcrumb') .
        ': <a href="' . Shop::getURL() . '">' .
        Shop::Lang()->get('startpage', 'breadcrumb') . '</a>';
    $ele        = new stdClass();
    $ele->name  = Shop::Lang()->get('startpage', 'breadcrumb');
    $ele->url   = Shop::getURL();
    $brotnavi[] = $ele;
    switch ($seite) {
        case 'STARTSEITE':
            $SieSindHierString .= '<br />';
            break;

        case 'ARTIKEL':
            if (!isset($KategorieListe->elemente) || count($KategorieListe->elemente) === 0) {
                break;
            }
            $cntchr    = 0;
            $elemCount = count($KategorieListe->elemente) - 1;
            for ($i = $elemCount; $i >= 0; $i--) {
                $cntchr += strlen($KategorieListe->elemente[$i]->cName);
            }
            for ($i = $elemCount; $i >= 0; $i--) {
                if (isset($KategorieListe->elemente[$i]->cName) && isset($KategorieListe->elemente[$i]->cURL)) {
                    if ($cntchr < 80) {
                        $SieSindHierString .= ' &gt; <a href="' . $KategorieListe->elemente[$i]->cURL . '">' . $KategorieListe->elemente[$i]->cName . '</a>';
                    } else {
                        $cntchr -= strlen($KategorieListe->elemente[$i]->cName);
                        $SieSindHierString .= ' &gt; ...';
                    }
                    $ele        = new stdClass();
                    $ele->name  = $KategorieListe->elemente[$i]->cName;
                    $ele->url   = $KategorieListe->elemente[$i]->cURL;
                    $brotnavi[] = $ele;
                }
            }
            $SieSindHierString .= ' &gt; <a href="' . $Artikel->cURL . '">' . $Artikel->cName . '</a>';
            $ele        = new stdClass();
            $ele->name  = $Artikel->cName;
            $ele->url   = $Artikel->cURL;
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'PRODUKTE':
            $cntchr    = 0;
            $elemCount = (isset($KategorieListe->elemente)) ? count($KategorieListe->elemente) : 0;
            for ($i = $elemCount - 1; $i >= 0; $i--) {
                $cntchr += strlen($KategorieListe->elemente[$i]->cName);
            }
            for ($i = $elemCount - 1; $i >= 0; $i--) {
                if ($cntchr < 80) {
                    $SieSindHierString .= ' &gt; <a href="' . $KategorieListe->elemente[$i]->cURL . '">' . $KategorieListe->elemente[$i]->cName . '</a>';
                } else {
                    $cntchr -= strlen($KategorieListe->elemente[$i]->cName);
                    $SieSindHierString .= ' &gt; ...';
                }
                $ele        = new stdClass();
                $ele->name  = $KategorieListe->elemente[$i]->cName;
                $ele->url   = $KategorieListe->elemente[$i]->cURL;
                $brotnavi[] = $ele;
            }

            $SieSindHierString .= '<br />';
            break;

        case 'WARENKORB':
            $SieSindHierString .= ' &gt; <a href="warenkorb.php">' . Shop::Lang()->get('basket', 'breadcrumb') . '</a>';
            $ele        = new stdClass();
            $ele->name  = Shop::Lang()->get('basket', 'breadcrumb');
            $ele->url   = 'warenkorb.php';
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'PASSWORT VERGESSEN':
            $SieSindHierString .= ' &gt; <a href="pass.php">' . Shop::Lang()->get('forgotpassword', 'breadcrumb') . '</a>';
            $ele        = new stdClass();
            $ele->name  = Shop::Lang()->get('forgotpassword', 'breadcrumb');
            $ele->url   = 'pass.php';
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'MEIN KONTO':
            $cText = (isset($_SESSION['Kunde']->kKunde) && $_SESSION['Kunde']->kKunde > 0) ? Shop::Lang()->get('account', 'breadcrumb') : Shop::Lang()->get('login', 'breadcrumb');
            $SieSindHierString .= ' &gt; <a href="jtl.php">' . $cText . '</a>';
            $ele        = new stdClass();
            $ele->name  = $cText;
            $ele->url   = 'jtl.php';
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'BESTELLVORGANG':
            $SieSindHierString .= ' &gt; <a href="bestellvorgang.php">' . Shop::Lang()->get('checkout', 'breadcrumb') . '</a>';
            $ele        = new stdClass();
            $ele->name  = Shop::Lang()->get('checkout', 'breadcrumb');
            $ele->url   = 'bestellvorgang.php';
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'REGISTRIEREN':
            $SieSindHierString .= ' &gt; <a href="registrieren.php">' . Shop::Lang()->get('register', 'breadcrumb') . '</a>';
            $ele        = new stdClass();
            $ele->name  = Shop::Lang()->get('register', 'breadcrumb');
            $ele->url   = 'registrieren.php';
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'KONTAKT':
            $SieSindHierString .= ' &gt; <a href="kontakt.php">' . Shop::Lang()->get('contact', 'breadcrumb') . '</a>';
            $ele        = new stdClass();
            $ele->name  = Shop::Lang()->get('contact', 'breadcrumb');
            $ele->url   = 'kontakt.php';
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'WARTUNG':
            $SieSindHierString .= ' &gt; <a href="wartung.php">' . Shop::Lang()->get('maintainance', 'breadcrumb') . '</a>';
            $ele        = new stdClass();
            $ele->name  = Shop::Lang()->get('maintainance', 'breadcrumb');
            $ele->url   = 'wartung.php';
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'NEWSLETTER':
            $SieSindHierString .= ' &gt; <a href="' . $linkURL . '">' . Shop::Lang()->get('newsletter', 'breadcrumb') . '</a>';
            $ele        = new stdClass();
            $ele->name  = $linkname;
            $ele->url   = $linkURL;
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'NEWS':
            $SieSindHierString .= ' &gt; <a href="' . $linkURL . '">' . $linkname . '</a>';
            $ele        = new stdClass();
            $ele->name  = $linkname;
            $ele->url   = $linkURL;
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'NEWSDETAIL':
            $SieSindHierString .= ' &gt; <a href="news.php">' . Shop::Lang()->get('news', 'breadcrumb') . '</a>';
            $ele        = new stdClass();
            $ele->name  = Shop::Lang()->get('news', 'breadcrumb');
            $ele->url   = 'news.php';
            $brotnavi[] = $ele;

            $SieSindHierString .= ' &gt; <a href="' . $linkURL . '">' . $linkname . '</a>';
            $ele        = new stdClass();
            $ele->name  = $linkname;
            $ele->url   = $linkURL;
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'NEWSKATEGORIE':
            $SieSindHierString .= ' &gt; <a href="news.php">' . Shop::Lang()->get('newskat', 'breadcrumb') . '</a>';
            $ele        = new stdClass();
            $ele->name  = Shop::Lang()->get('newskat', 'breadcrumb');
            $ele->url   = 'news.php';
            $brotnavi[] = $ele;

            $SieSindHierString .= ' &gt; <a href="' . $linkURL . '">' . $linkname . '</a>';
            $ele        = new stdClass();
            $ele->name  = $linkname;
            $ele->url   = $linkURL;
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'NEWSMONAT':
            $SieSindHierString .= ' &gt; <a href="news.php">' . Shop::Lang()->get('newsmonat', 'breadcrumb') . '</a>';
            $ele        = new stdClass();
            $ele->name  = Shop::Lang()->get('newsmonat', 'breadcrumb');
            $ele->url   = 'news.php';
            $brotnavi[] = $ele;

            $SieSindHierString .= ' &gt; <a href="' . $linkURL . '">' . $linkname . '</a>';
            $ele        = new stdClass();
            $ele->name  = $linkname;
            $ele->url   = $linkURL;
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'UMFRAGE':
            $SieSindHierString .= ' &gt; <a href="' . $linkURL . '">' . $linkname . '</a>';
            $ele        = new stdClass();
            $ele->name  = $linkname;
            $ele->url   = $linkURL;
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        case 'VERGLEICHSLISTE':
            $SieSindHierString .= ' &gt; <a href="vergleichsliste.php">' . Shop::Lang()->get('compare', 'global') . '</a>';
            $ele        = new stdClass();
            $ele->name  = Shop::Lang()->get('compare', 'global');
            $ele->url   = 'vergleichsliste.php';
            $brotnavi[] = $ele;
            $SieSindHierString .= '<br />';
            break;

        default:
            $SieSindHierString .= ' &gt; <a href="' . $linkURL . '">' . $linkname . '</a>';
            $SieSindHierString .= '<br />';
            $linkHelper = LinkHelper::getInstance();
            $oLink      = $linkHelper->getLinkObject($kLink);
            $kVaterLink = (isset($oLink->kVaterLink)) ? intval($oLink->kVaterLink) : null;
            $elems      = array();
            do {
                if ($kVaterLink == 0) {
                    break;
                }
                $oItem = Shop::DB()->query("SELECT kLink, kVaterLink FROM tlink WHERE tlink.kLink = {$kVaterLink}", 1);
                if (!is_object($oItem)) {
                    break;
                }
                $oItem          = $linkHelper->getPageLink($oItem->kLink);
                $oItem->Sprache = $linkHelper->getPageLinkLanguage($oItem->kLink);
                $itm            = new stdClass();
                $itm->name      = $oItem->Sprache->cName;
                $itm->url       = baueURL($oItem, URLART_SEITE);
                $elems[]        = $itm;
                $kVaterLink     = $oItem->kVaterLink;
            } while (true);

            $elems      = array_reverse($elems);
            $brotnavi   = array_merge($brotnavi, $elems);
            $ele        = new stdClass();
            $ele->name  = $linkname;
            $ele->url   = $linkURL;
            $brotnavi[] = $ele;
            break;

    }
    executeHook(HOOK_TOOLSGLOBAL_INC_SWITCH_CREATENAVIGATION, array('navigation' => &$brotnavi));
    Shop::Smarty()->assign('Brotnavi', $brotnavi);

    return $SieSindHierString;
}

/**
 * @param float $preis
 * @return mixed
 */
function gibPreisString($preis)
{
    return str_replace(',', '.', sprintf('%.2f', $preis));
}

/**
 * @param float      $preis
 * @param object|int $waehrung
 * @param int        $html
 * @param int        $nNachkommastellen
 * @return string
 */
function gibPreisStringLocalized($preis, $waehrung = 0, $html = 1, $nNachkommastellen = 2)
{
    if (!$waehrung && isset($_SESSION['Waehrung'])) {
        $waehrung = $_SESSION['Waehrung'];
    }
    if (!isset($waehrung->kWaehrung) || !$waehrung->kWaehrung) {
        $waehrung = Shop::DB()->query("SELECT * FROM twaehrung WHERE cStandard = 'Y'", 1);
    }
    $preis        = number_format($preis * $waehrung->fFaktor, $nNachkommastellen, $waehrung->cTrennzeichenCent, $waehrung->cTrennzeichenTausend);
    $waherungname = (!$html) ? $waehrung->cName : $waehrung->cNameHTML;

    return ($waehrung->cVorBetrag === 'Y') ? ($waherungname . ' ' . $preis) : ($preis . ' ' . $waherungname);
}

/**
 * @param float $preis
 * @param float $MwSt
 * @param int   $nGenauigkeit
 * @return float
 */
function berechneBrutto($preis, $MwSt, $nGenauigkeit = 2)
{
    return round(($preis * (100 + $MwSt) / 100), intval($nGenauigkeit));
}

/**
 * @param float $fPreisBrutto
 * @param float $fMwSt
 * @param int   $nGenauigkeit
 * @return float
 */
function berechneNetto($fPreisBrutto, $fMwSt, $nGenauigkeit = 2)
{
    return round($fPreisBrutto / (100 + doubleval($fMwSt)) * 100, $nGenauigkeit);
}

/**
 * @param float  $fPreisNetto
 * @param float  $fPreisBrutto
 * @param string $cClass
 * @param bool   $bForceSteuer
 * @return string
 */
function getCurrencyConversion($fPreisNetto, $fPreisBrutto, $cClass = '', $bForceSteuer = true)
{
    $cString = '';
    if (isset($fPreisNetto) || isset($fPreisBrutto)) {
        $oWaehrung_arr = Shop::DB()->query("SELECT * FROM twaehrung ORDER BY cStandard DESC", 2);

        if (is_array($oWaehrung_arr) && count($oWaehrung_arr) > 0) {
            $oSteuerklasse = Shop::DB()->query("SELECT kSteuerklasse FROM tsteuerklasse WHERE cStandard = 'Y'", 1);
            $kSteuerklasse = (isset($oSteuerklasse->kSteuerklasse)) ? $oSteuerklasse->kSteuerklasse : 1;
            // Netto
            if (isset($fPreisNetto) && doubleval($fPreisNetto) > 0) {
                $fPreisNetto  = doubleval($fPreisNetto);
                $fPreisBrutto = berechneBrutto(doubleval($fPreisNetto), gibUst($kSteuerklasse));
            } elseif (isset($fPreisBrutto) && doubleval($fPreisBrutto) > 0) {
                $fPreisNetto  = berechneNetto(doubleval($fPreisBrutto), gibUst($kSteuerklasse));
                $fPreisBrutto = doubleval($fPreisBrutto);
            }
            $cString = '<span class="preisstring ' . $cClass . '">';
            foreach ($oWaehrung_arr as $i => $oWaehrung) {
                $cPreisLocalized       = number_format($fPreisNetto * $oWaehrung->fFaktor, 2, $oWaehrung->cTrennzeichenCent, $oWaehrung->cTrennzeichenTausend);
                $cPreisBruttoLocalized = number_format($fPreisBrutto * $oWaehrung->fFaktor, 2, $oWaehrung->cTrennzeichenCent, $oWaehrung->cTrennzeichenTausend);

                if ($oWaehrung->cVorBetrag === 'Y') {
                    $cPreisLocalized       = $oWaehrung->cNameHTML . ' ' . $cPreisLocalized;
                    $cPreisBruttoLocalized = $oWaehrung->cNameHTML . ' ' . $cPreisBruttoLocalized;
                } else {
                    $cPreisLocalized       = $cPreisLocalized . ' ' . $oWaehrung->cNameHTML;
                    $cPreisBruttoLocalized = $cPreisBruttoLocalized . ' ' . $oWaehrung->cNameHTML;
                }
                // Wurde geändert weil der Preis nun als Betrag gesehen wird und die Steuer direkt in der Versandart als eSteuer Flag eingestellt wird
                if ($i > 0) {
                    $cString .= ($bForceSteuer) ?
                        (' &#x2259; <strong>' . $cPreisBruttoLocalized . '</strong>' . ' (<em>' . $cPreisLocalized . '</em>)') :
                        (' &#x2259; ' . $cPreisBruttoLocalized);
                } else {
                    $cString .= ($bForceSteuer) ?
                        ('<strong>' . $cPreisBruttoLocalized . '</strong>' . ' (<em>' . $cPreisLocalized . '</em>)') :
                        '<strong>' . $cPreisBruttoLocalized . '</strong>';
                }
            }
            $cString .= ($bForceSteuer) ?
                ' (<strong>Brutto</strong> / Netto)</span>' :
                '</span>';
        }
    }

    return $cString;
}

/**
 * @param string $var
 * @return bool
 */
function hasGPCDataInteger($var)
{
    return (isset($_POST[$var]) || isset($_GET[$var]) || isset($_COOKIE[$var]));
}

/**
 * @param string $var
 * @return int
 */
function verifyGPCDataInteger($var)
{
    if (isset($_POST[$var])) {
        return (int) $_POST[$var];
    }
    if (isset($_GET[$var])) {
        return (int) $_GET[$var];
    }
    if (isset($_COOKIE[$var])) {
        return (int) $_COOKIE[$var];
    }

    return 0;
}

/**
 * @param string $var
 * @return string
 */
function verifyGPDataString($var)
{
    if (isset($_POST[$var])) {
        return $_POST[$var];
    }
    if (isset($_GET[$var])) {
        return $_GET[$var];
    }

    return '';
}

/**
 * @param object $originalObj
 * @return stdClass
 */
function kopiereMembers($originalObj)
{
    if (!is_object($originalObj)) {
        return $originalObj;
    }
    $obj     = new stdClass();
    $members = array_keys(get_object_vars($originalObj));
    if (is_array($members) && count($members) > 0) {
        foreach ($members as $member) {
            $obj->$member = $originalObj->$member;
        }
    }

    return $obj;
}

/**
 * @return mixed
 */
function getRealIp()
{
    $ip = null;
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip   = $list[0];
    } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $ip = filter_var($ip, FILTER_VALIDATE_IP);

    return ($ip === false) ? '0.0.0.0' : $ip;
}

/**
 * @param bool $bBestellung
 * @return mixed|string
 */
function gibIP($bBestellung = false)
{
    $ip   = getRealIp();
    $conf = Shop::getSettings(array(CONF_KAUFABWICKLUNG, CONF_GLOBAL));
    if ($conf['global']['global_ips_speichern'] === 'Y' && !$bBestellung) {
        return $ip;
    }
    if ($conf['global']['global_ips_speichern'] === 'N' && !$bBestellung) {
        return substr($ip, 0, strpos($ip, '.', strpos($ip, '.') + 1) + 1) . '*.*';
    }
    if ($conf['kaufabwicklung']['bestellabschluss_ip_speichern'] === 'Y' && $bBestellung) {
        return $ip;
    }
    if ($conf['kaufabwicklung']['bestellabschluss_ip_speichern'] === 'N' && $bBestellung) {
        return substr($ip, 0, strpos($ip, '.', strpos($ip, '.') + 1) + 1) . '*.*';
    }

    return $ip;
}

/**
 *
 */
function checkeWarenkorbEingang()
{
    $fAnzahl = 0;
    if (isset($_POST['anzahl'])) {
        $_POST['anzahl'] = str_replace(',', '.', $_POST['anzahl']);
    }
    if (isset($_POST['anzahl']) && doubleval($_POST['anzahl']) > 0) {
        $fAnzahl = doubleval($_POST['anzahl']);
    } elseif (isset($_GET['anzahl']) && doubleval($_GET['anzahl']) > 0) {
        $fAnzahl = doubleval($_GET['anzahl']);
    }
    if (isset($_POST['n']) && doubleval($_POST['n']) > 0) {
        $fAnzahl = doubleval($_POST['n']);
    } elseif (isset($_POST['n']) && doubleval($_GET['n']) > 0) {
        $fAnzahl = doubleval($_GET['n']);
    }
    $kArtikel = verifyGPCDataInteger('a');
    $conf     = Shop::getSettings(array(CONF_GLOBAL));
    executeHook(HOOK_TOOLS_GLOBAL_CHECKEWARENKORBEINGANG_ANFANG, array('kArtikel' => $kArtikel, 'fAnzahl' => $fAnzahl));
    // Wunschliste?
    if ((isset($_POST['Wunschliste']) || isset($_GET['Wunschliste'])) && $conf['global']['global_wunschliste_anzeigen'] === 'Y') {
        // Prüfe ob Kunde eingeloggt
        if (!isset($_SESSION['Kunde']->kKunde)) {
            //redirekt zum artikel, um variation/en zu wählen / MBM beachten
            header('Location: jtl.php?a=' . $kArtikel . '&n=' . $fAnzahl . '&r=' . R_LOGIN_WUNSCHLISTE, true, 302);
            exit();
        }

        if ($kArtikel > 0) {
            // Prüfe auf kArtikel
            $oArtikelVorhanden = Shop::DB()->select('tartikel',  'kArtikel', $kArtikel, null, null, null, null, false, 'kArtikel, cName');
            // Falls Artikel vorhanden
            if (isset($oArtikelVorhanden->kArtikel) && $oArtikelVorhanden->kArtikel > 0) {
                $oEigenschaftwerte_arr = array();
                // Sichtbarkeit Prüfen
                $oSichtbarkeit = Shop::DB()->select('tartikelsichtbarkeit', 'kArtikel', $kArtikel, 'kKundengruppe', intval($_SESSION['Kundengruppe']->kKundengruppe), null, null, false, 'kArtikel');
                if (!isset($oSichtbarkeit->kArtikel) || !$oSichtbarkeit->kArtikel) {
                    // Prüfe auf Vater Artikel
                    if (ArtikelHelper::isParent($kArtikel)) {
                        // Falls die Wunschliste aus der Artikelübersicht ausgewählt wurde, muss zum Artikel weitergeleitet werden
                        // um Variationen zu wählen
                        if (verifyGPCDataInteger('overview') === 1) {
                            header('Location: ' . Shop::getURL() . '/navi.php?a=' . $kArtikel . '&n=' . $fAnzahl . '&r=' . R_VARWAEHLEN, true, 303);
                            exit;
                        }

                        $kArtikel = ArtikelHelper::getArticleForParent($kArtikel);
                        if ($kArtikel > 0) {
                            $oEigenschaftwerte_arr = ArtikelHelper::getSelectedPropertiesForVarCombiArticle($kArtikel);
                        }
                    } else {
                        $oEigenschaftwerte_arr = ArtikelHelper::getSelectedPropertiesForArticle($kArtikel);
                    }
                    // Prüfe ob die Session ein Wunschlisten Objekt hat
                    if ($kArtikel > 0) {
                        if (!$_SESSION['Wunschliste']->kWunschliste) {
                            $_SESSION['Wunschliste'] = new Wunschliste();
                            $_SESSION['Wunschliste']->schreibeDB();
                        }
                        if ($fAnzahl <= 0) {
                            $fAnzahl = 1;
                        }
                        $kWunschlistePos = $_SESSION['Wunschliste']->fuegeEin($kArtikel, $oArtikelVorhanden->cName, $oEigenschaftwerte_arr, $fAnzahl);
                        // Kampagne
                        if (isset($_SESSION['Kampagnenbesucher'])) {
                            setzeKampagnenVorgang(KAMPAGNE_DEF_WUNSCHLISTE, $kWunschlistePos, $fAnzahl); // Wunschliste
                        }

                        $obj           = new stdClass();
                        $obj->kArtikel = $kArtikel;
                        executeHook(HOOK_TOOLS_GLOBAL_CHECKEWARENKORBEINGANG_WUNSCHLISTE, array('kArtikel' => &$kArtikel, 'fAnzahl' => &$fAnzahl, 'AktuellerArtikel' => &$obj));

                        Shop::Smarty()->assign('hinweis', Shop::Lang()->get('wishlistProductadded', 'messages'));
                        // Weiterleiten?
                        if ($conf['global']['global_wunschliste_weiterleitung'] === 'Y') {
                            header('Location: jtl.php?wl=' . $_SESSION['Wunschliste']->kWunschliste, true, 302);
                            exit;
                        }
                    }
                }
            }
        }
    } elseif (isset($_POST['Vergleichsliste'])) { // Vergleichsliste?
        if ($kArtikel > 0) {
            $Einstellungen_vergleichsliste = Shop::getSettings(array(CONF_VERGLEICHSLISTE));

            // Prüfen ob nicht schon die maximale Anzahl an Artikeln auf der Vergleichsliste ist
            if (!isset($_SESSION['Vergleichsliste']->oArtikel_arr) ||
                intval($Einstellungen_vergleichsliste['vergleichsliste']['vergleichsliste_anzahl']) > count($_SESSION['Vergleichsliste']->oArtikel_arr)
            ) {
                // Prüfe auf kArtikel
                $oArtikelVorhanden = Shop::DB()->select('tartikel', 'kArtikel', $kArtikel, null, null, null, null, false, 'kArtikel, cName');
                // Falls Artikel vorhanden
                if (isset($oArtikelVorhanden->kArtikel)) {
                    // Sichtbarkeit Prüfen
                    $oSichtbarkeit = Shop::DB()->select('tartikelsichtbarkeit', 'kArtikel', $kArtikel, 'kKundengruppe', intval($_SESSION['Kundengruppe']->kKundengruppe), null, null, false, 'kArtikel');
                    if ($oSichtbarkeit === false || !isset($oSichtbarkeit->kArtikel) || !$oSichtbarkeit->kArtikel) {
                        // Prüfe auf Vater Artikel
                        $oVariationen_arr = 0;
                        if (ArtikelHelper::isParent($kArtikel)) {
                            $kArtikel         = ArtikelHelper::getArticleForParent($kArtikel);
                            $oVariationen_arr = ArtikelHelper::getSelectedPropertiesForVarCombiArticle($kArtikel, 1);
                        }
                        // Prüfe auf Vater Artikel
                        if (ArtikelHelper::isParent($kArtikel)) {
                            $kArtikel = ArtikelHelper::getArticleForParent($kArtikel);
                        }

                        $oVergleichsliste = new Vergleichsliste($kArtikel, $oVariationen_arr);
                        // Falls es eine Vergleichsliste in der Session gibt
                        if (isset($_SESSION['Vergleichsliste'])) {
                            // Falls Artikel vorhanden sind
                            if (is_array($_SESSION['Vergleichsliste']->oArtikel_arr) && count($_SESSION['Vergleichsliste']->oArtikel_arr) > 0) {
                                $bSchonVorhanden = false;
                                foreach ($_SESSION['Vergleichsliste']->oArtikel_arr as $oArtikel) {
                                    if ($oArtikel->kArtikel == $oVergleichsliste->oArtikel_arr[0]->kArtikel) {
                                        $bSchonVorhanden = true;
                                        break;
                                    }
                                }
                                // Wenn der Artikel der eingetragen werden soll, nicht schon in der Session ist
                                if (!$bSchonVorhanden) {
                                    foreach ($_SESSION['Vergleichsliste']->oArtikel_arr as $oArtikel) {
                                        $oVergleichsliste->oArtikel_arr[] = $oArtikel;
                                    }
                                    $_SESSION['Vergleichsliste'] = $oVergleichsliste;
                                    Shop::Smarty()->assign('hinweis', Shop::Lang()->get('comparelistProductadded', 'messages'));
                                } else {
                                    Shop::Smarty()->assign('fehler', Shop::Lang()->get('comparelistProductexists', 'messages'));
                                }
                            }
                        } else {
                            // Vergleichsliste neu in der Session anlegen
                            $_SESSION['Vergleichsliste'] = $oVergleichsliste;
                            Shop::Smarty()->assign('hinweis', Shop::Lang()->get('comparelistProductadded', 'messages'));
                            setzeLinks();
                        }
                    }
                }
            } else {
                Shop::Smarty()->assign('fehler', Shop::Lang()->get('compareMaxlimit', 'errorMessages'));
            }
        }
    } elseif (isset($_POST['wke']) && intval($_POST['wke']) === 1 && !isset($_POST['Vergleichsliste']) && !isset($_POST['Wunschliste'])) { //warenkorbeingang?
        // VariationsBox ist vorhanden => Prüfen ob Anzahl gesetzt wurde
        if (isset($_POST['variBox']) && intval($_POST['variBox']) === 1) {
            if (pruefeVariBoxAnzahl($_POST['variBoxAnzahl'])) {
                fuegeVariBoxInWK($_POST['variBoxAnzahl'], $kArtikel, ArtikelHelper::isParent($kArtikel), isset($_POST['varimatrix']));
            } else {
                header('Location: index.php?a=' . $kArtikel . '&r=' . R_EMPTY_VARIBOX, true, 303);
                exit;
            }
        } else {
            if (ArtikelHelper::isParent($kArtikel)) { // Varikombi
                $kArtikel              = ArtikelHelper::getArticleForParent($kArtikel);
                $oEigenschaftwerte_arr = ArtikelHelper::getSelectedPropertiesForVarCombiArticle($kArtikel);
            } else {
                $oEigenschaftwerte_arr = ArtikelHelper::getSelectedPropertiesForArticle($kArtikel);
            }
            $isConfigArticle = false;
            if (class_exists('Konfigurator')) {
                if (!Konfigurator::validateKonfig($kArtikel)) {
                    $isConfigArticle = false;
                } else {
                    $oGruppen_arr    = Konfigurator::getKonfig($kArtikel);
                    $isConfigArticle = (is_array($oGruppen_arr) && count($oGruppen_arr) > 0);
                }
            }

            if ($isConfigArticle) {
                $bValid                  = true;
                $aError_arr              = array();
                $aItemError_arr          = array();
                $oKonfigitem_arr         = array();
                $nKonfiggruppe_arr       = (isset($_POST['item']) && is_array($_POST['item'])) ? $_POST['item'] : array();
                $nKonfiggruppeAnzahl_arr = (isset($_POST['quantity']) && is_array($_POST['quantity'])) ? $_POST['quantity'] : array();
                $nKonfigitemAnzahl_arr   = (isset($_POST['item_quantity']) && is_array($_POST['item_quantity'])) ? $_POST['item_quantity'] : false;
                $bIgnoreLimits           = isset($_POST['konfig_ignore_limits']);

                if (!function_exists('baueArtikelhinweise')) {
                    require_once PFAD_ROOT . PFAD_INCLUDES . 'artikel_inc.php';
                }
                // Beim Bearbeiten die alten Positionen löschen
                if (isset($_POST['kEditKonfig'])) {
                    $kEditKonfig = intval($_POST['kEditKonfig']);

                    if (!function_exists('loescheWarenkorbPosition')) {
                        require_once PFAD_ROOT . PFAD_INCLUDES . 'warenkorb_inc.php';
                    }

                    loescheWarenkorbPosition($kEditKonfig);
                }

                foreach ($nKonfiggruppe_arr as $nKonfigitem_arr) {
                    foreach ($nKonfigitem_arr as $kKonfigitem) {
                        $kKonfigitem = intval($kKonfigitem);
                        // Falls ungültig, ignorieren
                        if ($kKonfigitem <= 0) {
                            continue;
                        }
                        $oKonfigitem          = new Konfigitem($kKonfigitem);
                        $oKonfigitem->fAnzahl = floatval(
                            isset($nKonfiggruppeAnzahl_arr[$oKonfigitem->getKonfiggruppe()]) ?
                                $nKonfiggruppeAnzahl_arr[$oKonfigitem->getKonfiggruppe()] : $oKonfigitem->getInitial()
                        );
                        if ($nKonfigitemAnzahl_arr && isset($nKonfigitemAnzahl_arr[$oKonfigitem->getKonfigitem()])) {
                            $oKonfigitem->fAnzahl = floatval($nKonfigitemAnzahl_arr[$oKonfigitem->getKonfigitem()]);
                        }
                        // Todo: Mindestbestellanzahl / Abnahmeinterval beachten
                        if ($oKonfigitem->fAnzahl < 1) {
                            $oKonfigitem->fAnzahl = 1;
                        }
                        if ($fAnzahl < 1) {
                            $fAnzahl = 1;
                        }
                        $oKonfigitem->fAnzahlWK = $oKonfigitem->fAnzahl;
                        if (!$oKonfigitem->ignoreMultiplier()) {
                            $oKonfigitem->fAnzahlWK *= $fAnzahl;
                        }
                        $oKonfigitem_arr[] = $oKonfigitem;
                        // Alle Artikel können in den WK gelegt werden?
                        if ($oKonfigitem->getPosTyp() == KONFIG_ITEM_TYP_ARTIKEL) {
                            // Varikombi
                            $oKonfigitem->oEigenschaftwerte_arr = array();
                            $oTmpArtikel                        = $oKonfigitem->getArtikel();

                            if ($oTmpArtikel->kVaterArtikel > 0) {
                                if (isset($oTmpArtikel->kEigenschaftKombi) && $oTmpArtikel->kEigenschaftKombi > 0) {
                                    $oKonfigitem->oEigenschaftwerte_arr = gibVarKombiEigenschaftsWerte($oTmpArtikel->kArtikel, false);
                                }
                            }
                            if ($oTmpArtikel->cTeilbar !== 'Y' && intval($fAnzahl) != $fAnzahl) {
                                $fAnzahl = intval($fAnzahl);
                            }
                            $redirectParam = pruefeFuegeEinInWarenkorb(
                                $oKonfigitem->getArtikel(),
                                $oKonfigitem->fAnzahlWK,
                                $oKonfigitem->oEigenschaftwerte_arr
                            );
                            if (count($redirectParam) > 0) {
                                $bValid            = false;
                                $aArticleError_arr = baueArtikelhinweise(
                                    $redirectParam,
                                    true,
                                    $oKonfigitem->getArtikel(),
                                    $oKonfigitem->fAnzahlWK,
                                    $oKonfigitem->getKonfigitem()
                                );
                                $aItemError_arr[$oKonfigitem->getKonfigitem()] = $aArticleError_arr[0];
                            }
                        }
                    }
                }
                // Komplette Konfiguration validieren
                if (!$bIgnoreLimits) {
                    if (($aError_arr = Konfigurator::validateBasket($kArtikel, $oKonfigitem_arr)) !== true) {
                        $bValid = false;
                    }
                }
                // Alle Konfigurationsartikel können in den WK gelegt werden
                if ($bValid) {
                    // Eindeutige ID
                    $cUnique = gibUID(10);
                    // Hauptartikel in den WK legen
                    fuegeEinInWarenkorb($kArtikel, $fAnzahl, $oEigenschaftwerte_arr, 0, $cUnique);
                    // Konfigartikel in den WK legen
                    foreach ($oKonfigitem_arr as $oKonfigitem) {
                        switch ($oKonfigitem->getPosTyp()) {
                            case KONFIG_ITEM_TYP_ARTIKEL :
                                $_SESSION['Warenkorb']->fuegeEin(
                                    $oKonfigitem->getArtikelKey(),
                                    $oKonfigitem->fAnzahlWK,
                                    $oKonfigitem->oEigenschaftwerte_arr,
                                    C_WARENKORBPOS_TYP_ARTIKEL,
                                    $cUnique,
                                    $oKonfigitem->getKonfigitem()
                                );
                                break;

                            case KONFIG_ITEM_TYP_SPEZIAL :
                                $_SESSION['Warenkorb']->erstelleSpezialPos(
                                    $oKonfigitem->getName(),
                                    $oKonfigitem->fAnzahlWK,
                                    $oKonfigitem->getPreis(),
                                    $oKonfigitem->getSteuerklasse(),
                                    C_WARENKORBPOS_TYP_ARTIKEL,
                                    false,
                                    !$_SESSION['Kundengruppe']->nNettoPreise,
                                    '',
                                    $cUnique,
                                    $oKonfigitem->getKonfigitem(),
                                    $oKonfigitem->getArtikelKey()
                                );
                                break;
                        }

                        fuegeEinInWarenkorbPers(
                            $oKonfigitem->getArtikelKey(),
                            $oKonfigitem->fAnzahlWK,
                            (isset($oKonfigitem->oEigenschaftwerte_arr)) ? $oKonfigitem->oEigenschaftwerte_arr : array(),
                            $cUnique,
                            $oKonfigitem->getKonfigitem()
                        );
                    }
                    // Warenkorb weiterleiten
                    $_SESSION['Warenkorb']->redirectTo();
                } else {
                    // Gesammelte Fehler anzeigen
                    Shop::Smarty()->assign('aKonfigerror_arr', $aError_arr)
                        ->assign('aKonfigitemerror_arr', $aItemError_arr)
                        ->assign('fehler', Shop::Lang()->get('configError', 'productDetails'));
                }

                $nKonfigitem_arr = array();
                foreach ($nKonfiggruppe_arr as $nTmpKonfigitem_arr) {
                    $nKonfigitem_arr = array_merge($nKonfigitem_arr, $nTmpKonfigitem_arr);
                }
                Shop::Smarty()->assign('fAnzahl', $fAnzahl)
                    ->assign('nKonfigitem_arr', $nKonfigitem_arr)
                    ->assign('nKonfigitemAnzahl_arr', $nKonfigitemAnzahl_arr)
                    ->assign('nKonfiggruppeAnzahl_arr', $nKonfiggruppeAnzahl_arr);
            } else {
                fuegeEinInWarenkorb($kArtikel, $fAnzahl, $oEigenschaftwerte_arr);
            }
        }
    }
}

/**
 * @param array $variBoxAnzahl_arr
 * @param int   $kArtikel
 * @param bool  $bIstVater
 * @param bool  $bExtern
 */
function fuegeVariBoxInWK($variBoxAnzahl_arr, $kArtikel, $bIstVater, $bExtern = false)
{
    if (is_array($variBoxAnzahl_arr) && count($variBoxAnzahl_arr) > 0) {
        $cKeys_arr            = array_keys($variBoxAnzahl_arr);
        $kVaterArtikel        = $kArtikel;
        $oAlleEigenschaft_arr = array();
        unset($_SESSION['variBoxAnzahl_arr']);
        // Es ist min. eine Anzahl vorhanden
        foreach ($cKeys_arr as $cKeys) {
            if (doubleval($variBoxAnzahl_arr[$cKeys]) > 0) {
                // Switch zwischen 1 Vari und 2
                if ($cKeys[0] == '_') { // 1
                    $cVariation0                             = substr($cKeys, 1);
                    list($kEigenschaft0, $kEigenschaftWert0) = explode(':', $cVariation0);
                    // In die Session einbauen
                    $oVariKombi                                 = new stdClass();
                    $oVariKombi->fAnzahl                        = doubleval($variBoxAnzahl_arr[$cKeys]);
                    $oVariKombi->cVariation0                    = StringHandler::filterXSS($cVariation0);
                    $oVariKombi->kEigenschaft0                  = intval($kEigenschaft0);
                    $oVariKombi->kEigenschaftWert0              = intval($kEigenschaftWert0);
                    $_SESSION['variBoxAnzahl_arr'][$cKeys]      = $oVariKombi;
                    $_POST['eigenschaftwert_' . $kEigenschaft0] = $kEigenschaftWert0;
                } else {
                    if ($bExtern) {
                        $cComb_arr                        = explode('_', $cKeys);
                        $oVariKombi                       = new stdClass();
                        $oVariKombi->fAnzahl              = doubleval($variBoxAnzahl_arr[$cKeys]);
                        $oVariKombi->kEigenschaft_arr     = array();
                        $oVariKombi->kEigenschaftWert_arr = array();
                        foreach ($cComb_arr as $cComb) {
                            list($kEigenschaft, $kEigenschaftWert)     = explode(':', $cComb);
                            $oVariKombi->kEigenschaft_arr[]            = (int) $kEigenschaft;
                            $oVariKombi->kEigenschaftWert_arr[]        = (int) $kEigenschaftWert;
                            $_POST['eigenschaftwert_' . $kEigenschaft] = (int) $kEigenschaftWert;
                        }
                        $_SESSION['variBoxAnzahl_arr'][$cKeys] = $oVariKombi;
                    } else {
                        list($cVariation0, $cVariation1)         = explode('_', $cKeys);
                        list($kEigenschaft0, $kEigenschaftWert0) = explode(':', $cVariation0);
                        list($kEigenschaft1, $kEigenschaftWert1) = explode(':', $cVariation1);
                        // In die Session einbauen
                        $oVariKombi                                 = new stdClass();
                        $oVariKombi->fAnzahl                        = doubleval($variBoxAnzahl_arr[$cKeys]);
                        $oVariKombi->cVariation0                    = StringHandler::filterXSS($cVariation0);
                        $oVariKombi->cVariation1                    = StringHandler::filterXSS($cVariation1);
                        $oVariKombi->kEigenschaft0                  = intval($kEigenschaft0);
                        $oVariKombi->kEigenschaftWert0              = intval($kEigenschaftWert0);
                        $oVariKombi->kEigenschaft1                  = intval($kEigenschaft1);
                        $oVariKombi->kEigenschaftWert1              = intval($kEigenschaftWert1);
                        $_SESSION['variBoxAnzahl_arr'][$cKeys]      = $oVariKombi;
                        $_POST['eigenschaftwert_' . $kEigenschaft0] = $kEigenschaftWert0;
                        $_POST['eigenschaftwert_' . $kEigenschaft1] = $kEigenschaftWert1;
                    }
                }
                $oAlleEigenschaft_arr[$cKeys]                   = new stdClass();
                $oAlleEigenschaft_arr[$cKeys]->oEigenschaft_arr = array();
                $oAlleEigenschaft_arr[$cKeys]->kArtikel         = 0;

                if ($bIstVater) {
                    $kArtikel                                       = ArtikelHelper::getArticleForParent($kVaterArtikel);
                    $oAlleEigenschaft_arr[$cKeys]->oEigenschaft_arr = ArtikelHelper::getSelectedPropertiesForVarCombiArticle($kArtikel);
                    $oAlleEigenschaft_arr[$cKeys]->kArtikel         = $kArtikel;
                } else {
                    $oAlleEigenschaft_arr[$cKeys]->oEigenschaft_arr = ArtikelHelper::getSelectedPropertiesForArticle($kArtikel);
                    $oAlleEigenschaft_arr[$cKeys]->kArtikel         = $kArtikel;
                }
            }
        }

        $nRedirectErr_arr = array();
        if (is_array($oAlleEigenschaft_arr) && count($oAlleEigenschaft_arr) > 0) {
            foreach ($oAlleEigenschaft_arr as $i => $oAlleEigenschaftPre) {
                $Artikel = new Artikel();
                $Artikel->fuelleArtikel($oAlleEigenschaftPre->kArtikel, Artikel::getDefaultOptions());
                // Prüfe ob er Artikel in den Warenkorb gelegt werden darf
                $nRedirect_arr = pruefeFuegeEinInWarenkorb($Artikel, doubleval($variBoxAnzahl_arr[$i]), $oAlleEigenschaftPre->oEigenschaft_arr);

                $_SESSION['variBoxAnzahl_arr'][$i]->bError = false;
                if (count($nRedirect_arr) > 0) {
                    foreach ($nRedirect_arr as $nRedirect) {
                        if (!in_array($nRedirect, $nRedirectErr_arr)) {
                            $nRedirectErr_arr[] = $nRedirect;
                        }
                    }

                    $_SESSION['variBoxAnzahl_arr'][$i]->bError = true;
                }
            }

            if (count($nRedirectErr_arr) > 0) {
                //redirekt zum artikel, um variation/en zu wählen / MBM beachten
                if ($bIstVater) {
                    header('Location: navi.php?a=' . $kVaterArtikel . '&r=' . implode(',', $nRedirectErr_arr), true, 302);
                } else {
                    header('Location: index.php?a=' . $kVaterArtikel . '&r=' . implode(',', $nRedirectErr_arr), true, 302);
                }
                exit();
            } else {
                foreach ($oAlleEigenschaft_arr as $i => $oAlleEigenschaftPost) {
                    if (!$_SESSION['variBoxAnzahl_arr'][$i]->bError) {
                        //#8224, #7482 -> do not call setzePositionsPreise() in loop @ Wanrekob::fuegeEin()
                        fuegeEinInWarenkorb(
                            $oAlleEigenschaftPost->kArtikel,
                            doubleval($variBoxAnzahl_arr[$i]),
                            $oAlleEigenschaftPost->oEigenschaft_arr,
                            0,
                            false,
                            0,
                            null,
                            false
                        );
                    }
                }
                $_SESSION['Warenkorb']->setzePositionsPreise();
                unset($_SESSION['variBoxAnzahl_arr']);
                $_SESSION['Warenkorb']->redirectTo();
            }
        }
    }
}

/**
 * @param array $variBoxAnzahl_arr
 * @return bool
 */
function pruefeVariBoxAnzahl($variBoxAnzahl_arr)
{
    if (is_array($variBoxAnzahl_arr) && count($variBoxAnzahl_arr) > 0) {
        $cKeys_arr = array_keys($variBoxAnzahl_arr);
        // Wurde die variBox überhaupt mit einer Anzahl gefüllt?
        $bAnzahlEnthalten = false;
        foreach ($cKeys_arr as $cKeys) {
            if (doubleval($variBoxAnzahl_arr[$cKeys]) > 0) {
                $bAnzahlEnthalten = true;
                break;
            }
        }

        if ($bAnzahlEnthalten) {
            return true;
        }
    }

    return false;
}

/**
 * @param int   $kArtikel
 * @param float $fAnzahl
 * @param array $oEigenschaftwerte_arr
 * @param bool  $cUnique
 * @param int   $kKonfigitem
 */
function fuegeEinInWarenkorbPers($kArtikel, $fAnzahl, $oEigenschaftwerte_arr, $cUnique = false, $kKonfigitem = 0)
{
    // Pruefe ob Kunde eingeloggt
    if (!isset($_SESSION['Kunde']->kKunde)) {
        return;
    }
    $kArtikel = intval($kArtikel);
    // Pruefe Einstellungen fuer persistenten Warenkorb
    $conf = Shop::getSettings(array(CONF_GLOBAL));
    if ($conf['global']['warenkorbpers_nutzen'] === 'Y') {
        // Persistenter Warenkorb
        if ($kArtikel > 0) {
            // Pruefe auf kArtikel
            $oArtikelVorhanden = Shop::DB()->select('tartikel', 'kArtikel', $kArtikel, null, null, null, null, false, 'kArtikel, cName');
            // Falls Artikel vorhanden
            if (isset($oArtikelVorhanden->kArtikel)) {
                // Sichtbarkeit pruefen
                $oSichtbarkeit = Shop::DB()->select('tartikelsichtbarkeit', 'kArtikel', $kArtikel, 'kKundengruppe', intval($_SESSION['Kundengruppe']->kKundengruppe), null, null, false, 'kArtikel');
                if (empty($oSichtbarkeit) || !isset($oSichtbarkeit->kArtikel) || !$oSichtbarkeit->kArtikel) {
                    $oWarenkorbPers = new WarenkorbPers($_SESSION['Kunde']->kKunde);
                    $oWarenkorbPers->fuegeEin($kArtikel, $oArtikelVorhanden->cName, $oEigenschaftwerte_arr, $fAnzahl, $cUnique, $kKonfigitem);
                }
            }
        }
    }
}

/**
 * Gibt den kArtikel von einem Varikombi Kind zurück und braucht dafür Eigenschaften und EigenschaftsWerte
 * Klappt nur bei max. 2 Dimensionen
 *
 * @param int $kArtikel
 * @param int $kEigenschaft0
 * @param int $kEigenschaftWert0
 * @param int $kEigenschaft1
 * @param int $kEigenschaftWert1
 * @return int
 */
function findeKindArtikelZuEigenschaft($kArtikel, $kEigenschaft0, $kEigenschaftWert0, $kEigenschaft1 = 0, $kEigenschaftWert1 = 0)
{
    if ($kEigenschaft0 > 0 && $kEigenschaftWert0 > 0) {
        $cSQLJoin = " JOIN teigenschaftkombiwert ON teigenschaftkombiwert.kEigenschaftKombi = tartikel.kEigenschaftKombi
                        AND teigenschaftkombiwert.kEigenschaft = " . intval($kEigenschaft0) . "
                        AND teigenschaftkombiwert.kEigenschaftWert = " . intval($kEigenschaftWert0);
        $cSQLHaving = '';
        if ($kEigenschaft1 > 0 && $kEigenschaftWert1 > 0) {
            $cSQLJoin = " JOIN teigenschaftkombiwert ON teigenschaftkombiwert.kEigenschaftKombi = tartikel.kEigenschaftKombi
                            AND teigenschaftkombiwert.kEigenschaft IN(" . intval($kEigenschaft0) . ", " . intval($kEigenschaft1) . ")
                            AND teigenschaftkombiwert.kEigenschaftWert IN(" . intval($kEigenschaftWert0) . ", " . intval($kEigenschaftWert1) . ")";

            $cSQLHaving = " HAVING count(*) = 2";
        }
        $oArtikel = Shop::DB()->query(
            "SELECT kArtikel
                FROM tartikel
                " . $cSQLJoin . "
                WHERE tartikel.kVaterArtikel = " . intval($kArtikel) . "
                GROUP BY teigenschaftkombiwert.kEigenschaftKombi" . $cSQLHaving, 1
        );
        if (isset($oArtikel->kArtikel) && count($oArtikel->kArtikel) > 0) {
            return $oArtikel->kArtikel;
        }
    }

    return 0;
}

/**
 * @param Artikel $Artikel
 * @param int     $anzahl
 * @param array   $oEigenschaftwerte_arr
 * @param int     $nGenauigkeit
 * @return array
 */
function pruefeFuegeEinInWarenkorb($Artikel, $anzahl, $oEigenschaftwerte_arr, $nGenauigkeit = 2)
{
    $kArtikel      = $Artikel->kArtikel; // relevant für die Berechnung von Artikelsummen im Warenkorb
    $redirectParam = array();
    $conf          = Shop::getSettings(array(CONF_GLOBAL));

    // Abnahmeintervall
    if ($Artikel->fAbnahmeintervall > 0) {
        $dVielfache = round($Artikel->fAbnahmeintervall * ceil($anzahl / $Artikel->fAbnahmeintervall), $nGenauigkeit);
        if ($dVielfache != $anzahl) {
            $redirectParam[] = R_ARTIKELABNAHMEINTERVALL;
        }
    }
    if (intval($anzahl) != $anzahl && $Artikel->cTeilbar !== 'Y') {
        $anzahl = max(intval($anzahl), 1);
    }
    //mbm
    if ($Artikel->fMindestbestellmenge > $anzahl + $_SESSION['Warenkorb']->gibAnzahlEinesArtikels($kArtikel)) {
        $redirectParam[] = R_MINDESTMENGE;
    }
    //lager beachten
    if ($Artikel->cLagerBeachten === 'Y' &&
        $Artikel->cLagerVariation !== 'Y' &&
        $Artikel->cLagerKleinerNull !== 'Y' &&
        $Artikel->fPackeinheit * ($anzahl + $_SESSION['Warenkorb']->gibAnzahlEinesArtikels($kArtikel)) > $Artikel->fLagerbestand
    ) {
        $redirectParam[] = R_LAGER;
    }
    //darf preise sehen und somit einkaufen?
    if ($_SESSION['Kundengruppe']->darfPreiseSehen != 1 || $_SESSION['Kundengruppe']->darfArtikelKategorienSehen != 1) {
        $redirectParam[] = R_LOGIN;
    }
    //kein vorbestellbares Produkt, aber mit Erscheinungsdatum in Zukunft
    if ($Artikel->nErscheinendesProdukt && $conf['global']['global_erscheinende_kaeuflich'] === 'N') {
        $redirectParam[] = R_VORBESTELLUNG;
    }
    // Die maximale Bestellmenge des Artikels wurde überschritten
    if (isset($Artikel->FunktionsAttribute[FKT_ATTRIBUT_MAXBESTELLMENGE]) && $Artikel->FunktionsAttribute[FKT_ATTRIBUT_MAXBESTELLMENGE] > 0) {
        if ($anzahl > $Artikel->FunktionsAttribute[FKT_ATTRIBUT_MAXBESTELLMENGE] ||
            ($_SESSION['Warenkorb']->gibAnzahlEinesArtikels($kArtikel) + $anzahl) > $Artikel->FunktionsAttribute[FKT_ATTRIBUT_MAXBESTELLMENGE]
        ) {
            $redirectParam[] = R_MAXBESTELLMENGE;
        }
    }
    // Der Artikel ist unverkäuflich
    if (isset($Artikel->FunktionsAttribute[FKT_ATTRIBUT_UNVERKAEUFLICH]) && $Artikel->FunktionsAttribute[FKT_ATTRIBUT_UNVERKAEUFLICH] == 1) {
        $redirectParam[] = R_UNVERKAEUFLICH;
    }
    // Preis auf Anfrage
    if ((!isset($Artikel->Preise->fVKNetto) || $Artikel->Preise->fVKNetto == 0) && $conf['global']['global_preis0'] === 'N') {
        $redirectParam[] = R_AUFANFRAGE;
    }
    // Stücklistenkomponente oder Stückliste und ein Teil ist bereits im Warenkorb?
    $xReturn = pruefeWarenkorbStueckliste($Artikel, $anzahl);
    if ($xReturn !== null) {
        $redirectParam[] = $xReturn;
    }
    if (is_array($Artikel->Variationen) && count($Artikel->Variationen) > 0) {
        //fehlen zu einer Variation werte?
        foreach ($Artikel->Variationen as $var) {
            //min. 1 Problem?
            if (count($redirectParam) > 0) {
                break;
            }
            if ($var->cTyp === 'FREIFELD') {
                continue;
            }
            //schau, ob diese Eigenschaft auch gewählt wurde
            $bEigenschaftWertDa = false;
            foreach ($oEigenschaftwerte_arr as $oEigenschaftwerte) {
                if ($var->cTyp === 'PFLICHT-FREIFELD' && $oEigenschaftwerte->kEigenschaft == $var->kEigenschaft) {
                    if (strlen($oEigenschaftwerte->cFreifeldWert) > 0) {
                        $bEigenschaftWertDa = true;
                    } else {
                        $redirectParam[] = R_VARWAEHLEN;
                        break;
                    }
                } elseif ($var->cTyp !== 'PFLICHT-FREIFELD') {
                    if ($oEigenschaftwerte->kEigenschaft == $var->kEigenschaft) {
                        $bEigenschaftWertDa = true;
                        //schau, ob auch genug davon auf Lager
                        $EigenschaftWert = new EigenschaftWert($oEigenschaftwerte->kEigenschaftWert);
                        //ist der Eigenschaftwert überhaupt gültig?
                        if ($EigenschaftWert->kEigenschaft != $oEigenschaftwerte->kEigenschaft) {
                            $redirectParam[] = R_VARWAEHLEN;
                            break;
                        }
                        //schaue, ob genug auf Lager von jeder var
                        if ($Artikel->cLagerBeachten === 'Y' && $Artikel->cLagerVariation === 'Y' && $Artikel->cLagerKleinerNull !== 'Y') {
                            if ($EigenschaftWert->fPackeinheit == 0) {
                                $EigenschaftWert->fPackeinheit = 1;
                            }
                            if ($EigenschaftWert->fPackeinheit *
                                ($anzahl + $_SESSION['Warenkorb']->gibAnzahlEinerVariation($kArtikel, $EigenschaftWert->kEigenschaftWert)) > $EigenschaftWert->fLagerbestand) {
                                $redirectParam[] = R_LAGERVAR;
                            }
                        }
                        break;
                    }
                }
            }
            if (!$bEigenschaftWertDa) {
                $redirectParam[] = R_VARWAEHLEN;
                break;
            }
        }
    }

    return $redirectParam;
}

/**
 * @param int  $kArtikel
 * @param bool $bSichtbarkeitBeachten
 * @return array
 */
function gibVarKombiEigenschaftsWerte($kArtikel, $bSichtbarkeitBeachten = true)
{
    $oEigenschaftwerte_arr = array();
    $kArtikel              = intval($kArtikel);
    if ($kArtikel > 0) {
        if (ArtikelHelper::isVariChild($kArtikel)) {
            $oArtikel                            = new Artikel();
            $oArtikelOptionen                    = new stdClass();
            $oArtikelOptionen->nMerkmale         = 0;
            $oArtikelOptionen->nAttribute        = 0;
            $oArtikelOptionen->nArtikelAttribute = 0;
            $oArtikelOptionen->nVariationKombi   = 1;

            if (!$bSichtbarkeitBeachten) {
                $oArtikelOptionen->nKeineSichtbarkeitBeachten = 1;
            }

            $oArtikel->fuelleArtikel($kArtikel, $oArtikelOptionen);

            if (isset($oArtikel->oVariationenNurKind_arr) && is_array($oArtikel->oVariationenNurKind_arr) && count($oArtikel->oVariationenNurKind_arr) > 0) {
                foreach ($oArtikel->oVariationenNurKind_arr as $oVariationenNurKind) {
                    $oEigenschaftwerte                       = new stdClass();
                    $oEigenschaftwerte->kEigenschaftWert     = $oVariationenNurKind->Werte[0]->kEigenschaftWert;
                    $oEigenschaftwerte->kEigenschaft         = $oVariationenNurKind->kEigenschaft;
                    $oEigenschaftwerte->cEigenschaftName     = $oVariationenNurKind->cName;
                    $oEigenschaftwerte->cEigenschaftWertName = $oVariationenNurKind->Werte[0]->cName;

                    $oEigenschaftwerte_arr[] = $oEigenschaftwerte;
                }
            }
        }
    }

    return $oEigenschaftwerte_arr;
}

/**
 * @param int    $kArtikel
 * @param int    $anzahl
 * @param string $oEigenschaftwerte_arr
 * @param int    $nWeiterleitung
 * @param bool   $cUnique
 * @param int    $kKonfigitem
 * @param null   $oArtikelOptionen
 * @param bool   $setzePositionsPreise
 * @return bool
 */
function fuegeEinInWarenkorb($kArtikel, $anzahl, $oEigenschaftwerte_arr = '', $nWeiterleitung = 0, $cUnique = false, $kKonfigitem = 0, $oArtikelOptionen = null, $setzePositionsPreise = true)
{
    $kArtikel = intval($kArtikel);

    if ($anzahl > 0 && $kArtikel > 0) {
        $Artikel = new Artikel();
        if ($oArtikelOptionen === null) {
            $oArtikelOptionen = Artikel::getDefaultOptions();
        }
        $Artikel->fuelleArtikel($kArtikel, $oArtikelOptionen);

        // ### 28.10.2010 auskommentiert weil es das $oEigenschaftwerte_arr bei Kindern mit PFLICHT-FREIFELDERN falsch verändert ###
        // Falls der Artikel ein Variationskombikind ist, hole direkt seine Eigenschaften
        //if(isset($Artikel->kEigenschaftKombi) && $Artikel->kEigenschaftKombi > 0)
        //$oEigenschaftwerte_arr = gibVarKombiEigenschaftsWerte($Artikel->kArtikel);

        if (intval($anzahl) != $anzahl && $Artikel->cTeilbar !== 'Y') {
            $anzahl = max(intval($anzahl), 1);
        }

        // Prüfung
        $redirectParam = pruefeFuegeEinInWarenkorb($Artikel, $anzahl, $oEigenschaftwerte_arr);

        if (count($redirectParam) > 0) {
            if (isset($_SESSION['variBoxAnzahl_arr'])) {
                return false;
            }

            if ($nWeiterleitung == 0) {
                if ($Artikel->nIstVater == 1) {
                    header('Location: navi.php?a=' . $Artikel->kArtikel . '&n=' . $anzahl . '&r=' . implode(',', $redirectParam), true, 302);
                } elseif ($Artikel->kEigenschaftKombi > 0) {
                    header('Location: navi.php?a=' . $Artikel->kVaterArtikel . '&a2=' . $Artikel->kArtikel . '&n=' . $anzahl . '&r=' . implode(',', $redirectParam), true, 302);
                } else {
                    header('Location: index.php?a=' . $Artikel->kArtikel . '&n=' . $anzahl . '&r=' . implode(',', $redirectParam), true, 302);
                }
                exit;
            } else {
                return false;
            }
        }
        $_SESSION['Warenkorb']->fuegeEin($kArtikel, $anzahl, $oEigenschaftwerte_arr, 1, $cUnique, $kKonfigitem, $setzePositionsPreise)
                              ->loescheSpezialPos(C_WARENKORBPOS_TYP_VERSANDPOS)
                              ->loescheSpezialPos(C_WARENKORBPOS_TYP_VERSANDZUSCHLAG)
                              ->loescheSpezialPos(C_WARENKORBPOS_TYP_VERSAND_ARTIKELABHAENGIG)
                              ->loescheSpezialPos(C_WARENKORBPOS_TYP_ZAHLUNGSART)
                              ->loescheSpezialPos(C_WARENKORBPOS_TYP_ZINSAUFSCHLAG)
                              ->loescheSpezialPos(C_WARENKORBPOS_TYP_BEARBEITUNGSGEBUEHR)
                              ->loescheSpezialPos(C_WARENKORBPOS_TYP_NEUKUNDENKUPON)
                              ->loescheSpezialPos(C_WARENKORBPOS_TYP_NACHNAHMEGEBUEHR)
                              ->loescheSpezialPos(C_WARENKORBPOS_TYP_TRUSTEDSHOPS);

        resetNeuKundenKupon();
        unset($_SESSION['VersandKupon']);
        unset($_SESSION['Versandart']);
        unset($_SESSION['Zahlungsart']);
        unset($_SESSION['TrustedShops']);
        // Wenn Kupon vorhanden und prozentual auf ganzen Warenkorb,
        // dann verwerfen und neu anlegen
        altenKuponNeuBerechnen();
        setzeLinks();
        // Persistenter Warenkorb
        if (!isset($_POST['login']) && !isset($_REQUEST['basket2Pers'])) {
            fuegeEinInWarenkorbPers($kArtikel, $anzahl, $oEigenschaftwerte_arr, $cUnique, $kKonfigitem);
        }
        // Hinweis
        Shop::Smarty()->assign('hinweis', Shop::Lang()->get('basketAdded', 'messages'))
            ->assign('bWarenkorbHinzugefuegt', true)
            ->assign('bWarenkorbAnzahl', $anzahl);
        // Kampagne
        if (isset($_SESSION['Kampagnenbesucher'])) {
            setzeKampagnenVorgang(KAMPAGNE_DEF_WARENKORB, $kArtikel, $anzahl);
        }
        // Warenkorb weiterleiten
        $_SESSION['Warenkorb']->redirectTo((bool) $nWeiterleitung, $cUnique);
        //prg @todo: validate, make option
        if (defined('PRG') && PRG === true && !empty(Shop::$cCanonicalURL)) {
            //store to session, read again in index.php, then destroy
            $_SESSION['bWarenkorbHinzugefuegt'] = true;
            $_SESSION['bWarenkorbAnzahl']       = $anzahl;
            $_SESSION['hinweis']                = Shop::Lang()->get('basketAdded', 'messages');
            header('Location: ' . Shop::$cCanonicalURL, true, 303);
            exit();
        }

        return true;
    }

    return false;
}

/**
 *
 */
function altenKuponNeuBerechnen()
{
    // Wenn Kupon vorhanden und prozentual auf ganzen Warenkorb, dann verwerfen und neu anlegen
    if (isset($_SESSION['Kupon']) && $_SESSION['Kupon']->cWertTyp === 'prozent' && intval($_SESSION['Kupon']->nGanzenWKRabattieren) === 1) {
        $oKupon = $_SESSION['Kupon'];
        unset($_SESSION['Kupon']);
        $_SESSION['Warenkorb']->setzePositionsPreise();
        require_once PFAD_ROOT . PFAD_INCLUDES . 'bestellvorgang_inc.php';
        kuponAnnehmen($oKupon);
    }
}

/**
 * @param object $oWKPosition
 * @param object $Kupon
 * @return mixed
 */
function checkeKuponWKPos($oWKPosition, $Kupon)
{
    if ($oWKPosition->nPosTyp != C_WARENKORBPOS_TYP_ARTIKEL) {
        return $oWKPosition;
    }
    $Artikel_qry    = " OR cArtikel LIKE '%" . str_replace('%', '\%', Shop::DB()->escape($oWKPosition->Artikel->cArtNr)) . ";%'";
    $Kategorie_qry  = '';
    $Kunden_qry     = '';
    $kKategorie_arr = array();

    if ($oWKPosition->Artikel->kArtikel > 0) {
        if ($oWKPosition->nPosTyp == C_WARENKORBPOS_TYP_ARTIKEL) {
            $kArtikel = intval($oWKPosition->Artikel->kArtikel);
            // Kind?
            if (ArtikelHelper::isVariChild($kArtikel)) {
                $kArtikel = ArtikelHelper::getParent($kArtikel);
            }
            $oKategorie_arr = Shop::DB()->query("SELECT kKategorie FROM tkategorieartikel WHERE kArtikel = " . $kArtikel, 2);
            foreach ($oKategorie_arr as $oKategorie) {
                if (!in_array($oKategorie->kKategorie, $kKategorie_arr)) {
                    $kKategorie_arr[] = intval($oKategorie->kKategorie);
                }
            }
        }
    }
    foreach ($kKategorie_arr as $kKategorie) {
        $Kategorie_qry .= " OR cKategorien LIKE '%;" . $kKategorie . ";%'";
    }
    if (isset($_SESSION['Kunde']->kKunde) && $_SESSION['Kunde']->kKunde > 0) {
        $Kunden_qry = " OR cKunden like '%;" . intval($_SESSION['Kunde']->kKunde) . ";%'";
    }
    $kupons_mgl = Shop::DB()->query(
        "SELECT *
            FROM tkupon
            WHERE cAktiv = 'Y'
                AND dGueltigAb <= now()
                AND (dGueltigBis > now() OR dGueltigBis = '0000-00-00 00:00:00')
                AND fMindestbestellwert <= " . $_SESSION['Warenkorb']->gibGesamtsummeWaren(true, false) . "
                AND (kKundengruppe = -1 OR kKundengruppe = 0 OR kKundengruppe = " . intval($_SESSION['Kundengruppe']->kKundengruppe) . ")
                AND (nVerwendungen = 0 OR nVerwendungen > nVerwendungenBisher)
                AND (cArtikel = '' " . $Artikel_qry . ")
                AND (cKategorien = '' OR cKategorien = '-1' " . $Kategorie_qry . ")
                AND (cKunden = '' OR cKunden = '-1' " . $Kunden_qry . ")
                AND kKupon = " . intval($Kupon->kKupon), 1
    );
    if (isset($kupons_mgl->kKupon) && $kupons_mgl->kKupon > 0 && !$_SESSION['Warenkorb']->posTypEnthalten(C_WARENKORBPOS_TYP_KUPON) && $kupons_mgl->cWertTyp === 'prozent') {
        $oWKPosition->fPreisEinzelNetto -= ($oWKPosition->fPreisEinzelNetto / 100) * $Kupon->fWert;
        $oWKPosition->fPreis -= ($oWKPosition->fPreis / 100) * $Kupon->fWert;
        $oWKPosition->cHinweis = $Kupon->cName . ' (' . str_replace('.', ',', $Kupon->fWert) . '% ' . Shop::Lang()->get('discount', 'global') . ')';

        if (is_array($oWKPosition->WarenkorbPosEigenschaftArr)) {
            foreach ($oWKPosition->WarenkorbPosEigenschaftArr as $oWarenkorbPosEigenschaft) {
                if (isset($oWarenkorbPosEigenschaft->fAufpreis) && floatval($oWarenkorbPosEigenschaft->fAufpreis) > 0) {
                    $oWarenkorbPosEigenschaft->fAufpreis -= (floatval($oWarenkorbPosEigenschaft->fAufpreis) / 100) * $Kupon->fWert;
                }
            }
        }
        if (is_array($_SESSION['Waehrungen'])) {
            foreach ($_SESSION['Waehrungen'] as $Waehrung) {
                $oWKPosition->cGesamtpreisLocalized[0][$Waehrung->cName] = gibPreisStringLocalized(berechneBrutto($oWKPosition->fPreis * $oWKPosition->nAnzahl, gibUst($oWKPosition->kSteuerklasse)), $Waehrung);
                $oWKPosition->cGesamtpreisLocalized[1][$Waehrung->cName] = gibPreisStringLocalized($oWKPosition->fPreis * $oWKPosition->nAnzahl, $Waehrung);
                $oWKPosition->cEinzelpreisLocalized[0][$Waehrung->cName] = gibPreisStringLocalized(berechneBrutto($oWKPosition->fPreis, gibUst($oWKPosition->kSteuerklasse)), $Waehrung);
                $oWKPosition->cEinzelpreisLocalized[1][$Waehrung->cName] = gibPreisStringLocalized($oWKPosition->fPreis, $Waehrung);
            }
        }
    }

    return $oWKPosition;
}

/**
 * @return string
 */
function gibLagerfilter()
{
    $conf = Shop::getSettings(array(CONF_GLOBAL));
    if ($conf['global']['artikel_artikelanzeigefilter'] == EINSTELLUNGEN_ARTIKELANZEIGEFILTER_LAGER) {
        return "AND (NOT (tartikel.fLagerbestand <= 0 AND tartikel.cLagerBeachten = 'Y') OR tartikel.cLagerVariation = 'Y')";
    }
    if ($conf['global']['artikel_artikelanzeigefilter'] == EINSTELLUNGEN_ARTIKELANZEIGEFILTER_LAGERNULL) {
        return "AND (NOT (tartikel.fLagerbestand <= 0 AND tartikel.cLagerBeachten = 'Y') OR tartikel.cLagerKleinerNull = 'Y' OR tartikel.cLagerVariation = 'Y')";
    }

    return '';
}

/**
 * @param array  $data
 * @param string $key
 * @param bool   $bStringToLower
 */
function objectSort(&$data, $key, $bStringToLower = false)
{
    $dataCount = count($data);
    for ($i = $dataCount - 1; $i >= 0; $i--) {
        $swapped = false;
        for ($j = 0; $j < $i; $j++) {
            $dataJ  = $data[$j]->$key;
            $dataJ1 = $data[$j + 1]->$key;
            if ($bStringToLower) {
                $dataJ  = strtolower($dataJ);
                $dataJ1 = strtolower($dataJ1);
            }
            if ($dataJ > $dataJ1) {
                $tmp          = $data[$j];
                $data[$j]     = $data[$j + 1];
                $data[$j + 1] = $tmp;
                $swapped      = true;
            }
        }
        if (!$swapped) {
            return;
        }
    }
}

/**
 * @param string $cPfad
 * @return string
 */
function gibArtikelBildPfad($cPfad)
{
    return (strlen(trim($cPfad)) > 0) ? $cPfad : BILD_KEIN_ARTIKELBILD_VORHANDEN;
}

/**
 * @param array $oVariation_arr
 * @param int   $kEigenschaft
 * @param int   $kEigenschaftWert
 * @return bool|object
 */
function findeVariation($oVariation_arr, $kEigenschaft, $kEigenschaftWert)
{
    foreach ($oVariation_arr as $oVariation) {
        if ($oVariation->kEigenschaft == $kEigenschaft && isset($oVariation->Werte) && is_array($oVariation->Werte) && count($oVariation->Werte) > 0) {
            foreach ($oVariation->Werte as $oWert) {
                if ($oWert->kEigenschaftWert == $kEigenschaftWert) {
                    return $oWert;
                }
            }
        }
    }

    return false;
}

/**
 * @param int|string $steuerland
 */
function setzeSteuersaetze($steuerland = 0)
{
    $_SESSION['Steuersatz'] = array();

    $merchantCountryCode = 'DE';
    if (defined('STEUERSATZ_STANDARD_LAND')) {
        $merchantCountryCode = STEUERSATZ_STANDARD_LAND;
    }
    $Firma = Shop::DB()->query("SELECT cLand FROM tfirma", 1);
    if (!empty($Firma->cLand)) {
        $merchantCountryCode = landISO($Firma->cLand);
    }
    $deliveryCountryCode = $merchantCountryCode;
    if ($steuerland) {
        $deliveryCountryCode = $steuerland;
    }
    if (isset($_SESSION['Kunde']->cLand)) {
        $deliveryCountryCode = $_SESSION['Kunde']->cLand;
        $billingCountryCode  = $_SESSION['Kunde']->cLand;
    }
    if (isset($_SESSION['Lieferadresse']->cLand)) {
        $deliveryCountryCode = $_SESSION['Lieferadresse']->cLand;
    }
    if (!isset($billingCountryCode)) {
        $billingCountryCode = $deliveryCountryCode;
    }
    $_SESSION['Steuerland']     = $deliveryCountryCode;
    $_SESSION['cLieferlandISO'] = $deliveryCountryCode;

    // Pruefen, ob Voraussetzungen fuer innergemeinschaftliche Lieferung (IGL) erfuellt werden #3525
    // Bedingungen fuer Steuerfreiheit bei Lieferung in EU-Ausland:
    // Kunde hat eine zum Rechnungland passende, gueltige USt-ID gesetzt && Firmen-Land != Kunden-Rechnungsland && Firmen-Land != Kunden-Lieferland
    $UstBefreiungIGL = false;
    if (isset($_SESSION['Kunde']->cUSTID) && strlen($_SESSION['Kunde']->cUSTID) > 0 &&
            strcasecmp($billingCountryCode, substr($_SESSION['Kunde']->cUSTID, 0, 2)) === 0 &&
        $merchantCountryCode !== $deliveryCountryCode &&
        $merchantCountryCode !== $billingCountryCode
    ) {
        $deliveryCountry = Shop::DB()->select('tland', 'cISO', $deliveryCountryCode);
        $shopCountry     = Shop::DB()->select('tland', 'cISO', $merchantCountryCode);
        if (!empty($deliveryCountry->nEU) && !empty($shopCountry->nEU)) {
            $UstBefreiungIGL = true;
        }
    }
    $steuerzonen = Shop::DB()->query(
        'SELECT tsteuerzone.kSteuerzone
			FROM tsteuerzone, tsteuerzoneland
			WHERE tsteuerzoneland.cISO = "' . $deliveryCountryCode . '"
			    AND tsteuerzoneland.kSteuerzone = tsteuerzone.kSteuerzone', 2
    );
    $steuerklassen = Shop::DB()->query("SELECT * FROM tsteuerklasse", 2);
    $qry           = '';
    foreach ($steuerzonen as $i => $steuerzone) {
        if ($i == 0) {
            $qry .= " kSteuerzone = " . (int) $steuerzone->kSteuerzone;
        } else {
            $qry .= " OR kSteuerzone = " . (int) $steuerzone->kSteuerzone;
        }
    }
    if (strlen($qry) > 5) {
        foreach ($steuerklassen as $steuerklasse) {
            $steuersatz = Shop::DB()->query("
                SELECT fSteuersatz
                    FROM tsteuersatz
                    WHERE kSteuerklasse = " . intval($steuerklasse->kSteuerklasse) . "
                    AND (" . $qry . ") ORDER BY nPrio DESC", 1
            );
            if (isset($steuersatz->fSteuersatz)) {
                $_SESSION['Steuersatz'][$steuerklasse->kSteuerklasse] = $steuersatz->fSteuersatz;
            } else {
                $_SESSION['Steuersatz'][$steuerklasse->kSteuerklasse] = 0;
            }
            if ($UstBefreiungIGL) {
                $_SESSION['Steuersatz'][$steuerklasse->kSteuerklasse] = 0;
            }
        }
    }
    if (isset($_SESSION['Warenkorb']) && get_class($_SESSION['Warenkorb']) === 'Warenkorb') {
        $_SESSION['Warenkorb']->setzePositionsPreise();
    }
}

/**
 * @param int $kSteuerklasse
 * @return mixed
 */
function gibUst($kSteuerklasse)
{
    if (!isset($_SESSION['Steuersatz']) || !is_array($_SESSION['Steuersatz']) || count($_SESSION['Steuersatz']) === 0) {
        setzeSteuersaetze();
    }
    if (!isset($_SESSION['Steuersatz'][$kSteuerklasse])) {
        if (isset($_SESSION['Steuersatz']) && is_array($_SESSION['Steuersatz'])) {
            $nKey_arr      = array_keys($_SESSION['Steuersatz']);
            $kSteuerklasse = $nKey_arr[0];
        }
    }

    return $_SESSION['Steuersatz'][$kSteuerklasse];
}

/**
 * @param string $cISO
 * @return string
 */
function ISO2land($cISO)
{
    if (strlen($cISO) > 2) {
        return $cISO;
    }
    if (!isset($_SESSION['cISOSprache'])) {
        $oSprache                = gibStandardsprache(true);
        $_SESSION['cISOSprache'] = $oSprache->cISO;
    }
    $cSpalte = ($_SESSION['cISOSprache'] === 'ger') ?
        'cDeutsch' :
        'cEnglisch';
    $land = Shop::DB()->select('tland', 'cISO', $cISO, null, null, null, null, false, $cSpalte);

    return (isset($land->$cSpalte)) ? $land->$cSpalte : $cISO;
}

/**
 * @param string $cLand
 * @return string
 */
function landISO($cLand)
{
    $iso = Shop::DB()->select('tland', 'cDeutsch', $cLand, null, null, null, null, false, 'cISO');
    if (!empty($iso->cISO)) {
        return $iso->cISO;
    }
    $iso = Shop::DB()->select('tland', 'cEnglisch', $cLand, null, null, null, null, false, 'cISO');
    if (!empty($iso->cISO)) {
        return $iso->cISO;
    }

    return 'noISO';
}

/**
 * @param object $obj
 * @param int    $art
 * @param int    $row
 * @param bool   $bForceNonSeo
 * @param bool   $bFull
 * @return string
 */
function baueURL($obj, $art, $row = 0, $bForceNonSeo = false, $bFull = false)
{
    $lang   = ''; // muss umgebaut werden
    $sid    = '';
    $cDatei = 'index.php';
    $prefix = ($bFull === false) ? '' : Shop::getURL() . '/';

    if (!standardspracheAktiv(true)) {
        $lang = '&amp;lang=' . $_SESSION['cISOSprache'];
    }
    if ($bForceNonSeo) {
        $obj->cSeo = '';
    }
    if (!$bForceNonSeo) {
        $cDatei = 'navi.php';
    }
    if ($art && $obj) {
        executeHook(HOOK_TOOLSGLOBAL_INC_SWITCH_BAUEURL, array('obj' => &$obj, 'art' => &$art));
        switch ($art) {
            case URLART_ARTIKEL:
                if (isset($obj->cSeo) && $obj->cSeo && !$row) {
                    return $prefix . $obj->cSeo;
                }

                return $prefix . $cDatei . '?a=' . $obj->kArtikel . $lang . $sid;
                break;
            case URLART_KATEGORIE:
                if (isset($obj->cSeo) && $obj->cSeo && !$row) {
                    return $prefix . $obj->cSeo;
                }

                return $prefix . $cDatei . '?k=' . $obj->kKategorie . $lang . $sid;
                break;
            case URLART_SEITE:
                // Hole aktuelle Spezialseite und gib den URL Dateinamen zurück
                $oSpezialseite = Shop::DB()->query("SELECT cDateiname FROM tspezialseite WHERE nLinkart = " . intval($obj->nLinkart), 1);

                if (isset($oSpezialseite->cDateiname) && strlen($oSpezialseite->cDateiname) > 0) {
                    if ($row) {
                        return $prefix . $oSpezialseite->cDateiname . '?' . SID;
                    }

                    return $prefix . $oSpezialseite->cDateiname;
                }
                if (isset($_SESSION['cISOSprache']) && isset($obj->cLocalizedSeo[$_SESSION['cISOSprache']]) &&
                    strlen($obj->cLocalizedSeo[$_SESSION['cISOSprache']]) && !$row
                ) {
                    return $prefix . $obj->cLocalizedSeo[$_SESSION['cISOSprache']];
                }

                return $prefix . $cDatei . '?s=' . $obj->kLink . $lang . $sid;
                break;
            case URLART_HERSTELLER:
                if (isset($obj->cSeo) && $obj->cSeo && !$row) {
                    return $prefix . $obj->cSeo;
                }

                return $prefix . $cDatei . '?h=' . $obj->kHersteller . $lang . $sid;
                break;
            case URLART_LIVESUCHE:
                if (isset($obj->cSeo) && $obj->cSeo && !$row) {
                    return $prefix . $obj->cSeo;
                }

                return $prefix . $cDatei . '?l=' . $obj->kSuchanfrage . $lang . $sid;
                break;
            case URLART_TAG:
                if (isset($obj->cSeo) && $obj->cSeo && !$row) {
                    return $prefix . $obj->cSeo;
                }

                return $prefix . $cDatei . '?t=' . $obj->kTag . $lang . $sid;
                break;
            case URLART_MERKMAL:
                if (isset($obj->cSeo) && $obj->cSeo && !$row) {
                    return $prefix . $obj->cSeo;
                }

                return $prefix . $cDatei . '?m=' . $obj->kMerkmalWert . $lang . $sid;
                break;
            case URLART_NEWS:
                if (isset($obj->cSeo) && $obj->cSeo && !$row) {
                    return $prefix . $obj->cSeo;
                }

                return $prefix . $cDatei . '?n=' . $obj->kNews . $lang . $sid;
                break;
            case URLART_NEWSMONAT:
                if (isset($obj->cSeo) && $obj->cSeo && !$row) {
                    return $prefix . $obj->cSeo;
                }

                return $prefix . $cDatei . '?nm=' . $obj->kNewsMonatsUebersicht . $lang . $sid;
                break;
            case URLART_NEWSKATEGORIE:
                if (isset($obj->cSeo) && $obj->cSeo && !$row) {
                    return $prefix . $obj->cSeo;
                }

                return $prefix . $cDatei . '?nk=' . $obj->kNewsKategorie . $lang . $sid;
                break;
            case URLART_UMFRAGE:
                if (isset($obj->cSeo) && $obj->cSeo && !$row) {
                    return $prefix . $obj->cSeo;
                }

                return $prefix . $cDatei . '?u=' . $obj->kUmfrage . $lang . $sid;
                break;

            case URLART_SEARCHSPECIALS:
                if (isset($obj->cSeo) && $obj->cSeo && !$row) {
                    return $prefix . $obj->cSeo;
                }

                return $prefix . $cDatei . '?q=' . $obj->kSuchspecial . $lang . $sid;
                break;

        }
    }

    return '';
}

/**
 * @param object $obj
 * @param int    $art
 * @return array
 */
function baueSprachURLS($obj, $art)
{
    $urls   = array();
    $seoobj = null;
    if ($art && $obj && count($_SESSION['Sprachen']) > 0) {
        foreach ($_SESSION['Sprachen'] as $Sprache) {
            if ($Sprache->kSprache != $_SESSION['kSprache']) {
                switch ($art) {
                    case URLART_ARTIKEL :
                        if ($Sprache->cStandard !== 'Y') {
                            $seoobj = Shop::DB()->query(
                                "SELECT tseo.cSeo
                                    FROM tartikelsprache
                                    LEFT JOIN tseo ON tseo.cKey = 'kArtikel'
                                        AND tseo.kKey = tartikelsprache.kArtikel
                                        AND tseo.kSprache = " . intval($Sprache->kSprache) . "
                                    WHERE tartikelsprache.kArtikel = " . intval($obj->kArtikel) . "
                                    AND tartikelsprache.kSprache = " . intval($Sprache->kSprache), 1
                            );
                        } else {
                            $seoobj = Shop::DB()->query(
                                "SELECT tseo.cSeo
                                    FROM tartikel
                                    LEFT JOIN tseo ON tseo.cKey = 'kArtikel'
                                        AND tseo.kKey = tartikel.kArtikel
                                        AND tseo.kSprache = " . intval($Sprache->kSprache) . "
                                    WHERE tartikel.kArtikel = " . intval($obj->kArtikel), 1
                            );
                        }
                        $url = (isset($seoobj->cSeo) && $seoobj->cSeo) ?
                            $seoobj->cSeo :
                            'index.php?a=' . $obj->kArtikel . '&amp;lang=' . $Sprache->cISO;
                        break;

                    case URLART_KATEGORIE :
                        if ($Sprache->cStandard !== 'Y') {
                            $seoobj = Shop::DB()->query(
                                "SELECT tseo.cSeo
                                    FROM tkategoriesprache
                                    LEFT JOIN tseo ON tseo.cKey = 'kKategorie'
                                        AND tseo.kKey = tkategoriesprache.kKategorie
                                        AND tseo.kSprache = " . intval($Sprache->kSprache) . "
                                        WHERE tkategoriesprache.kKategorie = " . intval($obj->kKategorie) . "
                                    AND tkategoriesprache.kSprache = " . intval($Sprache->kSprache), 1
                            );
                        } else {
                            $seoobj = Shop::DB()->query(
                                "SELECT tseo.cSeo
                                    FROM tkategorie
                                    LEFT JOIN tseo ON tseo.cKey = 'kKategorie'
                                        AND tseo.kKey = tkategorie.kKategorie
                                        AND tseo.kSprache = " . intval($Sprache->kSprache) . "
                                    WHERE tkategorie.kKategorie = " . intval($obj->kKategorie), 1
                            );
                        }
                        $url = (isset($seoobj->cSeo)) ?
                            $seoobj->cSeo :
                            'index.php?k=' . $obj->kKategorie . '&amp;lang=' . $Sprache->cISO;
                        break;

                    case URLART_SEITE:
                        $seoobj = Shop::DB()->query(
                            "SELECT tseo.cSeo
                                FROM tlinksprache
                                LEFT JOIN tseo ON tseo.cKey = 'kLink'
                                    AND tseo.kKey = tlinksprache.kLink
                                    AND tseo.kSprache = " . intval($Sprache->kSprache) . "
                                WHERE tlinksprache.kLink = " . intval($obj->kLink) . "
                                    AND tlinksprache.cISOSprache = '" . $Sprache->cISO . "'", 1
                        );
                        $url = (isset($seoobj->cSeo) && $seoobj->cSeo) ?
                            $seoobj->cSeo :
                            'index.php?s=' . $obj->kLink . '&amp;lang=' . $Sprache->cISO;
                        break;

                    default :
                        $url = $obj . '&amp;lang=' . $Sprache->cISO;
                        break;

                }
                $urls[$Sprache->cISO] = $url;
            }
        }
    }

    return $urls;
}

/**
 * @param string $lang
 */
function checkeSpracheWaehrung($lang = '')
{
    if (strlen($lang) > 0) {
        //Kategorien zurücksetzen, da sie lokalisiert abgelegt wurden
        if ($lang != $_SESSION['cISOSprache']) {
            $_SESSION['oKategorie_arr']     = array();
            $_SESSION['oKategorie_arr_new'] = array();
        }
        $bSpracheDa = false;
        $Sprachen   = gibAlleSprachen();
        foreach ($Sprachen as $Sprache) {
            if ($Sprache->cISO == $lang) {
                $_SESSION['cISOSprache'] = $Sprache->cISO;
                $_SESSION['kSprache']    = $Sprache->kSprache;
                Shop::setLanguage($Sprache->kSprache, $Sprache->cISO);
                unset($_SESSION['Suche']);
                $bSpracheDa = true;
                setzeLinks();
                if (isset($_SESSION['Wunschliste'])) {
                    $_SESSION['Wunschliste']->umgebungsWechsel();
                }
                if (isset($_SESSION['Vergleichsliste'])) {
                    $_SESSION['Vergleichsliste']->umgebungsWechsel();
                }
                $_SESSION['currentLanguage'] = clone $Sprache;
                unset($_SESSION['currentLanguage']->cURL);
            }
        }
        if (!$bSpracheDa) { //lang mitgegeben, aber nicht mehr in db vorhanden -> alter Sprachlink
            $kArtikel              = intval(verifyGPDataString('a'));
            $kKategorie            = intval(verifyGPDataString('k'));
            $kSeite                = intval(verifyGPDataString('s'));
            $kVariKindArtikel      = verifyGPCDataInteger('a2');
            $kHersteller           = verifyGPCDataInteger('h');
            $kSuchanfrage          = verifyGPCDataInteger('l');
            $kMerkmalWert          = verifyGPCDataInteger('m');
            $kTag                  = verifyGPCDataInteger('t');
            $kSuchspecial          = verifyGPCDataInteger('q');
            $kNews                 = verifyGPCDataInteger('n');
            $kNewsMonatsUebersicht = verifyGPCDataInteger('nm');
            $kNewsKategorie        = verifyGPCDataInteger('nk');
            $kUmfrage              = verifyGPCDataInteger('u');
            $cSeo                  = '';
            //redirect per 301
            header('HTTP/1.1 301 Moved Permanently');
            if ($kArtikel > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kArtikel'
                            AND tseo.kKey = " . $kArtikel . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kKategorie > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kKategorie'
                            AND tseo.kKey = " . $kKategorie . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kSeite > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kLink'
                            AND tseo.kKey = " . $kSeite . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kVariKindArtikel > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kArtikel'
                            AND tseo.kKey = " . $kVariKindArtikel . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kHersteller > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kHersteller'
                            AND tseo.kKey = " . $kHersteller . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kSuchanfrage > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kSuchanfrage'
                            AND tseo.kKey = " . $kSuchanfrage . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kMerkmalWert > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kMerkmalWert'
                            AND tseo.kKey = " . $kMerkmalWert . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kTag > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kTag'
                            AND tseo.kKey = " . $kTag . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kSuchspecial > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kSuchspecial'
                            AND tseo.kKey = " . $kSuchspecial . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kNews > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kNews'
                            AND tseo.kKey = " . $kNews . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kNewsMonatsUebersicht > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kNewsMonatsUebersicht'
                            AND tseo.kKey = " . $kNewsMonatsUebersicht . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kNewsKategorie > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kNewsKategorie'
                            AND tseo.kKey = " . $kNewsKategorie . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            } elseif ($kUmfrage > 0) {
                $dbRes = Shop::DB()->query(
                    "SELECT tseo.cSeo
                        FROM tseo
                        WHERE tseo.cKey = 'kUmfrage'
                            AND tseo.kKey = " . $kUmfrage . "
                            AND tseo.kSprache = " . intval($_SESSION['kSprache']), 1
                );
                $cSeo = $dbRes->cSeo;
            }
            header('Location: ' . Shop::getURL() . '/' . $cSeo, true, 301);
            exit;
        }
    }

    $waehrung = verifyGPDataString('curr');
    if ($waehrung) {
        $Waehrungen = Shop::DB()->query("SELECT * FROM twaehrung", 2);
        foreach ($Waehrungen as $Waehrung) {
            if ($Waehrung->cISO == $waehrung) {
                setFsession($Waehrung->kWaehrung, 0, 0);
                memberCopy($Waehrung, $_SESSION['Waehrung']);
                $_SESSION['cWaehrungName'] = $Waehrung->cName;

                if (isset($_SESSION['Wunschliste'])) {
                    $_SESSION['Wunschliste']->umgebungsWechsel();
                }
                if (isset($_SESSION['Vergleichsliste'])) {
                    $_SESSION['Vergleichsliste']->umgebungsWechsel();
                }
                // Trusted Shops Kaeuferschutz raus falls vorhanden
                unset($_SESSION['TrustedShops']);
                if (isset($_SESSION['Warenkorb'])) {
                    $_SESSION['Warenkorb']->loescheSpezialPos(C_WARENKORBPOS_TYP_TRUSTEDSHOPS);
                }
                if ($_SESSION['Warenkorb'] && count($_SESSION['Warenkorb']->PositionenArr) > 0) {
                    $_SESSION['Warenkorb']->setzePositionsPreise();
                }
            }
        }
    }
    Shop::Lang()->autoload();
    // Suchspecialoverlays
    $GLOBALS['oSuchspecialoverlay_arr'] = holeAlleSuchspecialOverlays($_SESSION['kSprache']);
}

/**
 * @param int $nKategorieBox
 * @return array
 */
function gibAlleKategorienNoHTML($nKategorieBox = 0)
{
    $oKategorienNoHTML_arr = array();
    $nTiefe                = 0;

    if (intval(K_KATEGORIE_TIEFE) > 0) {
        $oKategorien = new KategorieListe();
        $oKategorien->getAllCategoriesOnLevel(0);
        foreach ($oKategorien->elemente as $oKategorie) {
            //Kategoriebox Filter
            if ($nKategorieBox > 0 && $nTiefe == 0) {
                if ($oKategorie->KategorieAttribute[KAT_ATTRIBUT_KATEGORIEBOX] != $nKategorieBox) {
                    continue;
                }
            }

            unset($oKategorienNoHTML);
            $oKategorienNoHTML = $oKategorie;
            unset($oKategorienNoHTML->Unterkategorien);
            $oKategorienNoHTML->oUnterKat_arr               = array();
            $oKategorienNoHTML_arr[$oKategorie->kKategorie] = $oKategorienNoHTML;
            //nur wenn unterkategorien enthalten sind!
            if (intval(K_KATEGORIE_TIEFE) > 1) {
                $oAktKategorie = new Kategorie($oKategorie->kKategorie);
                if ($oAktKategorie->bUnterKategorien) {
                    $nTiefe           = 1;
                    $oUnterKategorien = new KategorieListe();
                    $oUnterKategorien->getAllCategoriesOnLevel($oAktKategorie->kKategorie);
                    foreach ($oUnterKategorien->elemente as $oUKategorie) {
                        unset($oKategorienNoHTML);
                        $oKategorienNoHTML = $oUKategorie;
                        unset($oKategorienNoHTML->Unterkategorien);
                        $oKategorienNoHTML->oUnterKat_arr                                                        = array();
                        $oKategorienNoHTML_arr[$oKategorie->kKategorie]->oUnterKat_arr[$oUKategorie->kKategorie] = $oKategorienNoHTML;

                        if (intval(K_KATEGORIE_TIEFE) > 2) {
                            $nTiefe                = 2;
                            $oUnterUnterKategorien = new KategorieListe();
                            $oUnterUnterKategorien->getAllCategoriesOnLevel($oUKategorie->kKategorie);
                            foreach ($oUnterUnterKategorien->elemente as $oUUKategorie) {
                                unset($oKategorienNoHTML);
                                $oKategorienNoHTML = $oUUKategorie;
                                unset($oKategorienNoHTML->Unterkategorien);
                                $oKategorienNoHTML_arr[$oKategorie->kKategorie]->oUnterKat_arr[$oUKategorie->kKategorie]->oUnterKat_arr[$oUUKategorie->kKategorie] = $oKategorienNoHTML;
                            }
                        }
                    }
                }
            }
        }
    }

    return $oKategorienNoHTML_arr;
}

/**
 * @param object $src
 * @param object $dest
 */
function memberCopy($src, &$dest)
{
    if ($dest === null) {
        $dest = new stdClass();
    }
    $arr = get_object_vars($src);
    if (is_array($arr)) {
        $keys = array_keys($arr);
        if (is_array($keys)) {
            foreach ($keys as $key) {
                if (!is_object($src->$key) && !is_array($src->$key)) {
                    $dest->$key = $src->$key;
                }
            }
        }
    }
}

/**
 * @param int $kWaehrung
 * @param int $ArtSort
 * @param int $ArtZahl
 * @return bool
 */
function setFsession($kWaehrung, $ArtSort, $ArtZahl)
{
    if (isset($_SERVER['HTTP_COOKIE']) && $_SERVER['HTTP_COOKIE']) {
        return false;
    }
    $fsess     = Shop::DB()->select('tfsession', 'cIP', gibIP(), 'cAgent', $_SERVER['HTTP_USER_AGENT']);
    $kWaehrung = (int) $kWaehrung;
    if (!empty($fsess->cIP)) {
        if ($kWaehrung) {
            $_upd            = new stdClass();
            $_upd->kWaehrung = $kWaehrung;
            Shop::DB()->update('tfsession', array('cIP', 'cAgent'), array(gibIP(), $_SERVER['HTTP_USER_AGENT']), $_upd);
        } elseif ($ArtSort) {
            $_upd                  = new stdClass();
            $_upd->nUserSortierung = $ArtSort;
            Shop::DB()->update('tfsession', array('cIP', 'cAgent'), array(gibIP(), $_SERVER['HTTP_USER_AGENT']), $_upd);
        } elseif ($ArtZahl) {
            $_upd                   = new stdClass();
            $_upd->nUserArtikelzahl = $ArtZahl;
            Shop::DB()->update('tfsession', array('cIP', 'cAgent'), array(gibIP(), $_SERVER['HTTP_USER_AGENT']), $_upd);
        }
    } else {
        $fs                   = new stdClass();
        $fs->cIP              = gibIP();
        $fs->cAgent           = $_SERVER['HTTP_USER_AGENT'];
        $fs->kWaehrung        = $kWaehrung;
        $fs->nUserSortierung  = $ArtSort;
        $fs->nUserArtikelzahl = $ArtZahl;
        $fs->dErstellt        = 'now()';
        Shop::DB()->insert('tfsession', $fs);
    }

    return true;
}

/**
 * @return bool
 */
function getFsession()
{
    if (isset($_SERVER['HTTP_COOKIE']) || !isset($_SERVER['HTTP_USER_AGENT'])) {
        return false;
    }
    $fsess = Shop::DB()->select('tfsession', 'cIP', gibIP(), 'cAgent', $_SERVER['HTTP_USER_AGENT']);
    if (isset($fsess->cIP)) {
        if ($fsess->nUserArtikelzahl) {
            $_SESSION['ArtikelProSeite'] = $fsess->nUserArtikelzahl;
        }
        if ($fsess->nUserSortierung) {
            $_SESSION['Usersortierung'] = $fsess->nUserSortierung;
        }
        if ($fsess->kWaehrung) {
            $Waehrung = Shop::DB()->select('twaehrung', 'kWaehrung', $fsess->kWaehrung);
            if (!empty($Waehrung->kWaehrung)) {
                $_SESSION['Waehrung']      = $Waehrung;
                $_SESSION['cWaehrungName'] = $Waehrung->cName;
            }
        }
    }
    if (time() % 10 === 0) {
        Shop::DB()->query(
            'DELETE
				FROM tfsession
				WHERE date_sub(now(), INTERVAL 60 MINUTE)>dErstellt', 4
        );
    }

    return true;
}

/**
 * @return null|stdClass
 */
function setzeLinks()
{
    $linkHelper                    = LinkHelper::getInstance();
    $linkGroups                    = $linkHelper->getLinkGroups();
    $_SESSION['Link_Datenschutz']  = $linkGroups->Link_Datenschutz;
    $_SESSION['Link_AGB']          = $linkGroups->Link_AGB;
    $_SESSION['Link_Versandseite'] = $linkGroups->Link_Versandseite;
    executeHook(HOOK_TOOLSGLOBAL_INC_SETZELINKS);

    return $linkGroups;
}

/**
 * @param bool      $bShop
 * @param int| null $kSprache - optional lang id to check against instead of session value
 * @return bool
 */
function standardspracheAktiv($bShop = false, $kSprache = null)
{
    if ($kSprache === null && !isset($_SESSION['kSprache'])) {
        return true;
    }
    $langToCheckAgainst = ($kSprache !== null) ? intval($kSprache) : $_SESSION['kSprache'];
    if (isset($_SESSION['Sprachen']) && is_array($_SESSION['Sprachen']) && $langToCheckAgainst > 0) {
        foreach ($_SESSION['Sprachen'] as $Sprache) {
            if ($Sprache->cStandard === 'Y' && $Sprache->kSprache == $langToCheckAgainst && !$bShop) {
                return true;
            }
            if ($Sprache->cShopStandard === 'Y' && $Sprache->kSprache == $langToCheckAgainst && $bShop) {
                return true;
            }
        }
    } else {
        return true;
    }

    return false;
}

/**
 * @param bool $bShop
 * @return mixed
 */
function gibStandardsprache($bShop = false)
{
    if (isset($_SESSION['Sprachen']) && is_array($_SESSION['Sprachen'])) {
        foreach ($_SESSION['Sprachen'] as $Sprache) {
            if ($Sprache->cStandard === 'Y' && !$bShop) {
                return $Sprache;
            }
            if ($Sprache->cShopStandard === 'Y' && $bShop) {
                return $Sprache;
            }
        }
    }

    $cacheID = 'shop_lang_' . (($bShop === true) ? 'b' : '');
    if (($lang = Shop::Cache()->get($cacheID)) !== false) {
        return $lang;
    }
    $row = ($bShop) ?
        'cShopStandard' :
        'cStandard';
    $lang = Shop::DB()->select('tsprache', $row, 'Y');
    Shop::Cache()->set($cacheID, $lang, array(CACHING_GROUP_LANGUAGE));

    return $lang;
}

/**
 * @param bool $bISO
 * @return mixed
 */
function gibStandardWaehrung($bISO = false)
{
    if (isset($_SESSION['Waehrung']) && $_SESSION['Waehrung']->kWaehrung > 0) {
        return $bISO === true ? $_SESSION['Waehrung']->cISO : $_SESSION['Waehrung']->kWaehrung;
    }
    $oWaehrung = Shop::DB()->query("SELECT kWaehrung, cISO FROM twaehrung WHERE cStandard = 'Y'", 1);

    return ($bISO === true) ? $oWaehrung->cISO : $oWaehrung->kWaehrung;
}

/**
 * @param array $Positionen
 * @param int   $Nettopreise
 * @param int   $htmlWaehrung
 * @return array
 */
function gibAlteSteuerpositionen($Positionen, $Nettopreise = -1, $htmlWaehrung = 1)
{
    if ($Nettopreise == -1) {
        $Nettopreise = $_SESSION['NettoPreise'];
    }
    $steuersatz = array();
    $steuerpos  = array();
    $conf       = Shop::getSettings(array(CONF_GLOBAL));
    if ($conf['global']['global_steuerpos_anzeigen'] === 'N') {
        return $steuerpos;
    }
    foreach ($Positionen as $position) {
        if ($position->fMwSt > 0) {
            if (!in_array($position->fMwSt, $steuersatz)) {
                $steuersatz[] = $position->fMwSt;
            }
        }
    }
    sort($steuersatz);
    foreach ($Positionen as $position) {
        if ($position->fMwSt > 0) {
            $i = array_search($position->fMwSt, $steuersatz);

            if (!isset($steuerpos[$i]->fBetrag) || !$steuerpos[$i]->fBetrag) {
                $steuerpos[$i]                  = new stdClass();
                $steuerpos[$i]->cName           = lang_steuerposition($position->fMwSt, $Nettopreise);
                $steuerpos[$i]->fUst            = $position->fMwSt;
                $steuerpos[$i]->fBetrag         = ($position->fPreis * $position->nAnzahl * $position->fMwSt) / 100.0;
                $steuerpos[$i]->cPreisLocalized = gibPreisStringLocalized($steuerpos[$i]->fBetrag, 0, $htmlWaehrung);
            } else {
                $steuerpos[$i]->fBetrag += ($position->fPreis * $position->nAnzahl * $position->fMwSt) / 100.0;
                $steuerpos[$i]->cPreisLocalized = gibPreisStringLocalized($steuerpos[$i]->fBetrag, 0, $htmlWaehrung);
            }
        }
    }

    return $steuerpos;
}

/**
 * @param string $email
 * @return bool
 */
function valid_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * @param string  $lieferland
 * @param string  $versandklassen
 * @param int     $kKundengruppe
 * @param Artikel $oArtikel
 * @param bool    $checkProductDepedency
 * @return mixed
 */
function gibGuenstigsteVersandart($lieferland, $versandklassen, $kKundengruppe, $oArtikel, $checkProductDepedency = true)
{
    $minVersand               = 10000;
    $cISO                     = $lieferland;
    $cNurAbhaengigeVersandart = ($checkProductDepedency && VersandartHelper::normalerArtikelversand($lieferland) === false) ?
        'Y' :
        'N';

    $versandarten = Shop::DB()->query(
        "SELECT *
            FROM tversandart
            WHERE cNurAbhaengigeVersandart = '" . $cNurAbhaengigeVersandart . "'
                AND cLaender LIKE '%" . $cISO . "%'
                AND (cVersandklassen = '-1' OR (cVersandklassen LIKE '% " . $versandklassen . " %' OR cVersandklassen LIKE '% " . $versandklassen . "'))
                AND (cKundengruppen = '-1' OR cKundengruppen LIKE '%;" . $kKundengruppe . ";%') ORDER BY nSort", 2
    );

    $cnt                    = count($versandarten);
    $nGuenstigsteVersandart = 0;
    for ($i = 0; $i < $cnt; $i++) {
        $versandarten[$i]->fEndpreis = berechneVersandpreis($versandarten[$i], $cISO, $oArtikel);
        if ($versandarten[$i]->fEndpreis == -1) {
            unset($versandarten[$i]);
            continue;
        }
        if ($versandarten[$i]->fEndpreis < $minVersand) {
            $minVersand             = $versandarten[$i]->fEndpreis;
            $nGuenstigsteVersandart = $i;
        }
    }

    return $versandarten[$nGuenstigsteVersandart];
}

/**
 * @param int $kKundengruppe
 * @return array
 */
function gibMoeglicheVerpackungen($kKundengruppe)
{
    $fSummeWarenkorb = $_SESSION['Warenkorb']->gibGesamtsummeWarenExt(array(C_WARENKORBPOS_TYP_ARTIKEL), true);
    $oVerpackung_arr = Shop::DB()->query(
        "SELECT * FROM tverpackung
			JOIN tverpackungsprache ON tverpackung.kVerpackung = tverpackungsprache.kVerpackung
			WHERE tverpackungsprache.cISOSprache = '" . $_SESSION['cISOSprache'] . "'
			AND (tverpackung.cKundengruppe = '-1' OR tverpackung.cKundengruppe LIKE '%;" . intval($kKundengruppe) . ";%')
			AND " . $fSummeWarenkorb . " >= tverpackung.fMindestbestellwert
			AND tverpackung.nAktiv = 1
			ORDER BY tverpackung.kVerpackung", 2
    );
    // Array bearbeiten
    if ($oVerpackung_arr !== false && count($oVerpackung_arr) > 0) {
        foreach ($oVerpackung_arr as $i => $oVerpackung) {
            $oVerpackung_arr[$i]->nKostenfrei = 0;
            if ($fSummeWarenkorb >= $oVerpackung->fKostenfrei && $oVerpackung->fBrutto > 0 && $oVerpackung->fKostenfrei != 0) {
                $oVerpackung_arr[$i]->nKostenfrei = 1;
            }
            $oVerpackung_arr[$i]->fBruttoLocalized = gibPreisStringLocalized($oVerpackung_arr[$i]->fBrutto, $_SESSION['Waehrung']->kWaehrung);
        }
    } else {
        $oVerpackung_arr = array();
    }

    return $oVerpackung_arr;
}

/**
 * @param Versandart $versandart
 * @param string     $cISO
 * @param string     $plz
 * @return object|null
 */
function gibVersandZuschlag($versandart, $cISO, $plz)
{
    $versandzuschlaege = Shop::DB()->query("
        SELECT * 
          FROM tversandzuschlag 
          WHERE kVersandart = " . intval($versandart->kVersandart) . " AND cISO = '" . $cISO . "'", 2
    );
    foreach ($versandzuschlaege as $versandzuschlag) {
        //ist plz enthalten?
        $plz_x = Shop::DB()->query(
            'SELECT * FROM tversandzuschlagplz
				WHERE ((cPLZAb <= "' . $plz . '" AND cPLZBis >= "' . $plz . '") OR cPLZ = "' . $plz . '") AND kVersandzuschlag = ' . intval($versandzuschlag->kVersandzuschlag), 1
        );
        if (isset($plz_x->kVersandzuschlagPlz) && $plz_x->kVersandzuschlagPlz > 0) {
            //posname lokalisiert ablegen
            $versandzuschlag->angezeigterName = array();
            foreach ($_SESSION['Sprachen'] as $Sprache) {
                $name_spr = Shop::DB()->query(
                    'SELECT cName
						FROM tversandzuschlagsprache
						WHERE kVersandzuschlag = ' . intval($versandzuschlag->kVersandzuschlag) . ' AND cISOSprache = "' . $Sprache->cISO . '"', 1
                );
                $versandzuschlag->angezeigterName[$Sprache->cISO] = $name_spr->cName;
            }
            $versandzuschlag->cPreisLocalized = gibPreisStringLocalized($versandzuschlag->fZuschlag);

            return $versandzuschlag;
        }
    }

    return;
}

/**
 * @todo Hier gilt noch zu beachten, dass fWarenwertNetto vom Zusatzartikel kein Netto sein darf, sondern der Preis muss in Brutto angegeben werden.
 * @param Versandart  $versandart
 * @param String      $cISO
 * @param Artikel     $oZusatzArtikel
 * @param Artikel|int $Artikel
 * @return int
 */
function berechneVersandpreis($versandart, $cISO = '', $oZusatzArtikel, $Artikel = 0)
{
    $fSteuersatz = 0.0;
    if (!isset($oZusatzArtikel->fAnzahl)) {
        if (!isset($oZusatzArtikel)) {
            $oZusatzArtikel = new stdClass();
        }
        $oZusatzArtikel->fAnzahl         = 0;
        $oZusatzArtikel->fWarenwertNetto = 0;
        $oZusatzArtikel->fGewicht        = 0;
    }

    $versandberechnung = Shop::DB()->select('tversandberechnung', 'kVersandberechnung', $versandart->kVersandberechnung);
    $preis             = 0;
    switch ($versandberechnung->cModulId) {
        case 'vm_versandkosten_pauschale_jtl' :
            $preis = $versandart->fPreis * ((100 + $fSteuersatz) / 100);
            break;

        case 'vm_versandberechnung_gewicht_jtl':
            $warenkorbgewicht = ($Artikel) ? $Artikel->fGewicht : $_SESSION['Warenkorb']->getWeight();
            $warenkorbgewicht += $oZusatzArtikel->fGewicht;
            $versand = Shop::DB()->query("SELECT * FROM tversandartstaffel WHERE kVersandart = " . intval($versandart->kVersandart) . " AND fBis >= " . $warenkorbgewicht . " ORDER BY fBis ASC", 1);
            if (isset($versand->kVersandartStaffel)) {
                $preis = $versand->fPreis * ((100 + $fSteuersatz) / 100);
            } else {
                return -1;
            }
            break;

        case 'vm_versandberechnung_warenwert_jtl' :
            $warenkorbwert = ($Artikel) ? $Artikel->Preise->fVKNetto : $_SESSION['Warenkorb']->gibGesamtsummeWarenExt(array(C_WARENKORBPOS_TYP_ARTIKEL), true);
            $warenkorbwert += $oZusatzArtikel->fWarenwertNetto;
            $versand = Shop::DB()->query("SELECT * FROM tversandartstaffel WHERE kVersandart = " . intval($versandart->kVersandart) . " AND fBis >= " . $warenkorbwert . " ORDER BY fBis ASC", 1);
            if (isset($versand->kVersandartStaffel)) {
                $preis = $versand->fPreis * ((100 + $fSteuersatz) / 100);
            } else {
                return -1;
            }
            break;

        case 'vm_versandberechnung_artikelanzahl_jtl' :
            $artikelanzahl = 1;
            if (!$Artikel) {
                $artikelanzahl = (isset($_SESSION['Warenkorb'])) ? $_SESSION['Warenkorb']->gibAnzahlArtikelExt(array(C_WARENKORBPOS_TYP_ARTIKEL)) : 0;
            }

            $artikelanzahl += $oZusatzArtikel->fAnzahl;
            $versand = Shop::DB()->query("SELECT * FROM tversandartstaffel WHERE kVersandart = " . intval($versandart->kVersandart) . " AND fBis >= " . $artikelanzahl . " ORDER BY fBis ASC", 1);
            if (isset($versand->kVersandartStaffel)) {
                $preis = $versand->fPreis * ((100 + $fSteuersatz) / 100);
            } else {
                return -1;
            }
            break;

        default :
            //bearbeite fremdmodule
            break;
    }
    //Deckelung?
    if ($preis >= $versandart->fDeckelung && $versandart->fDeckelung > 0) {
        $preis = $versandart->fDeckelung;
    }
    //Zuschlag
    if (isset($versandart->Zuschlag->fZuschlag) && $versandart->Zuschlag->fZuschlag != 0) {
        $preis += $versandart->Zuschlag->fZuschlag;
    }
    //versandkostenfrei?
    $fArtikelPreis = 0;
    if ($Artikel) {
        switch ($versandart->eSteuer) {
            case 'netto' :
                $fArtikelPreis = $Artikel->Preise->fVKNetto;
                break;

            case 'brutto' :
                $fArtikelPreis = berechneBrutto($Artikel->Preise->fVKNetto, gibUst($Artikel->kSteuerklasse));
                break;

        }
    }
    if ($Artikel && $fArtikelPreis >= $versandart->fVersandkostenfreiAbX && $versandart->fVersandkostenfreiAbX > 0) {
        $preis = 0;
    } elseif (isset($_SESSION['Warenkorb']) &&
        ($_SESSION['Warenkorb']->gibGesamtsummeWarenExt(array(C_WARENKORBPOS_TYP_ARTIKEL), 1)
            + $_SESSION['Warenkorb']->gibGesamtsummeWarenExt(array(C_WARENKORBPOS_TYP_KUPON), 1))
        >= $versandart->fVersandkostenfreiAbX &&
        $versandart->fVersandkostenfreiAbX > 0
    ) {
        $preis = 0;
    }
    executeHook(HOOK_TOOLSGLOBAL_INC_BERECHNEVERSANDPREIS, array('fPreis' => &$preis));

    return $preis;
}

/**
 * @param string  $cISO
 * @param Artikel $Artikel
 * @param int     $barzahlungZulassen
 * @param int     $kKundengruppe
 * @return int
 */
function gibGuenstigsteVersandkosten($cISO, $Artikel, $barzahlungZulassen = 1, $kKundengruppe)
{
    $versandpreis = 99999;
    $versandarten = Shop::DB()->query(
        "SELECT *
            FROM tversandart
            WHERE cLaender LIKE '%" . $cISO . "%'
                AND (cVersandklassen='-1' OR (cVersandklassen LIKE '% " . $Artikel->kVersandklasse . " %' OR cVersandklassen LIKE '% " . $Artikel->kVersandklasse . "'))
                AND (cKundengruppen='-1' OR cKundengruppen LIKE '%;" . $kKundengruppe . ";%')", 2
    );
    $cnt = count($versandarten);
    for ($i = 0; $i < $cnt; $i++) {
        if (!$barzahlungZulassen) {
            $za_bar = Shop::DB()->query("SELECT * FROM tversandartzahlungsart WHERE kZahlungsart=6 AND kVersandart = " . intval($versandarten[$i]->kVersandart), 1);
            if (isset($za_bar->kVersandartZahlungsart) && $za_bar->kVersandartZahlungsart > 0) {
                continue;
            }
        }
        $vp = berechneVersandpreis($versandarten[$i], $cISO, null, $Artikel);
        if ($vp != -1 && $vp < $versandpreis) {
            $versandpreis = $vp;
        }
        if ($vp == 0) {
            break;
        }
    }

    return ($versandpreis === 99999) ? -1 : $versandpreis;
}

/**
 * @param int  $kKundengruppe
 * @param bool $bIgnoreSetting
 * @param bool $bForceAll
 * @return array
 */
function gibBelieferbareLaender($kKundengruppe = 0, $bIgnoreSetting = false, $bForceAll = false)
{
    if (empty($kKundengruppe)) {
        $kKundengruppe = Kundengruppe::getDefaultGroupID();
    }
    $sprache = Shop::DB()->select('tsprache', 'kSprache', intval($_SESSION['kSprache']));
    $sel_var = 'cDeutsch';
    $conf    = Shop::getSettings(array(CONF_KUNDEN));
    if (strtolower($sprache->cNameEnglisch) !== 'german') {
        $sel_var = 'cEnglisch';
    }
    if (!$bForceAll && ($conf['kunden']['kundenregistrierung_nur_lieferlaender'] === 'Y' || $bIgnoreSetting)) {
        $laender_arr = array();
        $ll_obj_arr  = Shop::DB()->query("SELECT cLaender FROM tversandart WHERE (cKundengruppen='-1' OR cKundengruppen LIKE '%;" . $kKundengruppe . ";%')", 2);
        $where       = '';
        foreach ($ll_obj_arr as $cLaender) {
            $pcs = explode(' ', $cLaender->cLaender);
            foreach ($pcs as $i => $land) {
                if ($land && !in_array($land, $laender_arr)) {
                    if (count($laender_arr) > 0) {
                        $where .= ' OR ';
                    }
                    $laender_arr[] = $land;
                    $where .= ' cISO = "' . $land . '"';
                }
            }
        }
        $laender = Shop::DB()->query("SELECT cISO, $sel_var AS cName FROM tland WHERE $where ORDER BY $sel_var", 2);
    } else {
        $laender = Shop::DB()->query("SELECT cISO, $sel_var AS cName FROM tland ORDER BY $sel_var", 2);
    }
    if (is_array($laender)) {
        usort(
            $laender, function ($a, $b) {
                $a = mb_convert_case($a->cName, MB_CASE_LOWER, 'iso-8859-1');
                $b = mb_convert_case($b->cName, MB_CASE_LOWER, 'iso-8859-1');
                $a = str_replace(array('ä', 'ü', 'ö', 'ss', utf8_decode('ä'), utf8_decode('ü'), utf8_decode('ö')), array('a', 'u', 'o', 'ß', 'a', 'u', 'o', 'ß'), $a);
                $b = str_replace(array('ä', 'ü', 'ö', 'ss', utf8_decode('ä'), utf8_decode('ü'), utf8_decode('ö')), array('a', 'u', 'o', 'ß', 'a', 'u', 'o', 'ß'), $b);
                if ($a === $b) {
                    return 0;
                }

                return ($a < $b) ? -1 : 1;
            }
        );
    }
    executeHook(HOOK_TOOLSGLOBAL_INC_GIBBELIEFERBARELAENDER, array('oLaender_arr' => &$laender));

    return $laender;
}

/**
 * @param object $startKat
 * @param object $AufgeklappteKategorien
 * @param object $AktuelleKategorie
 */
function baueKategorieListenHTML($startKat, $AufgeklappteKategorien, $AktuelleKategorie)
{
    $cKategorielistenHTML_arr = array();
    if (function_exists('gibKategorienHTML')) {
        $cacheID = 'jtl_clh_' .
            $startKat->kKategorie . '_' .
            (isset($AktuelleKategorie->kKategorie) ? $AktuelleKategorie->kKategorie : 0);

        if (isset($AufgeklappteKategorien->elemente)) {
            foreach ($AufgeklappteKategorien->elemente as $_elem) {
                if (isset($_elem->kKategorie)) {
                    $cacheID .= '_' . $_elem->kKategorie;
                }
            }
        }
        $conf = Shop::getSettings(array(CONF_TEMPLATE));
        if ((!isset($conf['template']['categories']['sidebox_categories_full_category_tree']) || $conf['template']['categories']['sidebox_categories_full_category_tree'] !== 'Y') &&
            ($cKategorielistenHTML_arr = Shop::Cache()->get($cacheID)) === false ||
            !isset($cKategorielistenHTML_arr[0])
        ) {
            $cKategorielistenHTML_arr = array();
            //globale Liste
            $cKategorielistenHTML_arr[0] = (function_exists('gibKategorienHTML')) ? gibKategorienHTML(
                $startKat,
                (isset($AufgeklappteKategorien->elemente)) ? $AufgeklappteKategorien->elemente : null,
                0,
                (isset($AktuelleKategorie->kKategorie) ? $AktuelleKategorie->kKategorie : 0)
            ) : '';

            $dist_kategorieboxen = Shop::DB()->query("SELECT DISTINCT(cWert) FROM tkategorieattribut WHERE cName = '" . KAT_ATTRIBUT_KATEGORIEBOX . "'", 2);
            foreach ($dist_kategorieboxen as $katboxNr) {
                $nr = intval($katboxNr->cWert);
                if ($nr > 0) {
                    $cKategorielistenHTML_arr[$nr] = (function_exists('gibKategorienHTML')) ?
                        gibKategorienHTML($startKat, $AufgeklappteKategorien->elemente, 0,
                            $AktuelleKategorie->kKategorie, $nr) :
                        '';
                }
            }
            Shop::Cache()->set($cacheID, $cKategorielistenHTML_arr, array(CACHING_GROUP_CATEGORY));
        }
    }

    Shop::Smarty()->assign('cKategorielistenHTML_arr', $cKategorielistenHTML_arr);
}

/**
 * @param Kategorie $AktuelleKategorie
 */
function baueUnterkategorieListeHTML($AktuelleKategorie)
{
    if (isset($AktuelleKategorie->kKategorie) && $AktuelleKategorie->kKategorie > 0) {
        $cacheID = 'ukl_' . $AktuelleKategorie->kKategorie . '_' . Shop::$kSprache;
        if (($UnterKatListe = Shop::Cache()->get($cacheID)) === false || !is_object($UnterKatListe)) {
            $UnterKatListe = new KategorieListe();
            $UnterKatListe->getAllCategoriesOnLevel($AktuelleKategorie->kKategorie);
            // Bildpfad vorbereiten
            if (is_array($UnterKatListe->elemente) && count($UnterKatListe->elemente) > 0) {
                foreach ($UnterKatListe->elemente as $i => $oUnterKat) {
                    // Relativen Pfad uebergeben.
                    if (!empty($oUnterKat->cPfad)) {
                        $UnterKatListe->elemente[$i]->cBildPfad = 'bilder/kategorien/' . $oUnterKat->cPfad;
                    }
                }
            }
            Shop::Cache()->set($cacheID, $UnterKatListe, array(CACHING_GROUP_CATEGORY, CACHING_GROUP_CATEGORY . '_' . $AktuelleKategorie->kKategorie));
        }
        Shop::Smarty()->assign('oUnterKategorien_arr', $UnterKatListe->elemente);
    } else {
        Shop::Smarty()->assign('oUnterKategorien_arr', array());
    }
}

/**
 * @param int $sec
 * @return string
 */
function gibCaptchaCode($sec)
{
    $code = '';
    switch (intval($sec)) {
        case 1:
            $chars = '1234567890';
            for ($i = 0; $i < 4; $i++) {
                $code .= $chars{rand(0, strlen($chars) - 1)};
            }
            break;
        case 2:
        case 3:
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            for ($i = 0; $i < 4; $i++) {
                $code .= $chars{rand(0, strlen($chars) - 1)};
            }
            break;
    }

    return strtoupper($code);
}

/**
 * @param string $klartext
 * @return string
 */
function encodeCode($klartext)
{
    if (strlen($klartext) !== 4) {
        return '0';
    }
    $key  = BLOWFISH_KEY;
    $mod1 = (ord($key[0]) + ord($key[1]) + ord($key[2])) % 9 + 1;
    $mod2 = strlen($_SERVER['DOCUMENT_ROOT']) % 9 + 1;

    $s1 = ord($klartext{0}) - $mod2 + $mod1 + 123;
    $s2 = ord($klartext{1}) - $mod1 + $mod2 + 234;
    $s3 = ord($klartext{2}) + $mod1 + 345;
    $s4 = ord($klartext{3}) + $mod2 + 456;

    return rand(100, 999) . $s3 . rand(0, 9) . $s4 . rand(10, 99) . $s1 . $s2 . rand(1000, 9999);
}

/**
 * @param int $sec
 * @return stdClass|false
 */
function generiereCaptchaCode($sec)
{
    if ($sec === 'N' || !$sec) {
        return false;
    }

    //fix: #340 - Sicherheitscode Unterstützung für Tiny (Shop3) Templates
    if (TEMPLATE_COMPATIBILITY === true && $sec === 'Y') {
        $conf  = Shop::getSettings(array(CONF_GLOBAL));
        $_sec  = $conf['global']['anti_spam_method'];
        if ($_sec !== '7') {
            $sec = $_sec;
        }
    }
    if ($sec === '7' || $sec === 'Y') {
        return false;
    }

    $code = new stdClass();
    if ($sec == 4) {
        $rnd       = time() % 4 + 1;
        $code->art = $rnd;
        switch ($rnd) {
            case 1 :
                $x1          = rand(1, 10);
                $x2          = rand(1, 10);
                $code->code  = $x1 + $x2;
                $code->frage = Shop::Lang()->get('captchaMathQuestion', 'global') . ' ' . $x1 . ' ' . Shop::Lang()->get('captchaAddition', 'global') . ' ' . $x2 . '?';
                break;

            case 2 :
                $x1          = rand(3, 10);
                $x2          = rand(1, $x1 - 1);
                $code->code  = $x1 - $x2;
                $code->frage = Shop::Lang()->get('captchaMathQuestion', 'global') . ' ' . $x1 . ' ' . Shop::Lang()->get('captchaSubtraction', 'global') . ' ' . $x2 . '?';
                break;

            case 3 :
                $x1          = rand(2, 5);
                $x2          = rand(2, 5);
                $code->code  = $x1 * $x2;
                $code->frage = Shop::Lang()->get('captchaMathQuestion', 'global') . ' ' . $x1 . ' ' . Shop::Lang()->get('captchaMultiplication', 'global') . ' ' . $x2 . '?';
                break;

            case 4 :
                $x1         = rand(2, 5);
                $x2         = rand(2, 5);
                $code->code = $x1;
                $x1 *= $x2;
                $code->frage = Shop::Lang()->get('captchaMathQuestion', 'global') . ' ' . $x1 . ' ' . Shop::Lang()->get('captchaDivision', 'global') . ' ' . $x2 . '?';
                break;

        }
    } elseif ($sec == 5) { //Neuer unsichtbarer Token
        $_SESSION['xcrsf_token'] = null;
    } else {
        $code->code    = gibCaptchaCode($sec);
        $code->codeURL = Shop::getURL() . '/' . PFAD_INCLUDES . 'captcha/captcha.php?c=' . encodeCode($code->code) . '&amp;s=' . $sec . '&amp;l=' . rand(0, 9);
    }
    $code->codemd5 = md5(PFAD_ROOT . $code->code);

    return $code;
}

/**
 * @param string $data
 * @return int
 */
function checkeTel($data)
{
    if (!$data) {
        return 1;
    }
    if (!preg_match('/^[0-9\-\(\)\/\+\s]{1,}$/', $data)) {
        return 2;
    }

    return 0;
}

/**
 * @param string $data
 * @return int
 */
function checkeDatum($data)
{
    if (!$data) {
        return 1;
    }
    if (!preg_match('/^\d{2}\.\d{2}\.(\d{4})$/', $data)) {
        return 2;
    }
    list($tag, $monat, $jahr) = explode('.', $data);
    if (!checkdate($monat, $tag, $jahr)) {
        return 3;
    }

    return 0;
}

/**
 * @param Artikel $Artikel
 * @param string $einstellung
 * @return int
 */
function gibVerfuegbarkeitsformularAnzeigen($Artikel, $einstellung)
{
    if (isset($einstellung) && $einstellung !== 'N' &&
        ($Artikel->inWarenkorbLegbar == INWKNICHTLEGBAR_LAGER ||
            $Artikel->inWarenkorbLegbar == INWKNICHTLEGBAR_LAGERVAR ||
            ($Artikel->fLagerbestand <= 0 && $Artikel->cLagerKleinerNull === 'Y'))
    ) {
        switch ($einstellung) {
            case 'Y':
                return 1;
            case 'P':
                return 2;
            case 'L':
                return 3;
        }
    }

    return 0;
}

/**
 * Gibt von einem Artikel mit normalen Variationen, ein Array aller ausverkauften Variationen zurück
 *
 * @param int          $kArtikel
 * @param null|Artikel $oArtikel
 * @return array
 */
function pruefeVariationAusverkauft($kArtikel = 0, $oArtikel = null)
{
    if (intval($kArtikel) > 0) {
        $oArtikel = new Artikel();
        $oArtikel->fuelleArtikel($kArtikel, Artikel::getDefaultOptions());
    }

    if (!isset($oArtikel->kArtikel) || $oArtikel->kArtikel == 0) {
        return array();
    }

    $oVariationsAusverkauft_arr = array();
    if ($oArtikel->kEigenschaftKombi == 0 && $oArtikel->nIstVater == 0) {
        if (isset($oArtikel->Variationen) && count($oArtikel->Variationen) > 0) {
            foreach ($oArtikel->Variationen as $oVariation) {
                if (isset($oVariation->Werte) && count($oVariation->Werte) > 0) {
                    foreach ($oVariation->Werte as $oVariationWert) {
                        // Ist Variation ausverkauft?
                        if ($oVariationWert->fLagerbestand <= 0) {
                            $oVariationWert->cNameEigenschaft                      = $oVariation->cName;
                            $oVariationsAusverkauft_arr[$oVariation->kEigenschaft] = $oVariationWert;
                        }
                    }
                }
            }
        }
    }

    return $oVariationsAusverkauft_arr;
}

/**
 * Gibt einen String für einen Header mit dem angegebenen Status-Code aus
 *
 * @param int $nStatusCode
 * @return string
 */
function makeHTTPHeader($nStatusCode)
{
    static $http = array(
        100 => 'HTTP/1.1 100 Continue',
        101 => 'HTTP/1.1 101 Switching Protocols',
        200 => 'HTTP/1.1 200 OK',
        201 => 'HTTP/1.1 201 Created',
        202 => 'HTTP/1.1 202 Accepted',
        203 => 'HTTP/1.1 203 Non-Authoritative Information',
        204 => 'HTTP/1.1 204 No Content',
        205 => 'HTTP/1.1 205 Reset Content',
        206 => 'HTTP/1.1 206 Partial Content',
        300 => 'HTTP/1.1 300 Multiple Choices',
        301 => 'HTTP/1.1 301 Moved Permanently',
        302 => 'HTTP/1.1 302 Found',
        303 => 'HTTP/1.1 303 See Other',
        304 => 'HTTP/1.1 304 Not Modified',
        305 => 'HTTP/1.1 305 Use Proxy',
        307 => 'HTTP/1.1 307 Temporary Redirect',
        400 => 'HTTP/1.1 400 Bad Request',
        401 => 'HTTP/1.1 401 Unauthorized',
        402 => 'HTTP/1.1 402 Payment Required',
        403 => 'HTTP/1.1 403 Forbidden',
        404 => 'HTTP/1.1 404 Not Found',
        405 => 'HTTP/1.1 405 Method Not Allowed',
        406 => 'HTTP/1.1 406 Not Acceptable',
        407 => 'HTTP/1.1 407 Proxy Authentication Required',
        408 => 'HTTP/1.1 408 Request Time-out',
        409 => 'HTTP/1.1 409 Conflict',
        410 => 'HTTP/1.1 410 Gone',
        411 => 'HTTP/1.1 411 Length Required',
        412 => 'HTTP/1.1 412 Precondition Failed',
        413 => 'HTTP/1.1 413 Request Entity Too Large',
        414 => 'HTTP/1.1 414 Request-URI Too Large',
        415 => 'HTTP/1.1 415 Unsupported Media Type',
        416 => 'HTTP/1.1 416 Requested range not satisfiable',
        417 => 'HTTP/1.1 417 Expectation Failed',
        500 => 'HTTP/1.1 500 Internal Server Error',
        501 => 'HTTP/1.1 501 Not Implemented',
        502 => 'HTTP/1.1 502 Bad Gateway',
        503 => 'HTTP/1.1 503 Service Unavailable',
        504 => 'HTTP/1.1 504 Gateway Time-out'
    );

    return $http[$nStatusCode];
}

/**
 * @param array $nFilter_arr
 * @return array
 */
function setzeMerkmalFilter($nFilter_arr = array())
{
    $filter = array();
    if (is_array($nFilter_arr) && count($nFilter_arr) > 1) {
        foreach ($nFilter_arr as $nFilter) {
            if (intval($nFilter) > 0) {
                $filter[] = 'mf' . intval($nFilter);
            }
        }
    } else {
        if (count($_GET) > 0) {
            foreach ($_GET as $key => $value) {
                if (preg_match('/mf\d+/i', $key)) {
                    $filter[] = $value;
                }
            }
        } elseif (count($_POST) > 0) {
            foreach ($_POST as $key => $value) {
                if (preg_match('/mf\d+/i', $key)) {
                    $filter[] = $value;
                }
            }
        }
    }

    return $filter;
}

/**
 * @param array $nFilter_arr
 * @return array
 */
function setzeSuchFilter($nFilter_arr = array())
{
    $filter = array();

    if (is_array($nFilter_arr) && count($nFilter_arr) > 1) {
        foreach ($nFilter_arr as $nFilter) {
            if (intval($nFilter) > 0) {
                $filter[] = 'sf' . intval($nFilter);
            }
        }
    } else {
        $i = 1;
        while ($i < 20) {
            if (verifyGPCDataInteger('sf' . $i) > 0) {
                $filter[] = verifyGPCDataInteger('sf' . $i);
            }
            $i++;
        }
    }

    return $filter;
}

/**
 * @param array $nFilter_arr
 * @return array
 */
function setzeTagFilter($nFilter_arr = array())
{
    $filter = array();

    if (is_array($nFilter_arr) && count($nFilter_arr) > 1) {
        foreach ($nFilter_arr as $nFilter) {
            if (intval($nFilter) > 0) {
                $filter[] = 'tf' . intval($nFilter);
            }
        }
    } else {
        $i = 1;
        while ($i < 20) {
            if (verifyGPCDataInteger('tf' . $i) > 0) {
                $filter[] = verifyGPCDataInteger('tf' . $i);
            }
            $i++;
        }
    }

    return $filter;
}

/**
 * Sortiert ein Array von Objekten anhand von einem bestimmten Member vom Objekt
 * z.B. sortiereFilter($NaviFilter->MerkmalFilter, "kMerkmalWert");
 *
 * @param array $oFilter_arr
 * @param string $cKey
 * @return array
 */
function sortiereFilter($oFilter_arr, $cKey)
{
    $kKey_arr        = array();
    $oFilterSort_arr = array();

    if (is_array($oFilter_arr) && count($oFilter_arr) > 0) {
        foreach ($oFilter_arr as $oFilter) {
            // Baue das Array mit Keys auf, die sortiert werden sollen
            $kKey_arr[] = intval($oFilter->$cKey);
        }
        // Sortiere das Array
        sort($kKey_arr, SORT_NUMERIC);
        foreach ($kKey_arr as $kKey) {
            foreach ($oFilter_arr as $oFilter) {
                if ($oFilter->$cKey == $kKey) {
                    // Baue das Array auf, welches sortiert zurueckgegeben wird
                    $oFilterSort_arr[] = $oFilter;
                    break;
                }
            }
        }
    }

    return $oFilterSort_arr;
}

/**
 * Überprüft Parameter und gibt falls erfolgreich kWunschliste zurück, ansonten 0
 *
 * @return int
 */
function checkeWunschlisteParameter()
{
    $cURLID = StringHandler::filterXSS(Shop::DB()->escape(verifyGPDataString('wlid')));

    if (strlen($cURLID) > 0) {
        // Kampagne
        $cKampagneSQL = "cURLID='" . $cURLID . "'";
        $oKampagne    = new Kampagne(KAMPAGNE_INTERN_OEFFENTL_WUNSCHZETTEL);
        if (isset($oKampagne->kKampagne) && $oKampagne->kKampagne > 0) {
            $cKampagneSQL = "cURLID = '" . $cURLID . "&" . $oKampagne->cParameter . "=" . $oKampagne->cWert . "'";
        }

        $oWunschliste = Shop::DB()->query(
            "SELECT kWunschliste
                FROM twunschliste
                WHERE " . $cKampagneSQL . "
                    AND nOeffentlich = 1", 1
        );

        if (isset($oWunschliste->kWunschliste) && $oWunschliste->kWunschliste > 0) {
            return $oWunschliste->kWunschliste;
        }
    }

    return 0;
}

/**
 * @param array $oArtikel_arr
 * @param int   $nEinstArtGewicht
 * @param int   $nEinstVerGewicht
 * @return bool
 */
function baueGewicht($oArtikel_arr, $nEinstArtGewicht = 2, $nEinstVerGewicht = 2)
{
    if (is_array($oArtikel_arr) && count($oArtikel_arr) > 0) {
        foreach ($oArtikel_arr as $oArtikel) {
            if ($oArtikel->fGewicht > 0) {
                $oArtikel->Versandgewicht    = str_replace('.', ',', round($oArtikel->fGewicht, intval($nEinstVerGewicht)));
                $oArtikel->Versandgewicht_en = round($oArtikel->fGewicht, intval($nEinstVerGewicht));
            }

            if ($oArtikel->fArtikelgewicht > 0) {
                $oArtikel->Artikelgewicht    = str_replace('.', ',', round($oArtikel->fArtikelgewicht, intval($nEinstArtGewicht)));
                $oArtikel->Artikelgewicht_en = round($oArtikel->fArtikelgewicht, intval($nEinstArtGewicht));
            }
        }
    }

    return false;
}

/**
 * @param int    $kKundengruppe
 * @param string $cLand
 * @return int|mixed
 */
function gibVersandkostenfreiAb($kKundengruppe, $cLand = '')
{
    // Ticket #1018
    $versandklassen = VersandartHelper::getShippingClasses($_SESSION['Warenkorb']);
    $cacheID        = 'vkfrei_' . $kKundengruppe . '_' . $cLand . '_' . $versandklassen;
    if (($oVersandart = Shop::Cache()->get($cacheID)) === false) {
        if (strlen($cLand) > 0) {
            $cKundeSQLWhere = " AND cLaender LIKE '%" . StringHandler::filterXSS($cLand) . "%'";
        } else {
            $landIso        = Shop::DB()->query("SELECT cISO FROM tfirma JOIN tland ON tfirma.cLand = tland.cDeutsch LIMIT 0,1", 1);
            $cKundeSQLWhere = '';
            if (isset($landIso->cISO)) {
                $cKundeSQLWhere = " AND cLaender LIKE '%{$landIso->cISO}%'";
            }
        }
        $oVersandart = Shop::DB()->query(
            "SELECT * 
                FROM tversandart
                WHERE fVersandkostenfreiAbX > 0
                    AND (cVersandklassen='-1' OR (cVersandklassen LIKE '% " . $versandklassen . " %' OR cVersandklassen LIKE '% " . $versandklassen . "'))
                    AND (cKundengruppen='-1' OR cKundengruppen LIKE '%;" . $kKundengruppe . ";%')
                    " . $cKundeSQLWhere . "
                ORDER BY fVersandkostenfreiAbX
                LIMIT 1", 1
        );
        Shop::Cache()->set($cacheID, $oVersandart, array(CACHING_GROUP_OPTION));
    }

    if (is_object($oVersandart) && $oVersandart->fVersandkostenfreiAbX > 0) {
        return $oVersandart;
    }

    return 0;
}

/**
 * @param Versandart $oVersandart
 * @param float      $fWarenkorbSumme
 * @return string
 */
function baueVersandkostenfreiString($oVersandart, $fWarenkorbSumme)
{
    if (is_object($oVersandart) && floatval($oVersandart->fVersandkostenfreiAbX) > 0 && isset($_SESSION['Warenkorb']) && isset($_SESSION['Steuerland'])) {
        $fSummeDiff = floatval($oVersandart->fVersandkostenfreiAbX) - floatval($fWarenkorbSumme);
        //check if vkfreiabx is calculated net or gross
        $calculateNet = ($oVersandart->eSteuer === 'netto') ? true : false;
        if ($calculateNet === true) {
            //calculate net with default tax class
            $defaultTaxClass = Shop::DB()->select('tsteuerklasse', 'cStandard', 'Y');
            if (isset($defaultTaxClass->kSteuerklasse)) {
                $taxClasss  = intval($defaultTaxClass->kSteuerklasse);
                $defaultTax = Shop::DB()->select('tsteuersatz', 'kSteuerklasse', $taxClasss);
                if (isset($defaultTax->fSteuersatz)) {
                    $defaultTaxValue       = $defaultTax->fSteuersatz;
                    $fVersandkostenfreiAbX = berechneNetto(floatval($oVersandart->fVersandkostenfreiAbX), $defaultTaxValue);
                    $fSummeDiff            = $fVersandkostenfreiAbX - berechneNetto(floatval($fWarenkorbSumme), $defaultTaxValue);
                }
            }
        }
        // localization - see /jtl-shop/issues#347
        $VersandartSprache = Shop::DB()->select('tversandartsprache', 'kVersandart', $oVersandart->kVersandart, 'cISOSprache', $_SESSION['cISOSprache']);
        $cName             = (!empty($VersandartSprache->cName)) ?
            $VersandartSprache->cName :
            $oVersandart->cName;
        if ($fSummeDiff <= 0) {
            return sprintf(Shop::Lang()->get('noShippingCostsReached', 'basket'), strval($oVersandart->cLaender)) . ' ' . Shop::Lang()->get('with', 'global') . " $cName";
        }

        return sprintf(Shop::Lang()->get('noShippingCostsAt', 'basket'), strval(gibPreisStringLocalized($fSummeDiff))) . ' ' . Shop::Lang()->get('with', 'global') . " $cName";
    }

    return '';
}

/**
 * @param Versandart $oVersandart
 * @param float      $fWarenkorbSumme
 * @return mixed|string
 */
function baueVersandkostenfreiLaenderString($oVersandart, $fWarenkorbSumme = 0.0)
{
    if (is_object($oVersandart) && floatval($oVersandart->fVersandkostenfreiAbX) > 0) {
        $cacheID = 'bvkfls_' . $oVersandart->fVersandkostenfreiAbX . strlen($oVersandart->cLaender);
        if (($vkfls = Shop::Cache()->get($cacheID)) === false) {
            $cLaender_arr = explode(' ', $oVersandart->cLaender);
            if (strlen($cLaender_arr[count($cLaender_arr) - 1]) === 0) {
                unset($cLaender_arr[count($cLaender_arr) - 1]);
            }
            $cSQL     = '';
            $nLaender = count($cLaender_arr);
            for ($i = 0; $i < $nLaender; $i++) {
                $cSQL .= "cISO='" . $cLaender_arr[$i] . "'";
                if ($nLaender > ($i + 1)) {
                    $cSQL .= " OR ";
                }
            }

            $cLaender  = '';
            $oLand_arr = Shop::DB()->query("SELECT cISO, cDeutsch, cEnglisch FROM tland WHERE " . $cSQL, 2);

            for ($i = 0; $i < $nLaender; $i++) {
                $cLaender .= $_SESSION['cISOSprache'] == 'ger' ? $oLand_arr[$i]->cDeutsch : $oLand_arr[$i]->cEnglisch;
                if ($nLaender > ($i + 1)) {
                    $cLaender .= ', ';
                }
            }
            $vkfls = sprintf(Shop::Lang()->get('noShippingCostsAtExtended', 'basket'), $cLaender);
            Shop::Cache()->set($cacheID, $vkfls, array(CACHING_GROUP_OPTION));
        }

        return $vkfls;
    }

    return '';
}

/**
 * @param float $preis
 * @param int   $waehrung
 * @param int   $html
 * @return string
 */
function gibPreisLocalizedOhneFaktor($preis, $waehrung = 0, $html = 1)
{
    if (!$waehrung && isset($_SESSION['Waehrung'])) {
        $waehrung = $_SESSION['Waehrung'];
    }
    if (!isset($waehrung->kWaehrung)) {
        $waehrung = Shop::DB()->query("SELECT * FROM twaehrung WHERE cStandard = 'Y'", 1);
    }
    $preis        = number_format($preis, 2, $waehrung->cTrennzeichenCent, $waehrung->cTrennzeichenTausend);
    $waherungname = (!$html) ? $waehrung->cName : $waehrung->cNameHTML;

    return ($waehrung->cVorBetrag === 'Y') ? $waherungname . ' ' . $preis : $preis . ' ' . $waherungname;
}

/**
 * Bekommt einen String von Keys getrennt durch einen seperator (z.b. ;1;5;6;)
 * und gibt ein Array mit den Keys zurück
 *
 * @param array  $cKeys
 * @param string $cSeperator
 * @return array
 */
function gibKeyArrayFuerKeyString($cKeys, $cSeperator)
{
    $cTMP_arr = explode($cSeperator, $cKeys);
    $kKey_arr = array();
    if (is_array($cTMP_arr) && count($cTMP_arr) > 0) {
        foreach ($cTMP_arr as $cTMP) {
            if (strlen($cTMP) > 0) {
                $kKey_arr[] = intval($cTMP);
            }
        }
    }

    return $kKey_arr;
}

/**
 * Erhält ein Array von Keys und fügt Sie zu einem String zusammen
 * wobei jeder Key durch den Seperator getrennt wird (z.b. ;1;5;6;).
 *
 * @param array  $cKey_arr
 * @param string $cSeperator
 * @return string
 */
function gibKeyStringFuerKeyArray($cKey_arr, $cSeperator)
{
    $cKeys = '';
    if (is_array($cKey_arr) && count($cKey_arr) > 0 && strlen($cSeperator) > 0) {
        $cKeys .= ';';
        foreach ($cKey_arr as $i => $cKey) {
            if ($i > 0) {
                $cKeys .= ';' . $cKey;
            } else {
                $cKeys .= $cKey;
            }
        }
        $cKeys .= ';';
    }

    return $cKeys;
}

// Diese Funktion erhält einen Text als String und parsed ihn. Variablen die geparsed werden lauten wie folgt:
// $#a:ID:NAME#$ => ID = kArtikel NAME => Wunschname ... wird in eine URL (evt. SEO) zum Artikel umgewandelt.
// $#k:ID:NAME#$ => ID = kKategorie NAME => Wunschname ... wird in eine URL (evt. SEO) zur Kategorie umgewandelt.
// $#h:ID:NAME#$ => ID = kHersteller NAME => Wunschname ... wird in eine URL (evt. SEO) zum Hersteller umgewandelt.
// $#m:ID:NAME#$ => ID = kMerkmalWert NAME => Wunschname ... wird in eine URL (evt. SEO) zum MerkmalWert umgewandelt.
// $#n:ID:NAME#$ => ID = kNews NAME => Wunschname ... wird in eine URL (evt. SEO) zur News umgewandelt.
// $#t:ID:NAME#$ => ID = kTag NAME => Wunschname ... wird in eine URL (evt. SEO) zum Tag umgewandelt.
// $#l:ID:NAME#$ => ID = kSuchanfrage NAME => Wunschname ... wird in eine URL (evt. SEO) zur Livesuche umgewandelt.
// Name ist nun Optional

/**
 * @param string $cText
 * @return mixed
 */
function parseNewsText($cText)
{
    preg_match_all('/\${1}\#{1}[akhmntl]{1}:[0-9]+\:{0,1}[a-zA-Z0-9äÄöÖüÜß\.\,\!\"\§\$\%\&\/\(\)\=\`\´\+\~\*\'\;\-\_\?\{\}\[\]\ ]{0,}\#{1}\${1}/', $cText, $cTreffer_arr);
    if (is_array($cTreffer_arr[0]) && count($cTreffer_arr[0]) > 0) {
        // Parameter
        $cParameter_arr = array('a' => URLART_ARTIKEL, 'k' => URLART_KATEGORIE, 'h' => URLART_HERSTELLER, 'm' => URLART_MERKMAL, 'n' => URLART_NEWS, 't' => URLART_TAG, 'l' => URLART_LIVESUCHE);
        foreach ($cTreffer_arr[0] as $cTreffer) {
            $cParameter = substr($cTreffer, (strpos($cTreffer, '#', 0) + 1), 1);
            $nBis       = strpos($cTreffer, ':', 4);
            // Es wurde kein Name angegeben
            if ($nBis === false) {
                $nBis  = strpos($cTreffer, ':', 3);
                $nVon  = strpos($cTreffer, '#', $nBis);
                $cKey  = substr($cTreffer, $nBis + 1, ($nVon - 1) - $nBis);
                $cName = '';
            } else {
                $cKey  = substr($cTreffer, 4, $nBis - 4);
                $cName = substr($cTreffer, $nBis + 1, strpos($cTreffer, '#', $nBis) - ($nBis + 1));
            }

            $oObjekt    = new stdClass();
            $bVorhanden = false;
            //switch($cURLArt_arr[$i])
            switch ($cParameter_arr[$cParameter]) {
                case URLART_ARTIKEL :
                    $oObjekt->kArtikel = intval($cKey);
                    $oObjekt->cKey     = 'kArtikel';
                    $cTabellenname     = 'tartikel';
                    $cSpracheSQL       = '';
                    if (isset($_SESSION['kSprache']) && $_SESSION['kSprache'] > 0 && !standardspracheAktiv()) {
                        $cTabellenname = 'tartikelsprache';
                        $cSpracheSQL   = " AND tartikelsprache.kSprache=" . $_SESSION['kSprache'];
                    }
                    $oArtikel = Shop::DB()->query(
                        "SELECT " . $cTabellenname . ".kArtikel, " . $cTabellenname . ".cName, tseo.cSeo
                            FROM " . $cTabellenname . "
                            LEFT JOIN tseo ON tseo.cKey = 'kArtikel'
                                AND tseo.kKey = " . $cTabellenname . ".kArtikel
                                AND tseo.kSprache = " . intval($_SESSION['kSprache']) . "
                            WHERE " . $cTabellenname . ".kArtikel=" . intval($cKey) . $cSpracheSQL, 1
                    );

                    if (isset($oArtikel->kArtikel) && $oArtikel->kArtikel > 0) {
                        $bVorhanden     = true;
                        $oObjekt->cSeo  = $oArtikel->cSeo;
                        $oObjekt->cName = (!empty($oArtikel->cName)) ?
                            $oArtikel->cName :
                            'Link';
                    }
                    break;

                case URLART_KATEGORIE :
                    $oObjekt->kKategorie = intval($cKey);
                    $oObjekt->cKey       = 'kKategorie';
                    $cTabellenname       = 'tkategorie';
                    $cSpracheSQL         = '';
                    if (isset($_SESSION['kSprache']) && $_SESSION['kSprache'] > 0 && !standardspracheAktiv()) {
                        $cTabellenname = "tkategoriesprache";
                        $cSpracheSQL   = " AND tkategoriesprache.kSprache=" . $_SESSION['kSprache'];
                    }
                    $oKategorie = Shop::DB()->query(
                        "SELECT " . $cTabellenname . ".kKategorie, " . $cTabellenname . ".cName, tseo.cSeo
                            FROM " . $cTabellenname . "
                            LEFT JOIN tseo ON tseo.cKey = 'kKategorie'
                                AND tseo.kKey = " . $cTabellenname . ".kKategorie
                                AND tseo.kSprache = " . intval($_SESSION['kSprache']) . "
                            WHERE " . $cTabellenname . ".kKategorie=" . intval($cKey) . $cSpracheSQL, 1
                    );

                    if (isset($oKategorie->kKategorie) && $oKategorie->kKategorie > 0) {
                        $bVorhanden     = true;
                        $oObjekt->cSeo  = $oKategorie->cSeo;
                        $oObjekt->cName = (!empty($oKategorie->cName)) ?
                            $oKategorie->cName :
                            'Link';
                    }
                    break;

                case URLART_HERSTELLER :
                    $oObjekt->kHersteller = intval($cKey);
                    $oObjekt->cKey        = 'kHersteller';
                    $cTabellenname        = 'thersteller';
                    $oHersteller          = Shop::DB()->query(
                        "SELECT thersteller.kHersteller, thersteller.cName, tseo.cSeo
                            FROM thersteller
                            LEFT JOIN tseo ON tseo.cKey = 'kHersteller'
                                AND tseo.kKey = " . $cTabellenname . ".kHersteller
                                AND tseo.kSprache = " . intval($_SESSION['kSprache']) . "
                            WHERE " . $cTabellenname . ".kHersteller = " . intval($cKey), 1
                    );

                    if (isset($oHersteller->kHersteller) && $oHersteller->kHersteller > 0) {
                        $bVorhanden     = true;
                        $oObjekt->cSeo  = $oHersteller->cSeo;
                        $oObjekt->cName = (!empty($oHersteller->cName)) ?
                            $oHersteller->cName :
                            'Link';
                    }
                    break;

                case URLART_MERKMAL :
                    $oObjekt->kMerkmalWert = intval($cKey);
                    $oObjekt->cKey         = 'kMerkmalWert';
                    $oMerkmalWert          = Shop::DB()->query(
                        "SELECT tmerkmalwertsprache.kMerkmalWert, tmerkmalwertsprache.cWert, tseo.cSeo
                            FROM tmerkmalwertsprache
                            LEFT JOIN tseo ON tseo.cKey = 'kMerkmalWert'
                                AND tseo.kKey = tmerkmalwertsprache.kMerkmalWert
                                AND tseo.kSprache = " . intval($_SESSION['kSprache']) . "
                            WHERE tmerkmalwertsprache.kMerkmalWert = " . intval($cKey) . "
                                AND tmerkmalwertsprache.kSprache = " . intval($_SESSION['kSprache']), 1
                    );

                    if (isset($oMerkmalWert->kMerkmalWert) && $oMerkmalWert->kMerkmalWert > 0) {
                        $bVorhanden     = true;
                        $oObjekt->cSeo  = $oMerkmalWert->cSeo;
                        $oObjekt->cName = (!empty($oMerkmalWert->cWert)) ?
                            $oMerkmalWert->cWert :
                            'Link';
                    }
                    break;

                case URLART_NEWS :
                    $oObjekt->kNews = intval($cKey);
                    $oObjekt->cKey  = 'kNews';
                    $oNews          = Shop::DB()->query(
                        "SELECT tnews.kNews, tnews.cBetreff, tseo.cSeo
                            FROM tnews
                            LEFT JOIN tseo ON tseo.cKey = 'kNews'
                                AND tseo.kKey = tnews.kNews
                                AND tseo.kSprache = " . intval($_SESSION['kSprache']) . "
                            WHERE tnews.kNews=" . intval($cKey), 1
                    );

                    if (isset($oNews->kNews) && $oNews->kNews > 0) {
                        $bVorhanden     = true;
                        $oObjekt->cSeo  = $oNews->cSeo;
                        $oObjekt->cName = (!empty($oNews->cBetreff)) ?
                            $oNews->cBetreff :
                            'Link';
                    }
                    break;

                case URLART_UMFRAGE :
                    $oObjekt->kNews = intval($cKey);
                    $oObjekt->cKey  = 'kUmfrage';
                    $oUmfrage       = Shop::DB()->query(
                        "SELECT tumfrage.kUmfrage, tumfrage.cName, tseo.cSeo
                            FROM tumfrage
                            LEFT JOIN tseo ON tseo.cKey = 'kUmfrage'
                                AND tseo.kKey = tnews.kUmfrage
                                AND tseo.kSprache = " . intval($_SESSION['kSprache']) . "
                            WHERE tumfrage.kUmfrage = " . intval($cKey), 1
                    );

                    if (isset($oUmfrage->kUmfrage) && $oUmfrage->kUmfrage > 0) {
                        $bVorhanden     = true;
                        $oObjekt->cSeo  = $oUmfrage->cSeo;
                        $oObjekt->cName = (!empty($oUmfrage->cName)) ?
                            $oUmfrage->cName :
                            'Link';
                    }
                    break;

                case URLART_TAG :
                    $oObjekt->kNews = intval($cKey);
                    $oObjekt->cKey  = 'kTag';
                    $oTag           = Shop::DB()->query(
                        "SELECT ttag.kTag, ttag.cName, tseo.cSeo
                            FROM ttag
                            LEFT JOIN tseo ON tseo.cKey = 'kTag'
                                AND tseo.kKey = ttag.kTag
                                AND tseo.kSprache = " . intval($_SESSION['kSprache']) . "
                            WHERE ttag.kTag = " . intval($cKey), 1
                    );

                    if (isset($oTag->kTag) && $oTag->kTag > 0) {
                        $bVorhanden     = true;
                        $oObjekt->cSeo  = $oTag->cSeo;
                        $oObjekt->cName = (!empty($oTag->cName)) ?
                            $oTag->cName :
                            'Link';
                    }
                    break;

                case URLART_LIVESUCHE :
                    $oObjekt->kNews = intval($cKey);
                    $oObjekt->cKey  = 'kSuchanfrage';
                    $oSuchanfrage   = Shop::DB()->query(
                        "SELECT tsuchanfrage.kSuchanfrage, tsuchanfrage.cSuche, tseo.cSeo
                            FROM tsuchanfrage
                            LEFT JOIN tseo ON tseo.cKey = 'kSuchanfrage'
                                AND tseo.kKey = tsuchanfrage.kSuchanfrage
                                AND tseo.kSprache = " . intval($_SESSION['kSprache']) . "
                            WHERE tsuchanfrage.kSuchanfrage = " . intval($cKey), 1
                    );

                    if (isset($oSuchanfrage->kSuchanfrage) && $oSuchanfrage->kSuchanfrage > 0) {
                        $bVorhanden     = true;
                        $oObjekt->cSeo  = $oSuchanfrage->cSeo;
                        $oObjekt->cName = (!empty($oSuchanfrage->cSuche)) ?
                            $oSuchanfrage->cSuche :
                            'Link';
                    }
                    break;

            }
            executeHook(HOOK_TOOLSGLOBAL_INC_SWITCH_PARSENEWSTEXT);

            if (strlen($cName) > 0) {
                $oObjekt->cName = $cName;
                $cName          = ':' . $cName;
            }
            if ($bVorhanden) {
                $cURL  = baueURL($oObjekt, $cParameter_arr[$cParameter]);
                $cText = str_replace('$#' . $cParameter . ':' . $cKey . $cName . '#$', '<a href="' . Shop::getURL() . '/' . $cURL . '">' . $oObjekt->cName . '</a>', $cText);
            } else {
                $cText = str_replace('$#' . $cParameter . ':' . $cKey . $cName . '#$', '<a href="' . Shop::getURL() . '/" >' . Shop::Lang()->get('parseTextNoLinkID', 'global') . '</a>', $cText);
            }
        }
    }

    return $cText;
}

/**
 * @param int $kSprache
 * @param int $kKundengruppe
 * @return bool
 */
function gibAGBWRB($kSprache, $kKundengruppe)
{
    if ($kSprache > 0 && $kKundengruppe > 0) {
        // kLink für AGB und WRB suchen
        $oLinkAGB = Shop::DB()->query("SELECT kLink FROM tlink WHERE nLinkart = " . LINKTYP_AGB, 1);
        $oLinkWRB = Shop::DB()->query("SELECT kLink FROM tlink WHERE nLinkart = " . LINKTYP_WRB, 1);
        $oAGBWRB  = Shop::DB()->select('ttext', 'kKundengruppe', intval($kKundengruppe), 'kSprache', intval($kSprache));
        if (!empty($oAGBWRB->kText)) {
            $oAGBWRB->kLinkAGB = 0;
            $oAGBWRB->kLinkWRB = 0;
            if (isset($oLinkAGB->kLink) && $oLinkAGB->kLink > 0) {
                $oAGBWRB->kLinkAGB = $oLinkAGB->kLink;
            }
            if (isset($oLinkWRB->kLink) && $oLinkWRB->kLink > 0) {
                $oAGBWRB->kLinkWRB = $oLinkWRB->kLink;
            }

            return $oAGBWRB;
        }
        $oAGBWRB = Shop::DB()->query("SELECT * FROM ttext WHERE nStandard = 1", 1);
        if (!empty($oAGBWRB->kText)) {
            $oAGBWRB->kLinkAGB = 0;
            $oAGBWRB->kLinkWRB = 0;
            if (isset($oLinkAGB->kLink) && $oLinkAGB->kLink > 0) {
                $oAGBWRB->kLinkAGB = $oLinkAGB->kLink;
            }
            if (isset($oLinkWRB->kLink) && $oLinkWRB->kLink > 0) {
                $oAGBWRB->kLinkWRB = $oLinkWRB->kLink;
            }

            return $oAGBWRB;
        }
    }

    return false;
}

/**
 * @param int $kSprache
 * @return array|mixed
 */
function holeAlleSuchspecialOverlays($kSprache = 0)
{
    if (!$kSprache) {
        $oSprache = gibStandardsprache(true);
        $kSprache = $oSprache->kSprache;
        if (!$kSprache) {
            return array();
        }
    }
    $kSprache = intval($kSprache);
    $cacheID  = 'haso_' . $kSprache;
    if (($oSuchspecialOverlay_arr = Shop::Cache()->get($cacheID)) === false) {
        global $oSuchspecialOverlay_arr;
        if (!isset($oSuchspecialOverlay_arr) || count($oSuchspecialOverlay_arr) === 0) {
            $oSuchspecialOverlayTMP_arr = Shop::DB()->query(
                "SELECT tsuchspecialoverlay.*, tsuchspecialoverlaysprache.kSprache, tsuchspecialoverlaysprache.cBildPfad, tsuchspecialoverlaysprache.nAktiv,
                    tsuchspecialoverlaysprache.nPrio, tsuchspecialoverlaysprache.nMargin, tsuchspecialoverlaysprache.nTransparenz,
                    tsuchspecialoverlaysprache.nGroesse, tsuchspecialoverlaysprache.nPosition
                    FROM tsuchspecialoverlay
                    JOIN tsuchspecialoverlaysprache ON tsuchspecialoverlaysprache.kSuchspecialOverlay = tsuchspecialoverlay.kSuchspecialOverlay
                        AND tsuchspecialoverlaysprache.kSprache = " . $kSprache . "
                    WHERE tsuchspecialoverlaysprache.nAktiv = 1
                        AND tsuchspecialoverlaysprache.nPrio > 0
                    ORDER BY tsuchspecialoverlaysprache.nPrio DESC", 2
            );

            $oSuchspecialOverlay_arr = array();
            if (is_array($oSuchspecialOverlayTMP_arr) && count($oSuchspecialOverlayTMP_arr) > 0) {
                foreach ($oSuchspecialOverlayTMP_arr as $oSuchspecialOverlayTMP) {
                    $cSuchSpecial = strtolower(str_replace(array(' ', '-', '_'), '', $oSuchspecialOverlayTMP->cSuchspecial));
                    $cSuchSpecial = preg_replace(
                        array('/Ä/', '/Ö/', '/Ü/', '/ä/', '/ö/', '/ü/', '/ß/', utf8_decode('/Ä/'), utf8_decode('/Ö/'), utf8_decode('/Ü/'), utf8_decode('/ä/'), utf8_decode('/ö/'), utf8_decode('/ü/'), utf8_decode('/ß/')),
                        array('ae', 'oe', 'ue', 'ae', 'oe', 'ue', 'ss', 'ae', 'oe', 'ue', 'ae', 'oe', 'ue', 'ss'), $cSuchSpecial);
                    if (!isset($oSuchspecialOverlay_arr[$cSuchSpecial])) {
                        $oSuchspecialOverlay_arr[$cSuchSpecial] = new stdClass();
                    }
                    $oSuchspecialOverlay_arr[$cSuchSpecial]              = $oSuchspecialOverlayTMP;
                    $oSuchspecialOverlay_arr[$cSuchSpecial]->cPfadKlein  = PFAD_SUCHSPECIALOVERLAY_KLEIN . $oSuchspecialOverlay_arr[$cSuchSpecial]->cBildPfad;
                    $oSuchspecialOverlay_arr[$cSuchSpecial]->cPfadNormal = PFAD_SUCHSPECIALOVERLAY_NORMAL . $oSuchspecialOverlay_arr[$cSuchSpecial]->cBildPfad;
                    $oSuchspecialOverlay_arr[$cSuchSpecial]->cPfadGross  = PFAD_SUCHSPECIALOVERLAY_GROSS . $oSuchspecialOverlay_arr[$cSuchSpecial]->cBildPfad;
                }
            }
            Shop::Cache()->set($cacheID, $oSuchspecialOverlay_arr, array(CACHING_GROUP_OPTION));
        }
    }

    return $oSuchspecialOverlay_arr;
}

/**
 * @return array
 */
function baueAlleSuchspecialURLs()
{
    $oSuchspecial_arr = array();

    // URLs bauen
    $oSuchspecial_arr[SEARCHSPECIALS_BESTSELLER]        = new stdClass();
    $oSuchspecial_arr[SEARCHSPECIALS_BESTSELLER]->cName = Shop::Lang()->get('bestseller', 'global');
    $oSuchspecial_arr[SEARCHSPECIALS_BESTSELLER]->cURL  = baueSuchSpecialURL(SEARCHSPECIALS_BESTSELLER);

    $oSuchspecial_arr[SEARCHSPECIALS_SPECIALOFFERS]        = new stdClass();
    $oSuchspecial_arr[SEARCHSPECIALS_SPECIALOFFERS]->cName = Shop::Lang()->get('specialOffers', 'global');
    $oSuchspecial_arr[SEARCHSPECIALS_SPECIALOFFERS]->cURL  = baueSuchSpecialURL(SEARCHSPECIALS_SPECIALOFFERS);

    $oSuchspecial_arr[SEARCHSPECIALS_NEWPRODUCTS]        = new stdClass();
    $oSuchspecial_arr[SEARCHSPECIALS_NEWPRODUCTS]->cName = Shop::Lang()->get('newProducts', 'global');
    $oSuchspecial_arr[SEARCHSPECIALS_NEWPRODUCTS]->cURL  = baueSuchSpecialURL(SEARCHSPECIALS_NEWPRODUCTS);

    $oSuchspecial_arr[SEARCHSPECIALS_TOPOFFERS]        = new stdClass();
    $oSuchspecial_arr[SEARCHSPECIALS_TOPOFFERS]->cName = Shop::Lang()->get('topOffers', 'global');
    $oSuchspecial_arr[SEARCHSPECIALS_TOPOFFERS]->cURL  = baueSuchSpecialURL(SEARCHSPECIALS_TOPOFFERS);

    $oSuchspecial_arr[SEARCHSPECIALS_UPCOMINGPRODUCTS]        = new stdClass();
    $oSuchspecial_arr[SEARCHSPECIALS_UPCOMINGPRODUCTS]->cName = Shop::Lang()->get('upcomingProducts', 'global');
    $oSuchspecial_arr[SEARCHSPECIALS_UPCOMINGPRODUCTS]->cURL  = baueSuchSpecialURL(SEARCHSPECIALS_UPCOMINGPRODUCTS);

    $oSuchspecial_arr[SEARCHSPECIALS_TOPREVIEWS]        = new stdClass();
    $oSuchspecial_arr[SEARCHSPECIALS_TOPREVIEWS]->cName = Shop::Lang()->get('topReviews', 'global');
    $oSuchspecial_arr[SEARCHSPECIALS_TOPREVIEWS]->cURL  = baueSuchSpecialURL(SEARCHSPECIALS_TOPREVIEWS);

    return $oSuchspecial_arr;
}

/**
 * @param int $kKey
 * @return mixed|string
 */
function baueSuchSpecialURL($kKey)
{
    $cacheID = 'bsurl_' . $kKey . '_' . $_SESSION['kSprache'];
    if (($url = Shop::Cache()->get($cacheID)) !== false) {
        executeHook(HOOK_BOXEN_INC_SUCHSPECIALURL);

        return $url;
    }
    $oSeo = Shop::DB()->query(
        "SELECT cSeo
            FROM tseo
            WHERE kSprache = " . intval($_SESSION['kSprache']) . "
                AND cKey = 'suchspecial'
                AND kKey = " . intval($kKey), 1
    );
    if ($oSeo === false) {
        $oSeo = new stdClass();
    }

    $oSeo->kSuchspecial = $kKey;
    executeHook(HOOK_BOXEN_INC_SUCHSPECIALURL);
    $url = baueURL($oSeo, URLART_SEARCHSPECIALS);
    Shop::Cache()->set($cacheID, $url, array(CACHING_GROUP_CATEGORY));

    return $url;
}

/**
 * @param string $cZahlungsID
 */
function checkeExterneZahlung($cZahlungsID)
{
    $cZahlungsID = Shop::DB()->escape(substr($cZahlungsID, 1));
    // cZahlungsID / SessionID / z
    list($cZahlungsID, $SessionID, $z) = explode(';', $cZahlungsID);
    $oZahlungSession                   = Shop::DB()->select('tzahlungsession', 'cZahlungsID', $cZahlungsID);
    if (isset($oZahlungSession->kBestellung) && $oZahlungSession->kBestellung > 0 && !$oZahlungSession->dNotify) {
        $_upd                = new stdClass();
        $_upd->dBezahltDatum = 'now()';
        $_upd->cStatus       = BESTELLUNG_STATUS_BEZAHLT;
        Shop::DB()->update('tbestellung', 'kBestellung', (int) $oZahlungSession->kBestellung, $_upd);
        $bestellung = new Bestellung($oZahlungSession->kBestellung);
        $bestellung->fuelleBestellung(0);
        // process payment
        $zahlungseingang                    = new stdClass();
        $zahlungseingang->kBestellung       = $bestellung->kBestellung;
        $zahlungseingang->cZahlungsanbieter = 'PayPal';
        $zahlungseingang->fBetrag           = $_POST['mc_gross'];
        $zahlungseingang->fZahlungsgebuehr  = $_POST['payment_fee'];
        $zahlungseingang->cISO              = $_POST['mc_currency'];
        $zahlungseingang->cEmpfaenger       = $_POST['receiver_email'];
        $zahlungseingang->cZahler           = $_POST['payer_email'];
        $zahlungseingang->cAbgeholt         = 'N';
        $zahlungseingang->dZeit             = date_format(date_create($_POST['payment_date']), 'Y-m-d H:m:s');
        Shop::DB()->insert('tzahlungseingang', $zahlungseingang);
    }
}

/**
 * @param string      $cPasswort
 * @param null{string $cHashPasswort
 * @return bool|string
 */
function cryptPasswort($cPasswort, $cHashPasswort = null)
{
    $cSalt   = sha1(uniqid(mt_rand(), true));
    $nLaenge = strlen($cSalt);
    $nLaenge = max($nLaenge >> 3, ($nLaenge >> 2) - strlen($cPasswort));
    $cSalt   = $cHashPasswort ? substr($cHashPasswort, min(strlen($cPasswort), strlen($cHashPasswort) - $nLaenge), $nLaenge) : strrev(substr($cSalt, 0, $nLaenge));
    $cHash   = sha1($cPasswort);
    $cHash   = sha1(substr($cHash, 0, strlen($cPasswort)) . $cSalt . substr($cHash, strlen($cPasswort)));
    $cHash   = substr($cHash, $nLaenge);
    $cHash   = substr($cHash, 0, strlen($cPasswort)) . $cSalt . substr($cHash, strlen($cPasswort));

    return $cHashPasswort && $cHashPasswort !== $cHash ? false : $cHash;
}

/**
 *
 */
function setzeSpracheUndWaehrungLink()
{
    global $NaviFilter, $oZusatzFilter, $sprachURL, $AktuellerArtikel, $kSeite, $kLink, $AktuelleSeite;
    $shopURL = Shop::getURL();
    if (isset($kSeite) && $kSeite > 0) {
        $kLink = $kSeite;
    }
    // Sprachauswahl
    if (isset($_SESSION['Sprachen']) && count($_SESSION['Sprachen']) > 1) {
        if (isset($AktuellerArtikel->kArtikel) && $AktuellerArtikel->kArtikel > 0) {
            $AktuellerArtikel->baueArtikelSprachURL(SHOP_SEO);
        }
        foreach ($_SESSION['Sprachen'] as $i => $oSprache) {
            if (isset($AktuellerArtikel->kArtikel) && $AktuellerArtikel->kArtikel > 0 && isset($AktuellerArtikel->cSprachURL_arr[$oSprache->kSprache])) {
                $_SESSION['Sprachen'][$i]->cURL = $AktuellerArtikel->cSprachURL_arr[$oSprache->kSprache];
            } elseif (($kLink > 0 || $kSeite > 0) && isset($sprachURL[$oSprache->cISO])) {
                $_SESSION['Sprachen'][$i]->cURL = $sprachURL[$oSprache->cISO];
            } // Special Seiten
            elseif ($AktuelleSeite == 'WARENKORB'
                || $AktuelleSeite == 'KONTAKT'
                || $AktuelleSeite == 'REGISTRIEREN'
                || $AktuelleSeite == 'MEIN KONTO'
                || $AktuelleSeite == 'NEWSLETTER'
                || $AktuelleSeite == 'UMFRAGE'
                || $AktuelleSeite == 'BESTELLVORGANG'
                || $AktuelleSeite == 'STARTSEITE'
            ) {
                switch ($AktuelleSeite) {
                    case 'STARTSEITE' :
                        $_SESSION['Sprachen'][$i]->cURL = gibNaviURL($NaviFilter, SHOP_SEO, $oZusatzFilter, $oSprache->kSprache);
                        if ($_SESSION['Sprachen'][$i]->cURL === $shopURL . '/') {
                            $_SESSION['Sprachen'][$i]->cURL .= '?lang=' . $oSprache->cISO;
                        }
                        break;

                    case 'WARENKORB' :
                        $_SESSION['Sprachen'][$i]->cURL = $shopURL . '/warenkorb.php?lang=' . $oSprache->cISO;
                        break;

                    case 'KONTAKT' :
                        $_SESSION['Sprachen'][$i]->cURL = $shopURL . '/kontakt.php?lang=' . $oSprache->cISO;
                        break;

                    case 'REGISTRIEREN' :
                        $_SESSION['Sprachen'][$i]->cURL = $shopURL . '/registrieren.php?lang=' . $oSprache->cISO;
                        break;

                    case 'MEIN KONTO' :
                        $_SESSION['Sprachen'][$i]->cURL = $shopURL . '/jtl.php?lang=' . $oSprache->cISO;
                        break;

                    case 'NEWSLETTER' :
                        $_SESSION['Sprachen'][$i]->cURL = $shopURL . '/newsletter.php?lang=' . $oSprache->cISO;
                        break;

                    case 'UMFRAGE' :
                        $_SESSION['Sprachen'][$i]->cURL = $shopURL . '/umfrage.php?lang=' . $oSprache->cISO;
                        break;

                    case 'BESTELLVORGANG' :
                        $_SESSION['Sprachen'][$i]->cURL = $shopURL . '/bestellvorgang.php?lang=' . $oSprache->cISO;
                        break;

                }
                executeHook(HOOK_TOOLSGLOBAL_INC_SWITCH_SETZESPRACHEUNDWAEHRUNG_SPRACHE);
            } else {
                $_SESSION['Sprachen'][$i]->cURL = gibNaviURL($NaviFilter, SHOP_SEO, $oZusatzFilter, $oSprache->kSprache);
            }
        }
    }
    // Währungsauswahl
    if (count($_SESSION['Waehrungen']) > 1) {
        if (isset($AktuellerArtikel->kArtikel) && $AktuellerArtikel->kArtikel > 0 && $AktuellerArtikel->cSprachURL_arr === null) {
            $AktuellerArtikel->baueArtikelSprachURL(false);
        }
        foreach ($_SESSION['Waehrungen'] as $i => $oWaehrung) {
            if (isset($AktuellerArtikel->kArtikel) && $AktuellerArtikel->kArtikel > 0 && isset($_SESSION['kSprache']) && isset($AktuellerArtikel->cSprachURL_arr[$_SESSION['kSprache']])) {
                $_SESSION['Waehrungen'][$i]->cURL = $AktuellerArtikel->cSprachURL_arr[$_SESSION['kSprache']] . '?curr=' . $oWaehrung->cISO;
            } elseif ($kLink > 0) {
                $_SESSION['Waehrungen'][$i]->cURL = 'navi.php?s=' . $kLink . '&amp;lang=' . $_SESSION['cISOSprache'] . '&amp;curr=' . $oWaehrung->cISO;
            } elseif ($AktuelleSeite == 'WARENKORB' ||
                $AktuelleSeite == 'KONTAKT' ||
                $AktuelleSeite == 'REGISTRIEREN' ||
                $AktuelleSeite == 'MEIN KONTO' ||
                $AktuelleSeite == 'NEWSLETTER' ||
                $AktuelleSeite == 'UMFRAGE' ||
                $AktuelleSeite == 'BESTELLVORGANG'
            ) { // Special Seiten
                switch ($AktuelleSeite) {
                    case 'WARENKORB' :
                        $_SESSION['Waehrungen'][$i]->cURL = $shopURL . '/warenkorb.php?lang=' . $_SESSION['cISOSprache'] . '&amp;curr=' . $oWaehrung->cISO;
                        break;

                    case 'KONTAKT' :
                        $_SESSION['Waehrungen'][$i]->cURL = $shopURL . '/kontakt.php?lang=' . $_SESSION['cISOSprache'] . '&amp;curr=' . $oWaehrung->cISO;
                        break;

                    case 'REGISTRIEREN' :
                        $_SESSION['Waehrungen'][$i]->cURL = $shopURL . '/registrieren.php?lang=' . $_SESSION['cISOSprache'] . '&amp;curr=' . $oWaehrung->cISO;
                        break;

                    case 'MEIN KONTO' :
                        $_SESSION['Waehrungen'][$i]->cURL = $shopURL . '/jtl.php?lang=' . $_SESSION['cISOSprache'] . '&amp;curr=' . $oWaehrung->cISO;
                        break;

                    case 'NEWSLETTER' :
                        $_SESSION['Waehrungen'][$i]->cURL = $shopURL . '/newsletter.php?lang=' . $_SESSION['cISOSprache'] . '&amp;curr=' . $oWaehrung->cISO;
                        break;

                    case 'UMFRAGE' :
                        $_SESSION['Waehrungen'][$i]->cURL = $shopURL . '/umfrage.php?lang=' . $_SESSION['cISOSprache'] . '&amp;curr=' . $oWaehrung->cISO;
                        break;

                    case 'BESTELLVORGANG' :
                        $_SESSION['Waehrungen'][$i]->cURL = $shopURL . '/bestellvorgang.php?lang=' . $_SESSION['cISOSprache'] . '&amp;curr=' . $oWaehrung->cISO;
                        break;

                }
            } else {
                $_SESSION['Waehrungen'][$i]->cURL = gibNaviURL($NaviFilter, false, $oZusatzFilter, $_SESSION['kSprache']) . '&amp;lang=' . $_SESSION['cISOSprache'] . '&amp;curr=' . $oWaehrung->cISO;
            }
        }
    }

    executeHook(
        HOOK_TOOLSGLOBAL_INC_SETZESPRACHEUNDWAEHRUNG_WAEHRUNG, array(
            'oNaviFilter'       => &$NaviFilter,
            'oZusatzFilter'     => &$oZusatzFilter,
            'cSprachURL'        => &$sprachURL,
            'oAktuellerArtikel' => &$AktuellerArtikel,
            'kSeite'            => &$kSeite,
            'kLink'             => &$kLink,
            'AktuelleSeite'     => &$AktuelleSeite
        )
    );
}

/**
 * Prueft ob SSL aktiviert ist und auch durch Einstellung genutzt werden soll
 * -1 = SSL nicht aktiv und nicht erlaubt
 * 1 = SSL aktiv durch Einstellung nicht erwünscht
 * 2 = SSL aktiv und erlaubt
 * 3 = SSL nicht aktiv aber erlaubt
 * 4 = SSL nicht aktiv aber erzwungen
 *
 * @return int
 */
function pruefeSSL()
{
    $conf       = Shop::getSettings(array(CONF_GLOBAL));
    $cSSLNutzen = $conf['global']['kaufabwicklung_ssl_nutzen'];
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        $_SERVER['HTTPS'] = 'on';
    }

    // Ist im Server SSL aktiv?
    if (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) === 'on' || $_SERVER['HTTPS'] === '1')) {
        if ($cSSLNutzen === 'P' || $cSSLNutzen === 'Z') { // SSL durch Einstellung erlaubt?
            return 2;
        }

        return 1;
    }
    if ($cSSLNutzen === 'P') {
        return 4;
    }
    if ($cSSLNutzen === 'Z') { // SSL durch Einstellung erlaubt?
        return 3;
    }

    return -1;
}

/**
 * https? wenn erwünscht reload mit https
 *
 * @return bool
 */
function pruefeHttps()
{
    $conf = Shop::getSettings(array(CONF_GLOBAL));
    if ($conf['global']['kaufabwicklung_ssl_nutzen'] === 'Z' && (!isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) !== 'on')) {
        $https = false;
        //Ausnahmen
        //hosteurope
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $_SERVER['HTTP_X_FORWARDED_HOST'] === 'ssl.webpack.de') {
            $https = true;
        }
        //strato
        if (isset($_SERVER['SCRIPT_URI']) && preg_match('/^ssl-id/', $_SERVER['SCRIPT_URI'])) {
            $https = true;
        }
        //1&1
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && preg_match('/^ssl/', $_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $https = true;
        }
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || intval($_SERVER['HTTPS'] === 1))) {
            $https = true;
        }
        if (!$https) {
            $lang = '';
            if (!standardspracheAktiv(true)) {
                if (strpos($_SERVER['REQUEST_URI'], '?')) {
                    $lang = '&lang=' . $_SESSION['cISOSprache'];
                } else {
                    $lang = '?lang=' . $_SESSION['cISOSprache'];
                }
            }
            $www = $conf['global']['global_ssl_www'];
            //www. schon im servernamen enthalten?
            if ($_SERVER['SERVER_NAME']{0} === 'w' && $_SERVER['SERVER_NAME']{1} === 'w' && $_SERVER['SERVER_NAME']{2} === 'w' && $_SERVER['SERVER_NAME']{3} === '.') {
                $www = '';
            }
            header('Location: https://' . $www . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $lang, true, 301);
            exit();
        }
    }

    return false;
}

/**
 *
 */
function loeseHttps()
{
    $conf = Shop::getSettings(array(CONF_GLOBAL));
    if ($conf['global']['kaufabwicklung_ssl_nutzen'] === 'Z' && (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) === 'on' || intval($_SERVER['HTTPS']) === 1))
        && !(isset($_GET['exclusive_content']) && intval($_GET['exclusive_content']) === 1)
        && (verifyGPDataString('isAjax') !== 'true')
    ) {
        $https = false;
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $_SERVER['HTTP_X_FORWARDED_HOST'] === 'ssl.webpack.de') {
            //hosteurope
            $https = true;
        } elseif (isset($_SERVER['SCRIPT_URI']) && preg_match('/^ssl-id/', $_SERVER['SCRIPT_URI'])) {
            //strato
            $https = true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && preg_match('/^ssl/', $_SERVER['HTTP_X_FORWARDED_HOST'])) {
            //1&1
            $https = true;
        } elseif (isset($conf['global']['kaufabwicklung_ssl_proxy']) && preg_match('/^' . $conf['global']['kaufabwicklung_ssl_proxy'] . '/', $_SERVER['HTTP_X_FORWARDED_HOST'])) {
            //ist proxy im fw host?
            $https = true;
        } elseif (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) === 'on' || intval($_SERVER['HTTPS']) === 1)) {
            $https = true;
        }
        if ($https) {
            $lang = '';
            if (!standardspracheAktiv(true)) {
                if (strpos($_SERVER['REQUEST_URI'], '?')) {
                    $lang = '&lang=' . $_SESSION['cISOSprache'];
                } else {
                    $lang = '?lang=' . $_SESSION['cISOSprache'];
                }
            }
            header('Location: http://' . $_SERVER['SERVER_NAME'] . str_replace($lang, '', $_SERVER['REQUEST_URI']) . $lang, true, 302);
            exit();
        }
    }
}

/**
 * @param int    $nAnzahlStellen
 * @param string $cString
 * @return bool|string
 */
function gibUID($nAnzahlStellen, $cString = '')
{
    $cUID            = '';
    $cSalt           = '';
    $cSaltBuchstaben = 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789';
    // Gen SALT
    for ($j = 0; $j < 30; $j++) {
        $cSalt .= substr($cSaltBuchstaben, mt_rand(0, strlen($cSaltBuchstaben) - 1), 1);
    }
    $cSalt = md5($cSalt);
    mt_srand();
    // Wurde ein String übergeben?
    if (strlen($cString) > 0) {
        // Hat der String Elemente?
        list($cString_arr) = explode(';', $cString);
        if (is_array($cString_arr) && count($cString_arr) > 0) {
            foreach ($cString_arr as $cString) {
                $cUID .= md5($cString . md5(PFAD_ROOT . (time() - mt_rand())));
            }

            $cUID = md5($cUID . $cSalt);
        } else {
            for ($i = 0; $i < strlen($cString); $i++) {
                $nPos = mt_rand(0, strlen($cString) - 1);
                if ((intval(date('w')) % 2) <= strlen($cString)) {
                    $nPos = (intval(date('w')) % 2);
                }
                $cUID .= md5(substr($cString, $nPos, 1) . $cSalt . md5(PFAD_ROOT . (microtime() - mt_rand())));
            }
        }
        $cUID = cryptPasswort($cUID . $cSalt);
    } else {
        $cUID = cryptPasswort(md5(pi() . $cSalt . md5((time() - mt_rand()))));
    }
    // Anzahl Stellen beachten
    if ($nAnzahlStellen > 0) {
        return substr($cUID, 0, $nAnzahlStellen);
    }

    return $cUID;
}

/**
 * @param string $cText
 * @return string
 */
function verschluesselXTEA($cText)
{
    if (strlen($cText) > 0) {
        $oXTEA = new XTEA(BLOWFISH_KEY);

        return $oXTEA->encrypt($cText);
    }

    return $cText;
}

/**
 * @param string $cText
 * @return string
 */
function entschluesselXTEA($cText)
{
    if (strlen($cText) > 0) {
        $oXTEA = new XTEA(BLOWFISH_KEY);

        return $oXTEA->decrypt($cText);
    }

    return $cText;
}

/**
 * Prüft ob eine die angegebende Email in temailblacklist vorhanden ist
 * Gibt true zurück, falls Email geblockt, ansonsten false
 *
 * @param string $cEmail
 * @return bool
 */
function pruefeEmailblacklist($cEmail)
{
    $cEmail = strtolower(StringHandler::filterXSS($cEmail));
    if (valid_email($cEmail)) {
        $Einstellungen = Shop::getSettings(array(CONF_EMAILBLACKLIST));
        if ($Einstellungen['emailblacklist']['blacklist_benutzen'] === 'Y') {
            // Emailblacklist benutzen?
            $oEmailBlackList_arr = Shop::DB()->query("SELECT cEmail FROM temailblacklist", 2);
            if (is_array($oEmailBlackList_arr) && count($oEmailBlackList_arr) > 0) {
                foreach ($oEmailBlackList_arr as $oEmailBlackList) {
                    if (strpos($oEmailBlackList->cEmail, '*') !== false) {
                        $cEmailBlackListRegEx = str_replace("*", "[a-z0-9\-\_\.\@\+]*", $oEmailBlackList->cEmail);
                        preg_match('/' . $cEmailBlackListRegEx . '/', $cEmail, $cTreffer_arr);
                        // Blocked
                        if (strlen($cEmail) === strlen($cTreffer_arr[0])) {
                            // Email schonmal geblockt worden?
                            $oEmailblacklistBlock = Shop::DB()->select('temailblacklistblock', 'cEmail', $cEmail);
                            if (!empty($oEmailblacklistBlock->cEmail)) {
                                $_upd                = new stdClass();
                                $_upd->dLetzterBlock = 'now()';
                                Shop::DB()->update('temailblacklistblock', 'cEmail', $cEmail, $_upd);
                            } else {
                                // temailblacklistblock Eintrag
                                $oEmailblacklistBlock                = new stdClass();
                                $oEmailblacklistBlock->cEmail        = $cEmail;
                                $oEmailblacklistBlock->dLetzterBlock = 'now()';
                                Shop::DB()->insert('temailblacklistblock', $oEmailblacklistBlock);
                            }

                            return true;
                        }
                    } elseif (strtolower($oEmailBlackList->cEmail) === strtolower($cEmail)) {
                        // Email schonmal geblockt worden?
                        $oEmailblacklistBlock = Shop::DB()->select('temailblacklistblock', 'cEmail', $cEmail);

                        if (!empty($oEmailblacklistBlock->cEmail)) {
                            $_upd                = new stdClass();
                            $_upd->dLetzterBlock = 'now()';
                            Shop::DB()->update('temailblacklistblock', 'cEmail', $cEmail, $_upd);
                        } else {
                            // temailblacklistblock Eintrag
                            $oEmailblacklistBlock                = new stdClass();
                            $oEmailblacklistBlock->cEmail        = $cEmail;
                            $oEmailblacklistBlock->dLetzterBlock = 'now()';
                            Shop::DB()->insert('temailblacklistblock', $oEmailblacklistBlock);
                        }

                        return true;
                    }
                }
            }

            return false;
        }

        return false;
    }

    return true;
}

/**
 * Preisanzeige Einstellungen holen
 *
 * @return array|mixed
 */
function holePreisanzeigeEinstellungen()
{
    $oPreisanzeigeConfTMP_arr = array();
    $oPreisanzeigeConf_arr    = Shop::DB()->query(
        "SELECT *
            FROM teinstellungen
            WHERE kEinstellungenSektion = " . CONF_PREISANZEIGE . "
            ORDER BY cName", 2
    );
    $cMapping_arr = array(
        'preisanzeige_preisgrafik_artikeldetails_anzeigen'    => 'Artikeldetails',
        'preisanzeige_preisgrafik_artikeluebersicht_anzeigen' => 'Artikeluebersicht',
        'preisanzeige_preisgrafik_boxen_anzeigen'             => 'Boxen',
        'preisanzeige_preisgrafik_startseite_anzeigen'        => 'Startseite',

        'preisanzeige_groesse_artikeldetails'                 => 'Artikeldetails',
        'preisanzeige_groesse_artikeluebersicht'              => 'Artikeluebersicht',
        'preisanzeige_groesse_boxen'                          => 'Boxen',
        'preisanzeige_groesse_startseite'                     => 'Startseite',

        'preisanzeige_farbe_artikeldetails'                   => 'Artikeldetails',
        'preisanzeige_farbe_artikeluebersicht'                => 'Artikeluebersicht',
        'preisanzeige_farbe_boxen'                            => 'Boxen',
        'preisanzeige_farbe_startseite'                       => 'Startseite',

        'preisanzeige_schriftart_artikeldetails'              => 'Artikeldetails',
        'preisanzeige_schriftart_artikeluebersicht'           => 'Artikeluebersicht',
        'preisanzeige_schriftart_boxen'                       => 'Boxen',
        'preisanzeige_schriftart_startseite'                  => 'Startseite');
    // Mapping
    if (is_array($oPreisanzeigeConf_arr) && count($oPreisanzeigeConf_arr) > 0) {
        foreach ($oPreisanzeigeConf_arr as $z => $oPreisanzeigeConf) {
            foreach ($cMapping_arr as $i => $cMapping) {
                if ($oPreisanzeigeConf->cName == $i) {
                    $oPreisanzeigeConfTMP_arr[$cMapping][] = $oPreisanzeigeConf;
                }
            }
        }
    } else {
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_preisgrafik_artikeldetails_anzeigen', 'N', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_preisgrafik_artikeluebersicht_anzeigen', 'N', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_preisgrafik_boxen_anzeigen', 'N', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_preisgrafik_startseite_anzeigen', 'N', NULL)", 4);

        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_groesse_artikeldetails', '18', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_groesse_artikeluebersicht', '18', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_groesse_boxen', '18', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_groesse_startseite', '18', NULL)", 4);

        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_farbe_artikeldetails', '#000000', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_farbe_artikeluebersicht', '#000000', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_farbe_boxen', '#000000', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_farbe_startseite', '#000000', NULL)", 4);

        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_schriftart_artikeldetails', 'GeosansLight.ttf', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_schriftart_artikeluebersicht', 'GeosansLight.ttf', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_schriftart_boxen', 'GeosansLight.ttf', NULL)", 4);
        Shop::DB()->query("INSERT INTO teinstellungen VALUES(" . CONF_PREISANZEIGE . ", 'preisanzeige_schriftart_startseite', 'GeosansLight.ttf', NULL)", 4);

        $oPreisanzeigeConf_arr = Shop::DB()->query(
            "SELECT *
                FROM teinstellungen
                WHERE kEinstellungenSektion = '" . CONF_PREISANZEIGE . "' ORDER BY cName ASC", 2
        );
        foreach ($oPreisanzeigeConf_arr as $z => $oPreisanzeigeConf) {
            foreach ($cMapping_arr as $i => $cMapping) {
                if ($oPreisanzeigeConf->cName == $i) {
                    $oPreisanzeigeConfTMP_arr[$cMapping][] = $oPreisanzeigeConf;
                }
            }
        }
    }
    $oPreisanzeigeConf_arr = $oPreisanzeigeConfTMP_arr;

    return $oPreisanzeigeConf_arr;
}

/**
 * @param string $cMail
 * @param string $cBestellNr
 * @return null|TrustedShops
 */
function gibTrustedShopsBewertenButton($cMail, $cBestellNr)
{
    $oURLTrustedShopsBewerten = null;
    if (strlen($cMail) > 0 && strlen($cBestellNr) > 0) {
        require_once PFAD_ROOT . PFAD_CLASSES . 'class.JTL-Shop.TrustedShops.php';

        $cValidSprachISO_arr = array('de', 'en', 'fr', 'pl', 'es');
        if (in_array(StringHandler::convertISO2ISO639($_SESSION['cISOSprache']), $cValidSprachISO_arr)) {
            $oTrustedShops                = new TrustedShops(-1, StringHandler::convertISO2ISO639($_SESSION['cISOSprache']));
            $oTrustedShopsKundenbewertung = $oTrustedShops->holeKundenbewertungsstatus(StringHandler::convertISO2ISO639($_SESSION['cISOSprache']));

            if ($oTrustedShopsKundenbewertung && strlen($oTrustedShopsKundenbewertung->cTSID) > 0 &&
                $oTrustedShopsKundenbewertung->kTrustedshopsKundenbewertung > 0 && $oTrustedShopsKundenbewertung->nStatus == 1
            ) {
                $shopUrl                = Shop::getURL();
                $template               = Template::getInstance();
                $cTemplate              = $template->getDir();
                $cURLTSBewertungPIC_arr = array(
                    'de' => $shopUrl . '/' . PFAD_TEMPLATES . $cTemplate . '/themes/base/images/trustedshops/rate_now_de.png',
                    'en' => $shopUrl . '/' . PFAD_TEMPLATES . $cTemplate . '/themes/base/images/trustedshops/rate_now_en.png',
                    'fr' => $shopUrl . '/' . PFAD_TEMPLATES . $cTemplate . '/themes/base/images/trustedshops/rate_now_fr.png',
                    'es' => $shopUrl . '/' . PFAD_TEMPLATES . $cTemplate . '/themes/base/images/trustedshops/rate_now_es.png',
                    'nl' => $shopUrl . '/' . PFAD_TEMPLATES . $cTemplate . '/themes/base/images/trustedshops/rate_now_nl.png',
                    'pl' => $shopUrl . '/' . PFAD_TEMPLATES . $cTemplate . '/themes/base/images/trustedshops/rate_now_pl.png');

                $oURLTrustedShopsBewerten->cURL = "https://www.trustedshops.com/buyerrating/rate_{$oTrustedShopsKundenbewertung->cTSID}.html&buyerEmail=" .
                    urlencode(base64_encode($cMail)) . "&shopOrderID=" . urlencode(base64_encode($cBestellNr));
                $oURLTrustedShopsBewerten->cPicURL = $cURLTSBewertungPIC_arr[StringHandler::convertISO2ISO639($_SESSION['cISOSprache'])];
            }
        }
    }

    return $oURLTrustedShopsBewerten;
}

/**
 * Holt die Globalen Metaangaben und Return diese als Assoc Array wobei die Keys => kSprache sind
 *
 * @return array|mixed
 */
function holeGlobaleMetaAngaben()
{
    $cacheID = 'jtl_glob_meta';
    if (($oGlobaleMetaAngaben_arr = Shop::Cache()->get($cacheID)) !== false) {
        return $oGlobaleMetaAngaben_arr;
    }
    $oGlobaleMetaAngaben_arr    = array();
    $oGlobaleMetaAngabenTMP_arr = Shop::DB()->query("SELECT * FROM tglobalemetaangaben ORDER BY kSprache", 2);
    if (is_array($oGlobaleMetaAngabenTMP_arr) && count($oGlobaleMetaAngabenTMP_arr) > 0) {
        foreach ($oGlobaleMetaAngabenTMP_arr as $oGlobaleMetaAngabenTMP) {
            if (!isset($oGlobaleMetaAngaben_arr[$oGlobaleMetaAngabenTMP->kSprache])) {
                $oGlobaleMetaAngaben_arr[$oGlobaleMetaAngabenTMP->kSprache] = new stdClass();
            }
            $cName                                                              = $oGlobaleMetaAngabenTMP->cName;
            $oGlobaleMetaAngaben_arr[$oGlobaleMetaAngabenTMP->kSprache]->$cName = $oGlobaleMetaAngabenTMP->cWertName;
        }
    }
    Shop::Cache()->set($cacheID, $oGlobaleMetaAngaben_arr, array(CACHING_GROUP_CORE));

    return $oGlobaleMetaAngaben_arr;
}

/**
 * @return array
 */
function holeExcludedKeywords()
{
    $oExcludedKeywords_arr    = array();
    $oExcludedKeywordsTMP_arr = Shop::DB()->query("SELECT * FROM texcludekeywords ORDER BY cISOSprache", 2);
    if (is_array($oExcludedKeywordsTMP_arr) && count($oExcludedKeywordsTMP_arr) > 0) {
        foreach ($oExcludedKeywordsTMP_arr as $oExcludedKeywordsTMP) {
            $oExcludedKeywords_arr[$oExcludedKeywordsTMP->cISOSprache] = $oExcludedKeywordsTMP;
        }
    }

    return $oExcludedKeywords_arr;
}

/**
 * Erhält einen String aus dem alle nicht erlaubten Wörter rausgefiltert werden
 *
 * @param string $cString
 * @param array  $oExcludesKeywords_arr
 * @return mixed
 */
function gibExcludesKeywordsReplace($cString, $oExcludesKeywords_arr)
{
    if (is_array($oExcludesKeywords_arr) && count($oExcludesKeywords_arr) > 0) {
        foreach ($oExcludesKeywords_arr as $i => $oExcludesKeywords) {
            $oExcludesKeywords_arr[$i] = ' ' . $oExcludesKeywords . ' ';
        }

        return str_replace($oExcludesKeywords_arr, ' ', $cString);
    }

    return $cString;
}

/**
 * gibt alle Sprachen zurück
 *
 * @param int $nOption
 * 0 = Normales Array
 * 1 = Gib ein Assoc mit Key = kSprache
 * 2 = Gib ein Assoc mit Key = cISO
 * @return array
 */
function gibAlleSprachen($nOption = 0)
{
    if (isset($_SESSION['Sprachen']) && is_array($_SESSION['Sprachen']) && count($_SESSION['Sprachen']) > 0) {
        switch ($nOption) {
            case 0 :
                return $_SESSION['Sprachen'];
                break;

            case 1 :
                return baueAssocArray($_SESSION['Sprachen'], 'kSprache');
                break;

            case 2 :
                return baueAssocArray($_SESSION['Sprachen'], 'cISO');
                break;

        }
    } else {
        $oSprach_arr = Shop::DB()->query("SELECT * FROM tsprache ORDER BY cShopStandard DESC, cNameDeutsch", 2);

        switch ($nOption) {
            case 0 :
                return $oSprach_arr;
                break;

            case 1 :
                return baueAssocArray($oSprach_arr, 'kSprache');
                break;

            case 2 :
                return baueAssocArray($oSprach_arr, 'cISO');
                break;

        }
    }

    return array();
}

/**
 * @param string $cURL
 * @return bool
 */
function pruefeSOAP($cURL = '')
{
    if (strlen($cURL) > 0) {
        if (!phpLinkCheck($cURL)) {
            return false;
        }
    }
    if (class_exists('SoapClient')) {
        return true;
    }

    return false;
}

/**
 * @param string $cURL
 * @return bool
 */
function pruefeCURL($cURL = '')
{
    if (strlen($cURL) > 0) {
        if (!phpLinkCheck($cURL)) {
            return false;
        }
    }
    if (function_exists('curl_init')) {
        return true;
    }

    return false;
}

/**
 * @return bool
 */
function pruefeALLOWFOPEN()
{
    if (intval(ini_get('allow_url_fopen')) === 1) {
        return true;
    }

    return false;
}

/**
 * @param string $cSOCKETS
 * @return bool
 */
function pruefeSOCKETS($cSOCKETS = '')
{
    if (strlen($cSOCKETS) > 0) {
        if (!phpLinkCheck($cSOCKETS)) {
            return false;
        }
    }
    if (function_exists('fsockopen')) {
        return true;
    }

    return false;
}

/**
 * @param string $url
 * @return bool
 */
function phpLinkCheck($url)
{
    $url = parse_url(trim($url));
    if (strtolower($url['scheme']) !== 'http' && strtolower($url['scheme']) !== 'https') {
        return false;
    }
    if (!isset($url['port'])) {
        $url['port'] = 80;
    }
    if (!isset($url['path'])) {
        $url['path'] = '/';
    }
    $fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30);

    if (!$fp) {
        return false;
    }

    return true;
}

/**
 * @param Kategorie $Kategorie
 * @param int       $kKundengruppe
 * @param int       $kSprache
 * @param bool      $bString
 * @return array|string
 */
function gibKategoriepfad($Kategorie, $kKundengruppe, $kSprache, $bString = true)
{
    $pfad                   = '';
    $cPfad_arr              = array();
    $AufgeklappteKategorien = new KategorieListe();
    $AufgeklappteKategorien->getOpenCategories($Kategorie, $kKundengruppe, $kSprache);
    if (is_array($AufgeklappteKategorien->elemente)) {
        for ($i = count($AufgeklappteKategorien->elemente) - 1; $i >= 0; $i--) {
            if ($bString) {
                $pfad .= $AufgeklappteKategorien->elemente[$i]->cName;
                if ($i > 0) {
                    $pfad .= ' > ';
                }
            } else {
                $cPfad_arr[] = $AufgeklappteKategorien->elemente[$i]->cName;
            }
        }
    }

    if ($bString) {
        return $pfad;
    }

    return $cPfad_arr;
}

/**
 * @param float $fSumme
 * @return string
 */
function formatCurrency($fSumme)
{
    $fSumme    = doubleval($fSumme);
    $fSummeABS = null;
    $fCents    = null;
    if ($fSumme > 0) {
        $fSummeABS = abs($fSumme);
        $fSumme    = floor($fSumme * 100);
        $fCents    = $fSumme % 100;
        $fSumme    = strval(floor($fSumme / 100));
        if ($fCents < 10) {
            $fCents = '0' . $fCents;
        }
        for ($i = 0; $i < floor((strlen($fSumme) - (1 + $i)) / 3); $i++) {
            $fSumme = substr($fSumme, 0, strlen($fSumme) - (4 * $i + 3)) . '.' . substr($fSumme, 0, strlen($fSumme) - (4 * $i + 3));
        }
    }

    return ((($fSummeABS) ? '' : '-') . $fSumme . ',' . $fCents);
}

/**
 * Mapped die Suchspecial Einstellungen und liefert die Einstellungswerte als Assoc Array zurück.
 * Das Array kann via kKey Assoc angesprochen werden.
 *
 * @param array $oSuchspecialEinstellung_arr
 * @return array
 */
function gibSuchspecialEinstellungMapping($oSuchspecialEinstellung_arr)
{
    $oEinstellungen_arr = array();
    if (is_array($oSuchspecialEinstellung_arr) && count($oSuchspecialEinstellung_arr) > 0) {
        foreach ($oSuchspecialEinstellung_arr as $key => $oSuchspecialEinstellung) {
            switch ($key) {
                case 'suchspecials_sortierung_bestseller':
                    $oEinstellungen_arr[SEARCHSPECIALS_BESTSELLER] = $oSuchspecialEinstellung;
                    break;
                case 'suchspecials_sortierung_sonderangebote':
                    $oEinstellungen_arr[SEARCHSPECIALS_SPECIALOFFERS] = $oSuchspecialEinstellung;
                    break;
                case 'suchspecials_sortierung_neuimsortiment':
                    $oEinstellungen_arr[SEARCHSPECIALS_NEWPRODUCTS] = $oSuchspecialEinstellung;
                    break;
                case 'suchspecials_sortierung_topangebote':
                    $oEinstellungen_arr[SEARCHSPECIALS_TOPOFFERS] = $oSuchspecialEinstellung;
                    break;
                case 'suchspecials_sortierung_inkuerzeverfuegbar':
                    $oEinstellungen_arr[SEARCHSPECIALS_UPCOMINGPRODUCTS] = $oSuchspecialEinstellung;
                    break;
                case 'suchspecials_sortierung_topbewertet':
                    $oEinstellungen_arr[SEARCHSPECIALS_TOPREVIEWS] = $oSuchspecialEinstellung;
                    break;
            }
        }
    }

    return $oEinstellungen_arr;
}

/**
 * Bekommmt ein Array von Objekten und baut ein assoziatives Array
 *
 * @param array $oObjekt_arr
 * @param string $cKey
 * @return array
 */
function baueAssocArray($oObjekt_arr, $cKey)
{
    $oObjektAssoc_arr = array();
    if (is_array($oObjekt_arr) && count($oObjekt_arr) > 0 && strlen($cKey) > 0) {
        foreach ($oObjekt_arr as $oObjekt) {
            if (is_object($oObjekt)) {
                $oMember_arr = array_keys(get_object_vars($oObjekt));
                if (is_array($oMember_arr) && count($oMember_arr) > 0) {
                    $oObjektAssoc_arr[$oObjekt->$cKey] = new stdClass();
                    foreach ($oMember_arr as $oMember) {
                        $oObjektAssoc_arr[$oObjekt->$cKey]->$oMember = $oObjekt->$oMember;
                    }
                }
            }
        }
    }

    return $oObjektAssoc_arr;
}

/**
 * @param string $cAnrede
 * @param int    $kSprache
 * @param int    $kKunde
 * @return mixed
 */
function mappeKundenanrede($cAnrede, $kSprache, $kKunde = 0)
{
    if (strlen($cAnrede) > 0 && ($kSprache > 0 || $kKunde > 0)) {
        if ($kSprache == 0 && $kKunde > 0) {
            $oKunde = Shop::DB()->query(
                "SELECT kSprache
                    FROM tkunde
                    WHERE kKunde = " . intval($kKunde), 1
            );
            if (isset($oKunde->kSprache) && $oKunde->kSprache > 0) {
                $kSprache = $oKunde->kSprache;
            }
        }
        $cISOSprache = '';
        if ($kSprache > 0) { // Nimm die Sprache vom Kunden, falls gesetzt
            $oSprache = Shop::DB()->query(
                "SELECT kSprache, cISO
                    FROM tsprache
                    WHERE kSprache = " . intval($kSprache), 1
            );
            if (isset($oSprache->kSprache) && $oSprache->kSprache > 0) {
                $cISOSprache = $oSprache->cISO;
            }
        } else { // Ansonsten nimm die Standard Sprache
            $oSprache = Shop::DB()->query(
                "SELECT kSprache, cISO
                    FROM tsprache
                    WHERE cShopStandard = 'Y'", 1
            );
            if (isset($oSprache->kSprache) && $oSprache->kSprache > 0) {
                $cISOSprache = $oSprache->cISO;
            }
        }
        $cName       = ($cAnrede === 'm') ? 'salutationM' : 'salutationW';
        $oSprachWert = Shop::DB()->query(
            "SELECT tsprachwerte.cWert
                FROM tsprachwerte
                JOIN tsprachiso ON tsprachiso.cISO = '" . $cISOSprache . "'
                WHERE tsprachwerte.kSprachISO = tsprachiso.kSprachISO
                    AND tsprachwerte.cName = '" . $cName . "'", 1
        );
        if (isset($oSprachWert->cWert) && strlen($oSprachWert->cWert) > 0) {
            $cAnrede = $oSprachWert->cWert;
        }
    }

    return $cAnrede;
}

/**
 *
 */
function pruefeKampagnenParameter()
{
    $campaigns = Kampagne::getAvailable();
    if (count($campaigns) > 0 && isset($_SESSION['oBesucher']) && $_SESSION['oBesucher']->kBesucher > 0) {
        $bKampagnenHit = false;
        foreach ($campaigns as $oKampagne) {
            // Wurde für die aktuelle Kampagne der Parameter via GET oder POST uebergeben?
            if (strlen(verifyGPDataString($oKampagne->cParameter)) > 0) {
                // Wurde bei nicht dynamischen Kampagnen der richtige Wert uebergeben?
                if (isset($oKampagne->nDynamisch) && ($oKampagne->nDynamisch == 1 ||
                        ($oKampagne->nDynamisch == 0 &&
                            isset($oKampagne->cWert) && strtolower($oKampagne->cWert) === strtolower(verifyGPDataString($oKampagne->cParameter))))
                ) {
                    $referrer = gibReferer();
                    //wurde der HIT für diesen Besucher schon gezaehlt?
                    $oVorgang = Shop::DB()->query(
                        "SELECT kKampagneVorgang
                            FROM tkampagnevorgang
                            WHERE kKampagneDef = " . KAMPAGNE_DEF_HIT . "
                                AND kKampagne = " . intval($oKampagne->kKampagne) . "
                                AND kKey = " . intval($_SESSION['oBesucher']->kBesucher) . "
                                AND cCustomData = '" . (StringHandler::filterXSS(Shop::DB()->escape($_SERVER['REQUEST_URI'])) . ';' . $referrer) . "'
                            LIMIT 1", 1
                    ); //#3622 - added cCustomData to where clause
                    if (!isset($oVorgang->kKampagneVorgang)) {
                        $oKampagnenVorgang               = new stdClass();
                        $oKampagnenVorgang->kKampagne    = $oKampagne->kKampagne;
                        $oKampagnenVorgang->kKampagneDef = KAMPAGNE_DEF_HIT;
                        $oKampagnenVorgang->kKey         = $_SESSION['oBesucher']->kBesucher;
                        $oKampagnenVorgang->fWert        = 1.0;
                        $oKampagnenVorgang->cParamWert   = verifyGPDataString($oKampagne->cParameter);
                        $oKampagnenVorgang->cCustomData  = StringHandler::filterXSS($_SERVER['REQUEST_URI']) . ';' . $referrer;
                        if ($oKampagne->nDynamisch == 0) {
                            $oKampagnenVorgang->cParamWert = $oKampagne->cWert;
                        }
                        $oKampagnenVorgang->dErstellt = 'now()';

                        Shop::DB()->insert('tkampagnevorgang', $oKampagnenVorgang);
                        // Kampagnenbesucher in die Session
                        $_SESSION['Kampagnenbesucher'] = new stdClass();
                        $_SESSION['Kampagnenbesucher'] = $oKampagne;

                        break;
                    }
                }
            }

            if (!$bKampagnenHit) { // Kein Kampagnentreffer
                // Besucher kommt von Google und hat vorher keine Kampagne getroffen?
                if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '.google.') !== false) {
                    $oVorgang = Shop::DB()->query(
                        "SELECT tkampagnevorgang.kKampagneVorgang
                            FROM tkampagnevorgang
                            WHERE tkampagnevorgang.kKampagneDef = " . KAMPAGNE_DEF_HIT . "
                                AND tkampagnevorgang.kKampagne = " . KAMPAGNE_INTERN_GOOGLE . "
                                AND tkampagnevorgang.kKey = " . intval($_SESSION['oBesucher']->kBesucher) . "
                            LIMIT 1", 1
                    );

                    if (!isset($oVorgang->kKampagneVorgang)) {
                        $oKampagne                       = new Kampagne(KAMPAGNE_INTERN_GOOGLE);
                        $oKampagnenVorgang               = new stdClass();
                        $oKampagnenVorgang->kKampagne    = KAMPAGNE_INTERN_GOOGLE;
                        $oKampagnenVorgang->kKampagneDef = KAMPAGNE_DEF_HIT;
                        $oKampagnenVorgang->kKey         = $_SESSION['oBesucher']->kBesucher;
                        $oKampagnenVorgang->fWert        = 1.0;
                        $oKampagnenVorgang->cParamWert   = $oKampagne->cWert;
                        $oKampagnenVorgang->dErstellt    = 'now()';

                        Shop::DB()->insert('tkampagnevorgang', $oKampagnenVorgang);
                        // Kampagnenbesucher in die Session
                        $_SESSION['Kampagnenbesucher'] = new stdClass();
                        $_SESSION['Kampagnenbesucher'] = $oKampagne;
                    }
                }
            }
        }
    }
}

/**
 * @param int $kKampagneDef
 * @param int $kKey
 * @param float $fWert
 * @return int
 */
function setzeKampagnenVorgang($kKampagneDef, $kKey, $fWert)
{
    if ($kKampagneDef > 0 && $kKey > 0 && $fWert > 0 && isset($_SESSION['Kampagnenbesucher'])) {
        $oKampagnenVorgang               = new stdClass();
        $oKampagnenVorgang->kKampagne    = $_SESSION['Kampagnenbesucher']->kKampagne;
        $oKampagnenVorgang->kKampagneDef = $kKampagneDef;
        $oKampagnenVorgang->kKey         = $kKey;
        $oKampagnenVorgang->fWert        = $fWert;
        $oKampagnenVorgang->cParamWert   = $_SESSION['Kampagnenbesucher']->cWert;
        $oKampagnenVorgang->dErstellt    = 'now()';

        return Shop::DB()->insert('tkampagnevorgang', $oKampagnenVorgang);
    }

    return 0;
}

/**
 * YYYY-MM-DD HH:MM:SS, YYYY-MM-DD, now oder now()
 *
 * @param string $cDatum
 * @return array
 */
function gibDatumTeile($cDatum)
{
    $date_arr = array();
    if (strlen($cDatum) > 0) {
        if ($cDatum === 'now()') {
            $cDatum = 'now';
        }
        try {
            $date                 = new DateTime($cDatum);
            $date_arr['cDatum']   = $date->format('Y-m-d');
            $date_arr['cZeit']    = $date->format('H:m:s');
            $date_arr['cJahr']    = $date->format('Y');
            $date_arr['cMonat']   = $date->format('m');
            $date_arr['cTag']     = $date->format('d');
            $date_arr['cStunde']  = $date->format('H');
            $date_arr['cMinute']  = $date->format('i');
            $date_arr['cSekunde'] = $date->format('s');
        } catch (Exception $e) {
        }
    }

    return $date_arr;
}

/**
 * @param int $nSeitentyp
 * @return string
 */
function mappeSeitentyp($nSeitentyp)
{
    if (intval($nSeitentyp) > 0) {
        switch (intval($nSeitentyp)) {
            case PAGE_UNBEKANNT :
                return 'Unbekannt';
                break;

            case PAGE_ARTIKEL :
                return 'Artikeldetails';
                break;

            case PAGE_ARTIKELLISTE :
                return 'Artikelliste';
                break;

            case PAGE_WARENKORB :
                return 'Warenkorb';
                break;

            case PAGE_MEINKONTO :
                return 'Mein Konto';
                break;

            case PAGE_KONTAKT :
                return 'Kontakt';
                break;

            case PAGE_UMFRAGE :
                return 'Umfrage';
                break;

            case PAGE_NEWS :
                return 'News';
                break;

            case PAGE_NEWSLETTER :
                return 'Newsletter';
                break;

            case PAGE_LOGIN :
                return 'Login';
                break;

            case PAGE_REGISTRIERUNG :
                return 'Registrierung';
                break;

            case PAGE_BESTELLVORGANG :
                return 'Bestellvorgang';
                break;

            case PAGE_BEWERTUNG :
                return 'Bewertung';
                break;

            case PAGE_DRUCKANSICHT :
                return 'Druckansicht';
                break;

            case PAGE_PASSWORTVERGESSEN :
                return 'Passwort vergessen';
                break;

            case PAGE_WARTUNG :
                return 'Wartung';
                break;

            case PAGE_WUNSCHLISTE :
                return 'Wunschliste';
                break;

            case PAGE_VERGLEICHSLISTE :
                return 'Vergleichsliste';
                break;

            case PAGE_STARTSEITE :
                return 'Startseite';
                break;

            case PAGE_VERSAND :
                return 'Versand';
                break;

            case PAGE_AGB :
                return 'AGB';
                break;

            case PAGE_DATENSCHUTZ :
                return 'Datenschutz';
                break;

            case PAGE_TAGGING :
                return 'Tagging';
                break;

            case PAGE_LIVESUCHE :
                return 'Livesuche';
                break;

            case PAGE_HERSTELLER :
                return 'Hersteller';
                break;

            case PAGE_SITEMAP :
                return 'Sitemap';
                break;

            case PAGE_GRATISGESCHENK :
                return 'Gratis Geschenk ';
                break;

            case PAGE_WRB :
                return 'WRB';
                break;

            case PAGE_PLUGIN :
                return 'Plugin';
                break;

            case PAGE_NEWSLETTERARCHIV :
                return 'Newsletterarchiv';
                break;

            case PAGE_EIGENE :
                return 'Eigene Seite';
                break;

        }
    }

    return '';
}

/**
 *
 */
function pruefeZahlungsartNutzbarkeit()
{
    $oZahlungsart_arr = Shop::DB()->query("SELECT * FROM tzahlungsart WHERE nActive = 1", 2);
    if (is_array($oZahlungsart_arr) && count($oZahlungsart_arr) > 0) {
        foreach ($oZahlungsart_arr as $oZahlungsart) {
            // Bei SOAP oder CURL => versuche die Zahlungsart auf nNutzbar = 1 zu stellen, falls nicht schon geschehen
            if ($oZahlungsart->nSOAP == 1 || $oZahlungsart->nCURL == 1 || $oZahlungsart->nSOCKETS == 1) {
                aktiviereZahlungsart($oZahlungsart);
            }
        }
    }
}

/**
 * Bei SOAP oder CURL => versuche die Zahlungsart auf nNutzbar = 1 zu stellen, falls nicht schon geschehen
 *
 * @param Zahlungsart|object $oZahlungsart
 * @return bool
 */
function aktiviereZahlungsart($oZahlungsart)
{
    if ($oZahlungsart->kZahlungsart > 0) {
        $kZahlungsart = (int) $oZahlungsart->kZahlungsart;
        $nNutzbar     = 0;
        // SOAP
        if ($oZahlungsart->nSOAP) {
            $nNutzbar = (pruefeSOAP()) ? 1 : 0;
        }
        // CURL
        if (isset($oZahlungsart->nCURL) && $oZahlungsart->nCURL) {
            $nNutzbar = (pruefeCURL()) ? 1 : 0;
        }
        // SOCKETS
        if (isset($oZahlungsart->nSOCKETS) && $oZahlungsart->nSOCKETS) {
            $nNutzbar = (pruefeSOCKETS()) ? 1 : 0;
        }
        // ALLOW URL FOPEN
        if (isset($oZahlungsart->nALLOWURLFOPEN) && $oZahlungsart->nALLOWURLFOPEN) {
            $nNutzbar = (pruefeALLOWFOPEN()) ? 1 : 0;
        }
        Shop::DB()->query("UPDATE tzahlungsart SET nNutzbar = " . $nNutzbar . " WHERE kZahlungsart = " . $kZahlungsart, 4);
    }

    return false;
}

/**
 * Besucher nach 3 Std in Besucherarchiv verschieben
 */
function archiviereBesucher()
{
    Shop::DB()->query(
        "INSERT INTO tbesucherarchiv
            (kBesucher, kKunde, kBestellung, cReferer, cEinstiegsseite, cBrowser, cAusstiegsseite, nBesuchsdauer, kBesucherBot, dZeit)
            SELECT kBesucher, kKunde, kBestellung, cReferer, cEinstiegsseite, cBrowser, cAusstiegsseite, (UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(dLetzteAktivitaet))
              AS nLetzteAktivitaetStamp, kBesucherBot, dZeit 
              FROM tbesucher 
              WHERE dLetzteAktivitaet <= date_sub(now(),INTERVAL 3 HOUR)", 4
    );
    Shop::DB()->query("DELETE FROM tbesucher WHERE dLetzteAktivitaet <= date_sub(now(),INTERVAL 3 HOUR)", 4);
}

/**
 * @param string $cISO
 * @param int    $kSprache
 * @return bool
 */
function gibSprachKeyISO($cISO = '', $kSprache = 0)
{
    if (strlen($cISO) > 0) {
        $oSprache = Shop::DB()->select('tsprache', 'cISO', StringHandler::filterXSS($cISO));

        if (isset($oSprache->kSprache) && $oSprache->kSprache > 0) {
            return $oSprache->kSprache;
        }
    } elseif (intval($kSprache) > 0) {
        $oSprache = Shop::DB()->query(
            "SELECT cISO
                FROM tsprache
                WHERE kSprache = " . intval($kSprache), 1
        );

        if (isset($oSprache->cISO) && strlen($oSprache->cISO) > 0) {
            return $oSprache->cISO;
        }
    }

    return false;
}

/**
 * @param bool $cache
 * @return int
 */
function getSytemlogFlag($cache = true)
{
    $conf = Shop::getSettings(array(CONF_GLOBAL));
    if ($cache === true && isset($conf['global']['systemlog_flag'])) {
        return intval($conf['global']['systemlog_flag']);
    }
    $conf = Shop::DB()->query("SELECT cWert FROM teinstellungen WHERE cName = 'systemlog_flag'", 1);
    if (isset($conf->cWert)) {
        return intval($conf->cWert);
    }

    return 0;
}

/**
 * @param $gesamtsumme
 * @return float
 */
function optionaleRundung($gesamtsumme)
{
    $conf = Shop::getSettings(array(CONF_KAUFABWICKLUNG));
    if (isset($conf['kaufabwicklung']['bestellabschluss_runden5']) && $conf['kaufabwicklung']['bestellabschluss_runden5'] == 1) {
        $int          = intval($gesamtsumme * 100);
        $letzteStelle = $int % 10;
        if ($letzteStelle < 3) {
            $int -= $letzteStelle;
        } elseif ($letzteStelle > 2 && $letzteStelle < 8) {
            $int = $int - $letzteStelle + 5;
        } elseif ($letzteStelle > 7) {
            $int = $int - $letzteStelle + 10;
        }

        return $int / 100;
    }

    return $gesamtsumme;
}

/**
 * @param string $dir
 * @return bool
 */
function delDirRecursively($dir)
{
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    $res = true;
    foreach ($iterator as $fileInfo) {
        $fileName = $fileInfo->getFilename();
        if ($fileName !== '.gitignore' && $fileName !== '.gitkeep') {
            $func = ($fileInfo->isDir() ? 'rmdir' : 'unlink');
            $res  = $res && $func($fileInfo->getRealPath());
        }
    }

    return $res;
}

/**
 * @param object $oObj
 * @return mixed
 */
function deepCopy($oObj)
{
    return unserialize(serialize($oObj));
}

/**
 * @param Resource $ch
 * @param null|int $maxredirect
 * @return bool|mixed
 */
function curl_exec_follow($ch, &$maxredirect = 5)
{
    $mr = $maxredirect === null ? 5 : intval($maxredirect);
    if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
        curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
    } else {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        if ($mr > 0) {
            $newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

            $rch = curl_copy_handle($ch);
            curl_setopt($rch, CURLOPT_HEADER, true);
            curl_setopt($rch, CURLOPT_NOBODY, true);
            curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
            curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
            do {
                curl_setopt($rch, CURLOPT_URL, $newurl);
                $header = curl_exec($rch);
                if (curl_errno($rch)) {
                    $code = 0;
                } else {
                    $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
                    if ($code == 301 || $code == 302) {
                        preg_match('/Location:(.*?)\n/', $header, $matches);
                        $newurl = trim(array_pop($matches));
                    } else {
                        $code = 0;
                    }
                }
            } while ($code && --$mr);
            curl_close($rch);
            if (!$mr) {
                if ($maxredirect === null) {
                    trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING);
                } else {
                    $maxredirect = 0;
                }

                return false;
            }
            curl_setopt($ch, CURLOPT_URL, $newurl);
        }
    }

    return curl_exec($ch);
}

/**
 * @param string $cURL
 * @param int    $nTimeout
 * @param null   $cPost
 * @return mixed|string
 */
function http_get_contents($cURL, $nTimeout = 15, $cPost = null)
{
    $nCode = 0;
    $cData = '';

    if (function_exists('curl_init')) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $cURL);
        curl_setopt($curl, CURLOPT_TIMEOUT, $nTimeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_REFERER, Shop::getURL());

        if (!is_null($cPost)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $cPost);
        }

        $cData     = curl_exec_follow($curl);
        $cInfo_arr = curl_getinfo($curl);
        $nCode     = intval($cInfo_arr['http_code']);

        curl_close($curl);
    } elseif (ini_get('allow_url_fopen')) {
        @ini_set('default_socket_timeout', $nTimeout);
        $fileHandle = @fopen($cURL, 'r');
        if ($fileHandle) {
            @stream_set_timeout($fileHandle, $nTimeout);

            $cData = '';
            while (($buffer = fgets($fileHandle)) !== false) {
                $cData .= $buffer;
            }
            if (preg_match('|HTTP/\d\.\d\s+(\d+)\s+.*|', $http_response_header[0], $match)) {
                $nCode = intval($match[1]);
            }
            fclose($fileHandle);
        }
    }
    if (!($nCode >= 200 && $nCode < 300)) {
        $cData = '';
    }

    return $cData;
}

/**
 * @param string|array|object $xData
 * @param bool                $bEncode
 * @return array
 */
function utf8_convert_recursive($xData, $bEncode = true)
{
    if (is_string($xData)) {
        $cEncoding = mb_detect_encoding($xData, 'UTF-8, ISO-8859-1, ISO-8859-15', true);
        $bUTF8     = (strtoupper($cEncoding) === 'UTF-8');
        if (($bEncode && $bUTF8) || (!$bUTF8 && !$bEncode)) {
            return $xData;
        }

        return $bEncode ? utf8_encode($xData) : utf8_decode($xData);
    }
    if (is_object($xData)) {
        $xData_arr = get_object_vars($xData);
        $xNewData  = $xData;
        foreach ($xData_arr as $cKey => $xTmp) {
            $tmp    = utf8_convert_recursive($xNewData->$cKey, $bEncode);
            $newKey = utf8_convert_recursive($cKey, $bEncode);
            unset($xNewData->$cKey);
            $xNewData->$newKey = $tmp;
            unset($tmp);
        }

        return $xNewData;
    }
    if (!is_array($xData)) {
        return $xData;
    }
    $xData_rr = array();
    foreach ($xData as $cKey => $xTmp) {
        $cKey            = utf8_convert_recursive($cKey, $bEncode);
        $xData_rr[$cKey] = utf8_convert_recursive($xTmp, $bEncode);
    }

    return $xData_rr;
}

/**
 * @param object $NaviFilter
 * @param int    $nAnzahl
 * @param bool   $bSeo
 */
function doMainwordRedirect($NaviFilter, $nAnzahl, $bSeo = false)
{
    $cMainword_arr = array(
        'Kategorie'   => array(
                'cKey'   => 'kKategorie',
                'cParam' => 'k'
            ),
        'Hersteller'  => array(
            'cKey'   => 'kHersteller',
            'cParam' => 'h'
        ),
        'Suchanfrage' => array(
            'cKey'   => 'kSuchanfrage',
            'cParam' => 'l'
        ),
        'MerkmalWert' => array(
            'cKey'   => 'kMerkmalWert',
            'cParam' => 'm'
        ),
        'Tag'         => array(
            'cKey'   => 'kTag',
            'cParam' => 't'
        ),
        'Suchspecial' => array(
            'cKey'   => 'kKey',
            'cParam' => 'q'
        )
    );

    if ($nAnzahl == 0) {
        $kSprache = 1;
        if (isset($_SESSION['kSprache'])) {
            $kSprache = (int) $_SESSION['kSprache'];
        }
        if (gibAnzahlFilter($NaviFilter) > 0) {
            foreach ($cMainword_arr as $cMainword => $cInfo_arr) {
                $cKey   = $cInfo_arr['cKey'];
                $cParam = $cInfo_arr['cParam'];

                if (isset($NaviFilter->$cMainword) && intval($NaviFilter->$cMainword->$cKey) > 0) {
                    $cUrl = "navi.php?{$cParam}={$NaviFilter->$cMainword->$cKey}";
                    if ($bSeo && isset($NaviFilter->$cMainword->cSeo) && is_array($NaviFilter->$cMainword->cSeo)) {
                        $cUrl = "{$NaviFilter->$cMainword->cSeo[$kSprache]}";
                    }
                    if (strlen($cUrl) > 0) {
                        header("Location: {$cUrl}", true, 301);
                        exit();
                    }
                }
            }
        }
    }
}

/**
 * @param int  $kStueckliste
 * @param bool $bAssoc
 * @return array
 */
function gibStuecklistenKomponente($kStueckliste, $bAssoc = false)
{
    $kStueckliste = (int) $kStueckliste;
    if ($kStueckliste > 0) {
        $oObj_arr = Shop::DB()->query("SELECT * FROM tstueckliste WHERE kStueckliste = " . $kStueckliste, 2);
        if (is_array($oObj_arr) && count($oObj_arr) > 0) {
            if ($bAssoc) {
                $oArtikelAssoc_arr = array();
                foreach ($oObj_arr as $oObj) {
                    $oArtikelAssoc_arr[$oObj->kArtikel] = $oObj;
                }

                return $oArtikelAssoc_arr;
            }

            return $oObj_arr;
        }
    }

    return array();
}

/**
 * @param Artikel $oArtikel
 * @param float   $fAnzahl
 * @return int|null
 */
function pruefeWarenkorbStueckliste($oArtikel, $fAnzahl)
{
    $oStueckliste = ArtikelHelper::isStuecklisteKomponente($oArtikel->kArtikel, true);
    if (is_object($oArtikel) && $oArtikel->cLagerBeachten === 'Y' && $oArtikel->cLagerKleinerNull !== 'Y' && ($oArtikel->kStueckliste > 0 || $oStueckliste)) {
        $bKomponente                = false;
        $oStuecklisteKomponente_arr = null;
        if (isset($oStueckliste->kStueckliste)) {
            $bKomponente = true;
        } else {
            $oStuecklisteKomponente_arr = gibStuecklistenKomponente($oArtikel->kStueckliste, true);
        }
        foreach ($_SESSION['Warenkorb']->PositionenArr as $oPosition) {
            if ($oPosition->nPosTyp == C_WARENKORBPOS_TYP_ARTIKEL) {
                // Komponente soll hinzugefügt werden aber die Stückliste ist bereits im Warenkorb
                // => Prüfen ob der Lagebestand nicht unterschritten wird
                if ($bKomponente && isset($oPosition->Artikel->kStueckliste) && $oPosition->Artikel->kStueckliste > 0 && ($oPosition->nAnzahl * $oStueckliste->fAnzahl + $fAnzahl) > $oArtikel->fLagerbestand) {
                    return R_LAGER;
                } elseif (!$bKomponente && count($oStuecklisteKomponente_arr) > 0) {
                    //Test auf Stücklistenkomponenten in der aktuellen Position
                    if (isset($oPosition->Artikel->kStueckliste) && $oPosition->Artikel->kStueckliste) {
                        $oPositionKomponenten_arr = gibStuecklistenKomponente($oPosition->Artikel->kStueckliste, true);
                        foreach ($oPositionKomponenten_arr as $oKomponente) {
                            $desiredComponentQuantity = $fAnzahl * $oStuecklisteKomponente_arr[$oKomponente->kArtikel]->fAnzahl;
                            $currentComponentStock    = $oPosition->Artikel->fLagerbestand * $oKomponente->fAnzahl;
                            if ($desiredComponentQuantity > $currentComponentStock) {
                                return R_LAGER;
                            }
                        }
                    } elseif (isset($oStuecklisteKomponente_arr[$oPosition->kArtikel]) &&
                        (($oPosition->nAnzahl * $oStuecklisteKomponente_arr[$oPosition->kArtikel]->fAnzahl) +
                            ($oStuecklisteKomponente_arr[$oPosition->kArtikel]->fAnzahl * $fAnzahl)) > $oPosition->Artikel->fLagerbestand
                    ) {
                        return R_LAGER;
                    }
                }
            }
        }
    }

    return;
}

/**
 * return trimmed description without (double) line breaks
 *
 * @param string $cDesc
 * @return string
 */
function truncateMetaDescription($cDesc)
{
    $cDesc = str_replace('"', '', $cDesc);
    $conf  = Shop::getSettings(array(CONF_METAANGABEN));
    if ($conf['metaangaben']['global_meta_maxlaenge_description'] > 0) {
        $cDesc = substr($cDesc, 0, intval($conf['metaangaben']['global_meta_maxlaenge_description']));
    }

    return trim(preg_replace('/\s\s+/', ' ', $cDesc));
}

/**
 * @return mixed
 */
function gibLetztenTokenDaten()
{
    if (isset($_SESSION['xcrsf_token'])) {
        return json_decode($_SESSION['xcrsf_token'], true);
    }

    return '';
}

/**
 * @param bool $bAlten
 * @return string
 */
function gibToken($bAlten = false)
{
    if ($bAlten) {
        $cToken_arr = gibLetztenTokenDaten();
        if (!empty($cToken_arr) && array_key_exists('token', $cToken_arr)) {
            return $cToken_arr['token'];
        }
    }

    return sha1(md5(microtime(true)) . (rand(0, 5000000000) * 1000));
}

/**
 * @param bool $bAlten
 * @return string
 */
function gibTokenName($bAlten = false)
{
    if ($bAlten) {
        $cToken_arr = gibLetztenTokenDaten();
        if (!empty($cToken_arr) && array_key_exists('name', $cToken_arr)) {
            return $cToken_arr['name'];
        }
    }

    return substr(sha1(md5(microtime(true)) . (rand(0, 1000000000) * 1000)), 0, 4);
}

/**
 * @return bool
 */
function validToken()
{
    $cName  = gibTokenName(true);
    $cToken = gibToken(true);
    if (isset($_POST[$cName])) {
        return $cToken === $_POST[$cName];
    }

    return false;
}

/**
 * Converts price into given currency
 *
 * @param float  $price
 * @param string $iso - EUR / USD
 * @param int    $id - kWaehrung
 * @param bool   $useRounding
 * @param int    $nGenauigkeit
 * @return float|bool
 */
function convertCurrency($price, $iso = null, $id = null, $useRounding = true, $nGenauigkeit = 2)
{
    if (!isset($_SESSION['Waehrungen']) || !is_array($_SESSION['Waehrungen'])) {
        $_SESSION['Waehrungen'] = Shop::DB()->query("SELECT * FROM twaehrung", 2);
    }
    foreach ($_SESSION['Waehrungen'] as $waehrung) {
        if (($iso !== null && $waehrung->cISO == $iso) || ($id !== null && (int) $waehrung->kWaehrung == (int) $id)) {
            $newprice = $price * $waehrung->fFaktor;

            return $useRounding ? round($newprice, $nGenauigkeit) : $newprice;
        }
    }

    return false;
}

/**
 * @todo: validate.
 */
function resetNeuKundenKupon()
{
    if (isset($_SESSION['NeukundenKupon']->kKupon)) {
        Shop::DB()->delete('tkuponneukunde', 'kKupon', $_SESSION['NeukundenKupon']->kKupon);
    }
    unset($_SESSION['NeukundenKupon']);
    unset($_SESSION['NeukundenKuponAngenommen']);
    $_SESSION['Warenkorb']->loescheSpezialPos(C_WARENKORBPOS_TYP_NEUKUNDENKUPON)
                          ->setzePositionsPreise();
}

/**
 * @param int $kKonfig
 * @param JTLSmarty $smarty
 */
function holeKonfigBearbeitenModus($kKonfig, &$smarty)
{
    if (class_exists('Konfigitem')) {
        if (isset($_SESSION['Warenkorb']->PositionenArr[$kKonfig])) {
            $oBasePosition = $_SESSION['Warenkorb']->PositionenArr[$kKonfig];
            if ($oBasePosition->istKonfigVater()) {
                $nKonfigitem_arr         = array();
                $nKonfigitemAnzahl_arr   = array();
                $nKonfiggruppeAnzahl_arr = array();

                foreach ($_SESSION['Warenkorb']->PositionenArr as &$oPosition) {
                    if ($oPosition->istKonfigKind() && $oPosition->cUnique == $oBasePosition->cUnique) {
                        $oKonfigitem                                              = new Konfigitem($oPosition->kKonfigitem);
                        $nKonfigitem_arr[]                                        = $oKonfigitem->getKonfigitem();
                        $nKonfigitemAnzahl_arr[$oKonfigitem->getKonfigitem()]     = $oPosition->nAnzahl / $oBasePosition->nAnzahl;
                        $nKonfiggruppeAnzahl_arr[$oKonfigitem->getKonfiggruppe()] = $oPosition->nAnzahl / $oBasePosition->nAnzahl;
                    }
                }

                $smarty->assign('fAnzahl', $oBasePosition->nAnzahl)
                       ->assign('kEditKonfig', $kKonfig)
                       ->assign('nKonfigitem_arr', $nKonfigitem_arr)
                       ->assign('nKonfigitemAnzahl_arr', $nKonfigitemAnzahl_arr)
                       ->assign('nKonfiggruppeAnzahl_arr', $nKonfiggruppeAnzahl_arr);
            }
        }
    }
}

/**
 * @param array $hookInfos
 * @param bool  $forceExit
 * @return array
 */
function urlNotFoundRedirect(array $hookInfos = null, $forceExit = false)
{
    $url         = $_SERVER['REQUEST_URI'];
    $redirect    = new Redirect();
    $redirectUrl = $redirect->checkFallbackRedirect($url);
    if ($redirectUrl === false) {
        $redirectUrl = $redirect->test($url);
    }
    if ($redirectUrl !== false && $redirectUrl !== $url && '/' . $redirectUrl !== $url) {
        $cUrl_arr = parse_url($redirectUrl);
        if (!array_key_exists('scheme', $cUrl_arr)) {
            $redirectUrl = (substr($redirectUrl, 0, 1) === '/') ?
                Shop::getURL() . $redirectUrl :
                Shop::getURL() . '/' . $redirectUrl;
        }
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirectUrl);
        exit;
    }
    header('HTTP/1.0 404 Not Found');

    if ($forceExit || !$redirect->isValid($url)) {
        exit;
    }
    $isFileNotFound = true;
    executeHook(HOOK_PAGE_NOT_FOUND_PRE_INCLUDE, array('isFileNotFound' => &$isFileNotFound, $hookInfos['key'] => &$hookInfos['value']));
    $hookInfos['isFileNotFound'] = $isFileNotFound;

    return $hookInfos;
}

/**
 * @param int $minDeliveryDays
 * @param int $maxDeliveryDays
 * @return mixed
 */
function getDeliverytimeEstimationText($minDeliveryDays, $maxDeliveryDays)
{
    $deliveryText = ($minDeliveryDays == $maxDeliveryDays) ?
        str_replace('#DELIVERYDAYS#', $minDeliveryDays, Shop::Lang()->get('deliverytimeEstimationSimple', 'global')) :
        str_replace(
            array('#MINDELIVERYDAYS#', '#MAXDELIVERYDAYS#'),
            array($minDeliveryDays, $maxDeliveryDays),
            Shop::Lang()->get('deliverytimeEstimation', 'global')
        );

    executeHook(HOOK_GET_DELIVERY_TIME_ESTIMATION_TEXT, array(
            'min'  => $minDeliveryDays,
            'max'  => $maxDeliveryDays,
            'text' => &$deliveryText
        )
    );

    return $deliveryText;
}

/**
 * @param string $response
 * @return bool
 */
function validateReCaptcha($response)
{
    $settings = Shop::getSettings(array(CONF_GLOBAL));
    $secret   = $settings['global']['global_google_recaptcha_private'];
    $url      = 'https://www.google.com/recaptcha/api/siteverify';
    if (!isset($secret) || strlen($secret) < 1) {
        return true;
    }

    $json = http_get_contents($url, 30, array(
        'secret'   => $secret,
        'response' => $response,
        'remoteip' => getRealIp()
    ));

    if (is_string($json)) {
        $result = json_decode($json);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $_SESSION['bAnti_spam_already_checked'] = (isset($result->success) && $result->success);
        }
    }

    return false;
}

/**
 * @return int
 */
function getDefaultLanguageID()
{
    $kSprache = (isset($_SESSION['kSprache'])) ? $_SESSION['kSprache'] : 0;
    if ($kSprache === 0) {
        if (Shop::$kSprache !== null) {
            $kSprache = Shop::$kSprache;
        } else {
            $oSpracheTMP = Shop::DB()->query(
                "SELECT kSprache
                    FROM tsprache
                    WHERE cShopStandard = 'Y'", 1
            );
            if (isset($oSpracheTMP->kSprache) && $oSpracheTMP->kSprache > 0) {
                $kSprache = $oSpracheTMP->kSprache;
            }
        }
    }

    return (int) $kSprache;
}

/**
 * creates an csrf token
 *
 * @return string
 */
function generateCSRFToken()
{
    return md5(uniqid(rand(), true));
}

/**
 * create a hidden input field for xsrf validation
 *
 * @return string
 */
function getTokenInput()
{
    if (!isset($_SESSION['jtl_token'])) {
        $_SESSION['jtl_token'] = generateCSRFToken();
    }

    return '<input type="hidden" class="jtl_token" name="jtl_token" value="' . $_SESSION['jtl_token'] . '" />';
}

/**
 * validate token from POST/GET
 *
 * @return bool
 */
function validateToken()
{
    return isset($_SESSION['jtl_token']) && (
        filter_input(INPUT_POST, 'jtl_token') === $_SESSION['jtl_token'] ||
        filter_input(INPUT_GET, 'token') === $_SESSION['jtl_token']);
}

/**
 * @return bool
 */
function isAjaxRequest()
{
    return isset($_REQUEST['isAjax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

/**
 * @deprecated since 4.0
 * @return int
 */
function gibSeitenTyp()
{
    return Shop::getPageType();
}

/**
 * @deprecated since 4.0
 * @return string
 */
function getShopTemplate()
{
    $template = Template::getInstance();

    return $template->getDir();
}

/**
 * @deprecated since 4.0
 * @return float
 */
function microtime_float()
{
    return microtime(true);
}

/**
 * @deprecated since 4.0
 * @param int $kArtikel
 * @return bool
 */
function pruefeIstVaterArtikel($kArtikel)
{
    return ArtikelHelper::isParent($kArtikel);
}

/**
 * @deprecated since 4.0
 * @param Kunde $Kunde
 */
function setzeKundeInSession($Kunde)
{
    $session = Session::getInstance();
    $session->setCustomer($Kunde);
}

/**
 * @deprecated since 4.0
 * @param int $kArtikel
 * @return int
 */
function gibkArtikelZuVaterArtikel($kArtikel)
{
    return ArtikelHelper::getArticleForParent($kArtikel);
}

/**
 * @deprecated since 4.0
 * @param string $cString
 * @param int    $nSuche
 * @return mixed|string
 */
function filterXSS($cString, $nSuche = 0)
{
    return StringHandler::filterXSS($cString, $nSuche);
}

/**
 * @deprecated since 4.0
 * @param array $array
 * @return array
 */
function filterXSSArray($array)
{
    return StringHandler::filterXSS($array);
}

/**
 * @deprecated since 4.0
 * @return int
 */
function gibAktuelleKundengruppe()
{
    return Kundengruppe::getCurrent();
}

/**
 * @deprecated since 4.0
 * @return mixed
 */
function gibStandardKundenGruppe()
{
    return Kundengruppe::getDefaultGroupID();
}

/**
 * @deprecated since 4.0
 * @param string $cData
 * @return string
 */
function convertUTF8($cData)
{
    return StringHandler::convertUTF8($cData);
}

/**
 * @deprecated since 4.0
 * @param string $cData
 * @return string
 */
function convertISO($cData)
{
    return StringHandler::convertISO($cData);
}

/**
 * @deprecated since 4.0
 * @param string $ISO
 * @return mixed
 */
function convertISO2ISO639($ISO)
{
    return StringHandler::convertISO2ISO639($ISO);
}

/**
 * @deprecated since 4.0
 * @param string $ISO
 * @return int|string
 */
function convertISO6392ISO($ISO)
{
    return StringHandler::convertISO6392ISO($ISO);
}

/**
 * @deprecated since 4.0
 * @param string $lieferland
 * @param string $plz
 * @param string $versandklassen
 * @param int    $kKundengruppe
 * @return array
 */
function gibMoeglicheVersandarten($lieferland, $plz, $versandklassen, $kKundengruppe)
{
    return VersandartHelper::getPossibleShippingMethods($lieferland, $plz, $versandklassen, $kKundengruppe);
}

/**
 * @deprecated since 4.0
 * @param Warenkorb $Warenkorb
 * @return string
 */
function gibVersandklassen($Warenkorb)
{
    return VersandartHelper::getShippingClasses($Warenkorb);
}

/**
 * @deprecated since 4.0
 * @return array
 */
function baueBoxen()
{
    return array();
}

/**
 * @deprecated since 4.0
 * @return array
 */
function gibBoxen()
{
    $boxes = Boxen::getInstance();

    return $boxes->compatGet();
}

/**
 * @deprecated since 4.0
 * @param string $ePosition
 * @return bool
 */
function boxAnzeigen($ePosition)
{
    $boxes      = Boxen::getInstance();
    $visibility = $boxes->holeBoxAnzeige(gibSeitenTyp());

    return (isset($visibility[$ePosition]) && $visibility[$ePosition] === true);
}

/**
 * @deprecated since 4.0
 * @param bool $bForceSSL
 * @return string
 */
function gibShopURL($bForceSSL = false)
{
    return Shop::getURL($bForceSSL);
}

/**
 * @deprecated since 4.0
 * @param string $cLand
 * @param string $cPLZ
 * @param string $cError
 * @return bool
 */
function ermittleVersandkosten($cLand, $cPLZ, &$cError = '')
{
    return VersandartHelper::getShippingCosts($cLand, $cPLZ, $cError);
}

/**
 * @depreated since 4.0
 * @param int  $kKategorie
 * @param int  $kKundengruppe
 * @param int  $kSprache
 * @param bool $all
 * @return array
 */
function holUnterkategorien($kKategorie, $kKundengruppe, $kSprache, $all = false)
{
    $catList = new Kategorieliste();

    return $catList->holUnterkategorien($kKategorie, $kKundengruppe, $kSprache);
}

/**
 * @depreated since 4.0
 * @param int $kKategorie
 * @param int $kKundengruppe
 * @return bool
 */
function nichtLeer($kKategorie, $kKundengruppe)
{
    $catList = new KategorieListe();

    return $catList->nichtLeer($kKategorie, $kKundengruppe);
}

/**
 * @depreated since 4.0
 * @param int $kKategorie
 * @param int $kKundengruppe
 * @return bool
 */
function artikelVorhanden($kKategorie, $kKundengruppe)
{
    $catList = new KategorieListe();

    return $catList->artikelVorhanden($kKategorie, $kKundengruppe);
}

/**
 * @deprecated since 4.0
 * @param array $sektionen_arr
 * @return array
 */
function getEinstellungen($sektionen_arr)
{
    return Shop::getSettings($sektionen_arr);
}

/**
 * @deprecated since 4.0 - use Jtllog::writeLog() insted
 * @param string $logfile
 * @param string $entry
 * @param int    $level
 * @return bool
 */
function writeLog($logfile, $entry, $level)
{
    if (ES_LOGGING > 0 && ES_LOGGING >= $level) {
        $logfile = fopen($logfile, 'a');
        if (!$logfile) {
            return false;
        }
        fwrite($logfile, "\n[" . date('m.d.y H:i:s') . "] [" . gibIP() . "]\n" . $entry);
        fclose($logfile);
    }

    return true;
}

/**
 * @deprecated since 4.0
 * @param object $NaviFilter
 * @param array  $cParameter_arr
 * @return mixed
 */
function baueNaviFilter($NaviFilter = null, $cParameter_arr)
{
    return Shop::buildNaviFilter($cParameter_arr, $NaviFilter);
}

/**
 * @deprecated since 4.0
 * @return bool
 */
function session_notwendig()
{
    return false;
}

/**
 * @deprecated since 4.0
 */
function gibFinanzierung()
{
}

/**
 * @param int $keyname
 * @param int $key
 * @deprecated since 4.03
 */
function setzeBesuch($keyname, $key)
{
    if (!$key || isset($_GET['notrack'])) {
        return;
    }
    $besuch = Shop::DB()->select('tbesuchteseiten', $keyname, $key);
    if (isset($besuch->nBesuche) && $besuch->nBesuche > 0) {
        Shop::DB()->query("UPDATE tbesuchteseiten SET nBesuche = nBesuche+1 WHERE " . $keyname . " = " . $key, 4);
    } else {
        $BesuchteSeiten                        = new stdClass();
        $BesuchteSeiten->kArtikel              = 0;
        $BesuchteSeiten->kKategorie            = 0;
        $BesuchteSeiten->kLink                 = 0;
        $BesuchteSeiten->kTag                  = 0;
        $BesuchteSeiten->kSuchanfrage          = 0;
        $BesuchteSeiten->kHersteller           = 0;
        $BesuchteSeiten->kNews                 = 0;
        $BesuchteSeiten->kNewsMonatsUebersicht = 0;
        $BesuchteSeiten->kNewsKategorie        = 0;
        $BesuchteSeiten->nBesuche              = 1;
        $BesuchteSeiten->$keyname              = $key;
        Shop::DB()->insert('tbesuchteseiten', $BesuchteSeiten);
    }
}

/**
 * @param array $params
 * @deprecated since 4.03
 */
function setzeBesuchExt($params)
{
    if ($params['kKategorie'] > 0) {
        setzeBesuch('kKategorie', $params['kKategorie']);
    } elseif ($params['kHersteller'] > 0) {
        setzeBesuch('kHersteller', $params['kHersteller']);
    } elseif ($params['kTag'] > 0) {
        setzeBesuch('kTag', $params['kTag']);
    } elseif ($params['kSuchanfrage'] > 0) {
        setzeBesuch('kSuchanfrage', $params['kSuchanfrage']);
    } elseif ($params['kNews'] > 0) {
        setzeBesuch('kNews', $params['kNews']);
    } elseif ($params['kNewsMonatsUebersicht'] > 0) {
        setzeBesuch('kNewsMonatsUebersicht', $params['kNewsMonatsUebersicht']);
    } elseif ($params['kNewsKategorie'] > 0) {
        setzeBesuch('kNewsKategorie', $params['kNewsKategorie']);
    }
}

/**
 * @return string
 * @deprecated since 4.03
 */
function gibURLzuNewsArchiv()
{
    $oNewsMonatsUebersicht = Shop::DB()->query(
        "SELECT tnewsmonatsuebersicht.kNewsMonatsUebersicht, tnewsmonatsuebersicht.kSprache, tseo.cSeo,
            tnewsmonatsuebersicht.cName, tnewsmonatsuebersicht.nMonat, tnewsmonatsuebersicht.nJahr
            FROM tnewsmonatsuebersicht
            LEFT JOIN tseo ON tseo.cKey = 'kNewsMonatsUebersicht'
                AND tseo.kKey = tnewsmonatsuebersicht.kNewsMonatsUebersicht
                AND tseo.kSprache = " . (int) $_SESSION['kSprache'] . "
            WHERE tnewsmonatsuebersicht.kSprache = " . intval($_SESSION['kSprache']) . "
                AND tnewsmonatsuebersicht.nMonat = " . intval(date('m')) . "
                AND tnewsmonatsuebersicht.nJahr = " . intval(date('Y')), 1
    );

    if (empty($oNewsMonatsUebersicht->kNewsMonatsUebersicht)) {
        $oNewsMonatsUebersicht = Shop::DB()->query(
            "SELECT tnewsmonatsuebersicht.kNewsMonatsUebersicht, tnewsmonatsuebersicht.kSprache, tseo.cSeo,
                tnewsmonatsuebersicht.cName, tnewsmonatsuebersicht.nMonat, tnewsmonatsuebersicht.nJahr
                FROM tnewsmonatsuebersicht
                LEFT JOIN tseo ON tseo.cKey = 'kNewsMonatsUebersicht'
                    AND tseo.kKey = tnewsmonatsuebersicht.kNewsMonatsUebersicht
                    AND tseo.kSprache = " . (int) $_SESSION['kSprache'] . "
                WHERE tnewsmonatsuebersicht.kSprache = " . (int) $_SESSION['kSprache'] . "
                AND tnewsmonatsuebersicht.nJahr <= " . intval(date('Y')) . "
                ORDER BY nJahr DESC, nMonat DESC", 1
        );
    }
    if (empty($oNewsMonatsUebersicht->kNewsMonatsUebersicht)) {
        return 'news.php?noarchiv=1&';
    }

    return baueURL($oNewsMonatsUebersicht, URLART_NEWSMONAT);
}

function formatSize($size)
{
    $units = ['b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb'];
    foreach ($units as $n => $unit) {
        $div = pow(1024, $n);
        if ($size > $div) {
            $res = sprintf('%s %s', number_format($size / $div, 2), $unit);
        }
    }

    return $res;
}

if (!function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param mixed
     * @return void
     */
    function dd()
    {
        array_map(function ($var) {
            dump($var);
        }, func_get_args());
        die(1);
    }
}
