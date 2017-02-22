<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */
if (!isset($_GET['jtl_paypal_redirect'])) {
    if (isset($_GET['return'])) {
        header('Location: ' . URL_SHOP . '/warenkorb.php?return=1&jtl_paypal_redirect=1');
    } else {
        header('Location: ' . URL_SHOP . '/warenkorb.php?jtl_paypal_redirect=1');
    }
    exit;
}

require_once str_replace('frontend', 'paymentmethod', $oPlugin->cFrontendPfad) . '/class/PayPalExpress.class.php';
require_once PFAD_ROOT . PFAD_INCLUDES . 'mailTools.php';
require_once PFAD_INCLUDES . 'bestellvorgang_inc.php';
require_once PFAD_INCLUDES . 'registrieren_inc.php';

$paypal = new PayPalExpress();

if (isset($_GET['return']) && $_GET['return'] === '1') {
    $session  = Session::getInstance();
    $conf     = Shop::getConfig([CONF_KUNDEN]);
    $response = $paypal->GetExpressCheckoutDetails($_SESSION['reshash']['Token']);

    $_SESSION['reshash']['Token']   = $response->Token;
    $_SESSION['reshash']['Payer']   = $response->PayerInfo->Payer;
    $_SESSION['reshash']['PayerID'] = $response->PayerInfo->PayerID;

    $name   = PayPalHelper::extractName(StringHandler::convertISO($response->PayerInfo->Address->Name));
    $street = PayPalHelper::extractStreet(StringHandler::convertISO($response->PayerInfo->Address->Street1));

    $_POST['anrede']     = 'm';
    $_POST['vorname']    = $name->first;
    $_POST['nachname']   = $name->last;
    $_POST['strasse']    = $street->name;
    $_POST['hausnummer'] = $street->number;

    $_POST['adresszusatz'] = StringHandler::convertISO($response->PayerInfo->Address->Street2);
    $_POST['bundesland']   = StringHandler::convertISO($response->PayerInfo->Address->StateOrProvince);
    $_POST['plz']          = StringHandler::convertISO($response->PayerInfo->Address->PostalCode);
    $_POST['ort']          = StringHandler::convertISO($response->PayerInfo->Address->CityName);
    $_POST['land']         = StringHandler::convertISO($response->PayerInfo->Address->Country);
    $_POST['tel']          = StringHandler::convertISO($response->PayerInfo->Address->Phone);

    $_POST['email'] = StringHandler::convertISO($response->PayerInfo->Payer);

    if ($conf['kunden']['kundenregistrierung_abfragen_firma'] === 'Y') {
        $_POST['firma'] = 'NOCOMPANY';
    }

    if ($conf['kunden']['kundenregistrierung_abfragen_ustid'] !== 'N') {
        $_POST['ustid'] = 'NOVATID';
    }

    if ($conf['kunden']['kundenregistrierung_abfragen_geburtstag'] === 'Y') {
        $_POST['geburtstag'] = '01.01.1970';
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $customer      = null;
    $createAccount = $oPlugin->oPluginEinstellungAssoc_arr['jtl_paypal_express_create_account'] === 'Y';

    if (isset($_SESSION['Kunde']) && (int)$_SESSION['Kunde']->kKunde > 0) {
        if ($_SESSION['Kunde']->cMail == $_POST['email']) {
            $customer = $_SESSION['Kunde'];
        } else {
            $sql = "SELECT kKunde FROM tkunde WHERE cMail = '" . Shop::DB()->escape($_POST['email']) . "' AND nRegistriert=1";
            $obj = Shop::DB()->query($sql, 1);
            if (is_object($obj) && $obj->kKunde > 0) {
                $customer = new Kunde($obj->kKunde);
            }
        }
    }

    if ($customer !== null) {
        $session->setCustomer($customer);

        if (isset($customer->cSperre) && $customer->cSperre === 'Y') { //customer is blocked
            header('Location: warenkorb.php?fillOut=ppexpress_blocked');
            exit;
        }

        if (isset($customer->cAktiv) && $customer->cAktiv === 'N') { //customer is not active
            header('Location: warenkorb.php?fillOut=ppexpress_inactive');
            exit;
        }

        $customer = getKundendaten($_POST, 1, 0);
        $session->setCustomer($customer);
    } else {
        unset($_SESSION['Kunde']);
    }

    if ($createAccount) {
        $plainPassword = gibUID(max(intval($conf['kunden']['kundenregistrierung_passwortlaenge']), 8));
        $_POST['pass'] = $_POST['pass2'] = $plainPassword;
    }

    $customer = getKundendaten($_POST, 0, 0);

    $customer->kKundengruppe = $_SESSION['Kundengruppe']->kKundengruppe;
    $customer->kSprache      = $_SESSION['kSprache'];
    $customer->cAbgeholt     = 'N';
    $customer->cAktiv        = 'Y';
    $customer->cSperre       = 'N';
    $customer->nRegistriert  = 0;
    $customer->dErstellt     = date_format(date_create(), 'Y-m-d');

    if (!isset($customer->cAnrede) || $customer->cAnrede === null) {
        $customer->cAnrede = '';
    }

    $session->setCustomer($customer);

    if ($createAccount) {
        $oMail                            = (object) ['tkunde' => $_SESSION['Kunde']];
        $oMail->tkunde->cPasswortKlartext = $plainPassword;
        sendeMail('kPlugin_' . $oPlugin->kPlugin . '_ppexpresskkpw', $oMail);
    }

    setzeLieferadresseAusRechnungsadresse();

    $step = 'Zahlung';

    $_SESSION['Zahlungsart']                 = $paypal->zahlungsartsession();
    $_SESSION['paypalexpress']->sZahlungsart = $_SESSION['Zahlungsart'];
    $_POST['Zahlungsart']                    = $_SESSION['Zahlungsart']->kZahlungsart;
    $_POST['zahlungsartwahl']                = '1';

    pruefeZahlungsartwahlStep($_POST);

    //workaround since the session is deleted in pruefeZahlungsartwahlStep()

    $_SESSION['Zahlungsart']  = $_SESSION['paypalexpress']->sZahlungsart;
    $_POST['Zahlungsart']     = $_SESSION['Zahlungsart']->kZahlungsart;
    $_POST['zahlungsartwahl'] = '1';

    PayPalHelper::addSurcharge();

    header('Location: bestellvorgang.php?refresh=1');
    exit;
} else {
    $products = PayPalHelper::getProducts();

    $min = $oPlugin->oPluginEinstellungAssoc_arr['kPlugin_' . $oPlugin->kPlugin . '_paypalexpress_min'];
    $max = $oPlugin->oPluginEinstellungAssoc_arr['kPlugin_' . $oPlugin->kPlugin . '_paypalexpress_max'];
    $sum = $_SESSION['Warenkorb']->gibGesamtsummeWarenExt([C_WARENKORBPOS_TYP_ARTIKEL], true);

    if ($max > 0 && $max < $sum) {
        header('Location: warenkorb.php?fillOut=ppexpress_max&max=' . $max);
        exit;
    }

    if ($min > 0 && $min > $sum) {
        header('Location: warenkorb.php?fillOut=ppexpress_min&min=' . $min);
        exit;
    }

    if ($paypal->zahlungErlaubt($products) === false) {
        header('Location: warenkorb.php?fillOut=ppexpress_notallowed');
        exit;
    }

    $paypal->zahlungsprozess();
}
