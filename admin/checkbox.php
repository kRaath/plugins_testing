<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */
require_once dirname(__FILE__) . '/includes/admininclude.php';

$oAccount->permission('CHECKBOXES_VIEW', true, true);

require_once PFAD_ROOT . PFAD_ADMIN . PFAD_INCLUDES . 'blaetternavi.php';
require_once PFAD_ROOT . PFAD_ADMIN . PFAD_INCLUDES . 'checkbox_inc.php';

$Einstellungen     = Shop::getSettings(array(CONF_CHECKBOX));
$cHinweis          = '';
$cFehler           = '';
$cStep             = 'uebersicht';
$nAnzahlProSeite   = 15;
$oBlaetterNaviConf = baueBlaetterNaviGetterSetter(1, $nAnzahlProSeite);
$oSprach_arr       = gibAlleSprachen();
$oCheckBox         = new CheckBox();
$cTab              = $cStep;
if (strlen(verifyGPDataString('tab')) > 0) {
    $cTab = verifyGPDataString('tab');
}
if (isset($_POST['erstellenShowButton'])) {
    $cTab = 'erstellen';
} elseif (verifyGPCDataInteger('uebersicht') === 1) { // Loeschen, aktivieren, deaktivieren
    $kCheckBox_arr = $_POST['kCheckBox'];
    if (isset($_POST['checkboxAktivierenSubmit'])) {
        $oCheckBox->aktivateCheckBox($kCheckBox_arr);
        $cHinweis = 'Ihre markierten Checkboxen wurden erfolgreich aktiviert.';
    } elseif (isset($_POST['checkboxDeaktivierenSubmit'])) {
        $oCheckBox->deaktivateCheckBox($kCheckBox_arr);
        $cHinweis = 'Ihre markierten Checkboxen wurden erfolgreich deaktiviert.';
    } elseif (isset($_POST['checkboxLoeschenSubmit'])) {
        $oCheckBox->deleteCheckBox($kCheckBox_arr);
        $cHinweis = 'Ihre markierten Checkboxen wurden erfolgreich gel&ouml;scht.';
    }
} elseif (verifyGPCDataInteger('edit') > 0) {
    $kCheckBox = verifyGPCDataInteger('edit');
    $cStep     = 'erstellen';
    $cTab      = $cStep;
    $smarty->assign('oCheckBox', new CheckBox($kCheckBox, true));
} elseif (verifyGPCDataInteger('erstellen') === 1) { // Erstellen
    $cStep       = 'erstellen';
    $kCheckBox   = verifyGPCDataInteger('kCheckBox');
    $cPlausi_arr = plausiCheckBox($_POST, $oSprach_arr);
    if (count($cPlausi_arr) === 0) {
        $oCheckBox = speicherCheckBox($_POST, $oSprach_arr);
        $cStep     = 'uebersicht';
        $cHinweis  = 'Ihre Checkbox wurde erfolgreich erstellt.';
    } else {
        $cFehler = 'Fehler: Bitte f&uuml;llen Sie alle n&ouml;tigen Angaben aus!';
        $smarty->assign('cPost_arr', StringHandler::filterXSS($_POST))
               ->assign('cPlausi_arr', $cPlausi_arr);
        if ($kCheckBox > 0) {
            $smarty->assign('kCheckBox', $kCheckBox);
        }
    }
    $cTab = $cStep;
}

$smarty->assign('oCheckBox_arr', $oCheckBox->getAllCheckBox($oBlaetterNaviConf->cSQL1))
       ->assign('oBlaetterNavi', baueBlaetterNavi($oBlaetterNaviConf->nAktuelleSeite1, $oCheckBox->getAllCheckBoxCount(), $nAnzahlProSeite))
       ->assign('cAnzeigeOrt_arr', CheckBox::gibCheckBoxAnzeigeOrte())
       ->assign('CHECKBOX_ORT_REGISTRIERUNG', CHECKBOX_ORT_REGISTRIERUNG)
       ->assign('CHECKBOX_ORT_BESTELLABSCHLUSS', CHECKBOX_ORT_BESTELLABSCHLUSS)
       ->assign('CHECKBOX_ORT_NEWSLETTERANMELDUNG', CHECKBOX_ORT_NEWSLETTERANMELDUNG)
       ->assign('CHECKBOX_ORT_KUNDENDATENEDITIEREN', CHECKBOX_ORT_KUNDENDATENEDITIEREN)
       ->assign('CHECKBOX_ORT_KONTAKT', CHECKBOX_ORT_KONTAKT)
       ->assign('oSprache_arr', $oSprach_arr)
       ->assign('oKundengruppe_arr', Shop::DB()->query("SELECT * FROM tkundengruppe ORDER BY cName", 2))
       ->assign('oLink_arr', Shop::DB()->query("SELECT * FROM tlink ORDER BY cName", 2))
       ->assign('oCheckBoxFunktion_arr', $oCheckBox->getCheckBoxFunctions())
       ->assign('cHinweis', $cHinweis)
       ->assign('cFehler', $cFehler)
       ->assign('step', $cStep)
       ->assign('cTab', $cTab)
       ->display('checkbox.tpl');
