<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */
require_once dirname(__FILE__) . '/includes/globalinclude.php';
require_once PFAD_ROOT . PFAD_INCLUDES . 'bestellabschluss_inc.php';
require_once PFAD_ROOT . PFAD_INCLUDES . 'bestellvorgang_inc.php';
require_once PFAD_ROOT . PFAD_INCLUDES . 'trustedshops_inc.php';
require_once PFAD_ROOT . PFAD_INCLUDES . 'mailTools.php';
require_once PFAD_ROOT . PFAD_INCLUDES . 'smartyInclude.php';

$Einstellungen = Shop::getSettings(array(
    CONF_GLOBAL,
    CONF_RSS,
    CONF_KUNDEN,
    CONF_KAUFABWICKLUNG,
    CONF_ZAHLUNGSARTEN,
    CONF_EMAILS,
    CONF_TRUSTEDSHOPS
));
$AktuelleSeite = 'BESTELLVORGANG';
Shop::setPageType(PAGE_BESTELLABSCHLUSS);
if (isset($_GET['i'])) {
    $bestellung = null;
    $bestellid  = Shop::DB()->select('tbestellid', 'cId', Shop::DB()->escape($_GET['i']));
    if (isset($bestellid->kBestellung) && $bestellid->kBestellung > 0) {
        $bestellung = new Bestellung($bestellid->kBestellung);
        $bestellung->fuelleBestellung(0);
        Shop::DB()->delete('tbestellid', 'kBestellung', (int)$bestellid->kBestellung);
        // Zahlungsanbieter
        if (isset($_GET['za']) && $_GET['za'] === 'eos') {
            include_once PFAD_ROOT . PFAD_INCLUDES_MODULES . 'eos/eos.php';
            eosZahlungsNachricht($bestellung);
        }
    }
    Shop::DB()->query("DELETE FROM tbestellid WHERE dDatum < date_sub(now(),INTERVAL 30 DAY)", 4);
    $smarty->assign('abschlussseite', 1);
} else {
    $_SESSION['kommentar'] = (isset($_POST['kommentar'])) ? substr(strip_tags(Shop::DB()->escape($_POST['kommentar'])), 0, 1000) : '';
    if (pruefeEmailblacklist($_SESSION['Kunde']->cMail)) {
        header('Location: ' . Shop::getURL() . '/bestellvorgang.php?mailBlocked=1', true, 303);
        exit;
    } elseif (!bestellungKomplett()) {
        header('Location: ' . Shop::getURL() . '/bestellvorgang.php?fillOut=' . gibFehlendeEingabe(), true, 303);
        exit;
    } else {
        //pruefen, ob von jedem Artikel im WK genug auf Lager sind. Wenn nicht, WK verkleinern und Redirect zum WK
        $_SESSION['Warenkorb']->pruefeLagerbestaende();

        if ($_SESSION['Warenkorb']->checkIfCouponIsStillValid() === false) {
            $_SESSION['checkCouponResult']['ungueltig'] = 3;
            header('Location: ' . Shop::getURL() . '/warenkorb.php', true, 303);
            exit;
        }

        if (!isset($_SESSION['Zahlungsart']->nWaehrendBestellung) || $_SESSION['Zahlungsart']->nWaehrendBestellung == 0) {
            $bestellung = finalisiereBestellung();
            $bestellid  = (isset($bestellung->kBestellung) && $bestellung->kBestellung > 0) ?
                Shop::DB()->select('tbestellid', 'kBestellung', $bestellung->kBestellung) :
                false;
            if (is_null($bestellung->Lieferadresse) && isset($_SESSION['Lieferadresse']) && strlen($_SESSION['Lieferadresse']->cVorname) > 0) {
                $bestellung->Lieferadresse = gibLieferadresseAusSession();
            }
            $successPaymentURL = (!empty($bestellid->cId)) ?
                (Shop::getURL() . '/bestellabschluss.php?i=' . $bestellid->cId) :
                Shop::getURL();
            $smarty->assign('Bestellung', $bestellung);
        } else {
            $bestellung = fakeBestellung();
        }
        setzeSmartyWeiterleitung($bestellung);
    }
}
//hole aktuelle Kategorie, falls eine gesetzt
$AktuelleKategorie      = new Kategorie(verifyGPCDataInteger('kategorie'));
$AufgeklappteKategorien = new KategorieListe();
$AufgeklappteKategorien->getOpenCategories($AktuelleKategorie);
$startKat             = new Kategorie();
$startKat->kKategorie = 0;
// Trusted Shops Kaeuferschutz Classic
if (isset($Einstellungen['trustedshops']['trustedshops_nutzen']) && $Einstellungen['trustedshops']['trustedshops_nutzen'] === 'Y') {
    require_once PFAD_ROOT . PFAD_CLASSES . 'class.JTL-Shop.TrustedShops.php';
    $oTrustedShops = new TrustedShops(-1, StringHandler::convertISO2ISO639($_SESSION['cISOSprache']));

    if (strlen($oTrustedShops->tsId) > 0 && $oTrustedShops->nAktiv == 1) {
        $smarty->assign('oTrustedShops', $oTrustedShops);
    }
}

$smarty->assign('Navigation', createNavigation($AktuelleSeite))
       ->assign('Firma', Shop::DB()->query("SELECT * FROM tfirma", 1))
       ->assign('WarensummeLocalized', $_SESSION['Warenkorb']->gibGesamtsummeWarenLocalized())
       ->assign('Einstellungen', $Einstellungen)
       ->assign('Bestellung', $bestellung)
       ->assign('Kunde', (isset($_SESSION['Kunde'])) ? $_SESSION['Kunde'] : null)
       ->assign('bOrderConf', true)
       ->assign('C_WARENKORBPOS_TYP_ARTIKEL', C_WARENKORBPOS_TYP_ARTIKEL)
       ->assign('C_WARENKORBPOS_TYP_GRATISGESCHENK', C_WARENKORBPOS_TYP_GRATISGESCHENK);

// Plugin Zahlungsmethode beachten
$kPlugin = (isset($bestellung->Zahlungsart->cModulId)) ? gibkPluginAuscModulId($bestellung->Zahlungsart->cModulId) : 0;
if ($kPlugin > 0) {
    $oPlugin = new Plugin($kPlugin);
    $smarty->assign('oPlugin', $oPlugin);
}
if (!isset($_SESSION['Zahlungsart']->nWaehrendBestellung) || $_SESSION['Zahlungsart']->nWaehrendBestellung == 0 || isset($_GET['i'])) {
    if ($Einstellungen['trustedshops']['trustedshops_kundenbewertung_anzeigen'] === 'Y') {
        $smarty->assign('oTrustedShopsBewertenButton', gibTrustedShopsBewertenButton($bestellung->oRechnungsadresse->cMail, $bestellung->cBestellNr));
    }
    $session->cleanUp();
    require PFAD_ROOT . PFAD_INCLUDES . 'letzterInclude.php';
    executeHook(HOOK_BESTELLABSCHLUSS_PAGE);
    $smarty->display('checkout/order_completed.tpl');
} else {
    require PFAD_ROOT . PFAD_INCLUDES . 'letzterInclude.php';
    executeHook(HOOK_BESTELLABSCHLUSS_PAGE_ZAHLUNGSVORGANG);
    $smarty->display('checkout/step6_init_payment.tpl');
}

require PFAD_ROOT . PFAD_INCLUDES . 'profiler_inc.php';
