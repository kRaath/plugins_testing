<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */
require_once dirname(__FILE__) . '/includes/admininclude.php';

$oAccount->permission('DISPLAY_ARTICLEOVERLAYS_VIEW', true, true);

require_once PFAD_ROOT . PFAD_ADMIN . PFAD_INCLUDES . 'suchspecialoverlay_inc.php';

$cHinweis = '';
$cFehler  = '';
$step     = 'suchspecialoverlay_uebersicht';

setzeSprache();

if (verifyGPCDataInteger('suchspecialoverlay') === 1) {
    $step = 'suchspecialoverlay_detail';

    if (isset($_POST['speicher_einstellung']) && intval($_POST['speicher_einstellung']) === 1 && validateToken()) {
        if (speicherEinstellung(verifyGPCDataInteger('kSuchspecialOverlay'), $_POST, $_FILES)) {
            Shop::Cache()->flushTags(array(CACHING_GROUP_OPTION, CACHING_GROUP_ARTICLE));
            $cHinweis .= 'Ihre Einstellung wurde erfolgreich gespeichert.<br />';
        } else {
            $cFehler .= 'Fehler: Bitte f&uuml;llen Sie alle Felder komplett aus.<br />';
        }
    }
    // Hole bestimmtes SuchspecialOverlay
    if (verifyGPCDataInteger('kSuchspecialOverlay') > 0) {
        $smarty->assign('oSuchspecialOverlay', gibSuchspecialOverlay(verifyGPCDataInteger('kSuchspecialOverlay')));
    }
} else {
    $smarty->assign('oSuchspecialOverlay', gibSuchspecialOverlay(1));
}
$oSuchspecialOverlay_arr = gibAlleSuchspecialOverlays();

$smarty->assign('Sprachen', gibAlleSprachen())
       ->assign('cRnd', time())
       ->assign('oSuchspecialOverlay_arr', $oSuchspecialOverlay_arr)
       ->assign('nSuchspecialOverlayAnzahl', count($oSuchspecialOverlay_arr) + 1)
       ->assign('PFAD_SUCHSPECIALOVERLAY', PFAD_SUCHSPECIALOVERLAY_NORMAL)
       ->assign('hinweis', $cHinweis)
       ->assign('fehler', $cFehler)
       ->assign('step', $step)
       ->display('suchspecialoverlay.tpl');
