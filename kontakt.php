<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */
require_once dirname(__FILE__) . '/includes/globalinclude.php';
$session = Session::getInstance();
require_once PFAD_ROOT . PFAD_INCLUDES . 'kontakt_inc.php';
require_once PFAD_ROOT . PFAD_INCLUDES . 'mailTools.php';
require_once PFAD_ROOT . PFAD_INCLUDES . 'smartyInclude.php';

Shop::setPageType(PAGE_KONTAKT);
$AktuelleSeite = 'KONTAKT';
$Einstellungen = Shop::getSettings(array(CONF_GLOBAL, CONF_RSS, CONF_KONTAKTFORMULAR));
$linkHelper    = LinkHelper::getInstance();
$kLink         = $linkHelper->getSpecialPageLinkKey(LINKTYP_KONTAKT);
//hole alle OberKategorien
$AktuelleKategorie      = new Kategorie(verifyGPCDataInteger('kategorie'));
$AufgeklappteKategorien = new KategorieListe();
$AufgeklappteKategorien->getOpenCategories($AktuelleKategorie);
$startKat             = new Kategorie();
$startKat->kKategorie = 0;
$cCanonicalURL        = '';
// SSL?
pruefeHttps();
if (pruefeBetreffVorhanden()) {
    $step            = 'formular';
    $fehlendeAngaben = array();
    if (isset($_POST['kontakt']) && intval($_POST['kontakt']) === 1) {
        $fehlendeAngaben = gibFehlendeEingabenKontaktformular();
        $kKundengruppe   = Kundengruppe::getCurrent();
        // CheckBox Plausi
        $oCheckBox       = new CheckBox();
        $fehlendeAngaben = array_merge($fehlendeAngaben, $oCheckBox->validateCheckBox(CHECKBOX_ORT_KONTAKT, $kKundengruppe, $_POST, true));
        $nReturnValue    = eingabenKorrekt($fehlendeAngaben);
        $smarty->assign('cPost_arr', StringHandler::filterXSS($_POST));
        executeHook(HOOK_KONTAKT_PAGE_PLAUSI);

        if ($nReturnValue) {
            if (!floodSchutz($Einstellungen['kontakt']['kontakt_sperre_minuten'])) {
                $oNachricht = baueKontaktFormularVorgaben();
                // CheckBox Spezialfunktion ausfuehren
                $oCheckBox->triggerSpecialFunction(CHECKBOX_ORT_KONTAKT, $kKundengruppe, true, $_POST,
                    array('oKunde' => $oNachricht, 'oNachricht' => $oNachricht))
                          ->checkLogging(CHECKBOX_ORT_KONTAKT, $kKundengruppe, $_POST, true);
                bearbeiteNachricht();
                $step = 'nachricht versendet';
            } else {
                $step = 'floodschutz';
            }
        } else {
            $smarty->assign('fehlendeAngaben', $fehlendeAngaben);
        }
    }
    $lang     = $_SESSION['cISOSprache'];
    $Contents = Shop::DB()->query("
        SELECT *
            FROM tspezialcontentsprache
            WHERE nSpezialContent = '" . SC_KONTAKTFORMULAR . "'
            AND cISOSprache = '" . $lang . "'", 2
    );
    $SpezialContent = new stdClass();
    foreach ($Contents as $Content) {
        $SpezialContent->{$Content->cTyp} = $Content->cContent;
    }
    $betreffs = Shop::DB()->query(
        "SELECT *
            FROM tkontaktbetreff
            WHERE (cKundengruppen=0
            OR cKundengruppen LIKE '" . (int)$_SESSION['Kundengruppe']->kKundengruppe . ";') ORDER BY nSort", 2
    );
    $bCount = count($betreffs);
    for ($i = 0; $i < $bCount; $i++) {
        if ($betreffs[$i]->kKontaktBetreff > 0) {
            $betreffSprache = Shop::DB()->query(
                "SELECT *
                    FROM tkontaktbetreffsprache
                    WHERE kKontaktBetreff = " . (int)$betreffs[$i]->kKontaktBetreff . "
                    AND cISOSprache = '" . $_SESSION['cISOSprache'] . "'", 1
            );
            $betreffs[$i]->AngezeigterName = $betreffSprache->cName;
        }
    }
    $Vorgaben = baueKontaktFormularVorgaben();
    // Canonical
    $cCanonicalURL = Shop::getURL() . '/kontakt.php';
    // Metaangaben
    $oMeta            = $linkHelper->buildSpecialPageMeta(LINKTYP_KONTAKT, $lang);
    $cMetaTitle       = $oMeta->cTitle;
    $cMetaDescription = $oMeta->cDesc;
    $cMetaKeywords    = $oMeta->cKeywords;
    //specific assigns
    $smarty->assign('step', $step)
           ->assign('code', generiereCaptchaCode($Einstellungen['kontakt']['kontakt_abfragen_captcha']))
           ->assign('betreffs', $betreffs)
           ->assign('hinweis', (isset($hinweis)) ? $hinweis : null)
           ->assign('Vorgaben', $Vorgaben)
           ->assign('fehlendeAngaben', $fehlendeAngaben)
           ->assign('nAnzeigeOrt', CHECKBOX_ORT_KONTAKT);
} else {
    Jtllog::writeLog('Kein Kontaktbetreff vorhanden! Bitte im Backend unter Einstellungen -> Kontaktformular -> Betreffs einen Betreff hinzuf&uuml;gen.', JTLLOG_LEVEL_ERROR);
    $smarty->assign('hinweis', Shop::Lang()->get('noSubjectAvailable', 'contact'));
    $SpezialContent = new stdClass();
}

$smarty->assign('Navigation', createNavigation($AktuelleSeite))
       ->assign('Spezialcontent', $SpezialContent)
       ->assign('requestURL', (isset($requestURL)) ? $requestURL : null)
       ->assign('Einstellungen', $Einstellungen);

require PFAD_ROOT . PFAD_INCLUDES . 'letzterInclude.php';
executeHook(HOOK_KONTAKT_PAGE);
$smarty->display('contact/index.tpl');

require PFAD_ROOT . PFAD_INCLUDES . 'profiler_inc.php';
