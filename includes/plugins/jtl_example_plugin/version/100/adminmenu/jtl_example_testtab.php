<?php
/**
 * backend tab
 *
 * @package     jtl_example_plugin
 * @author      Felix Moche <felix.moche@jtl-software.com
 * @copyright   2015 JTL-Software-GmbH
 */

global $oPlugin;
require_once $oPlugin->cFrontendPfad . 'inc/class.jtl_example.helper.php';

$error   = null;
$success = null;
$helper  = jtlExampleHelper::getInstance($oPlugin);

if (isset($_POST['clear-cache']) && $_POST['clear-cache'] === '1') {
    if ($helper::isModern()) {
        if (validateToken()) {
            //we used the plugin's ID as an additional cache tag, so we can flush the whole group
            $result = Shop::Cache()->flushTags($oPlugin->pluginCacheGroup);
            //flushGroup() returns the number of deleted entries or FALSE if an error occured
            if (is_numeric($result)) {
                $cHinweis = 'Cache erfolgreich gel&ouml;scht';
            } else {
                $cFehler = 'Konnte Cache nicht l&ouml;schen!';
            }
        } else {
            $cFehler = 'CSRF-Fehler!';
        }
    } else {
        //the JTLCache class does not exist - don't do anything, just inform the user
        $cFehler = 'Cache wird von Ihrer Shop-Version nicht unterst&uuml;tzt!';
    }
}
//$cHinweis and $cFehler will be automatically displayed as alerts in Shop4
$smarty->assign('cHinweis', $success);
$smarty->assign('cFehler', $error);
$smarty->assign('modern', $helper::isModern());
//build a URL to POST to
$smarty->assign('adminURL', ($helper::isModern() ? Shop::getURL() : URL_SHOP) . '/' . PFAD_ADMIN . 'plugin.php?kPlugin=' . $oPlugin->kPlugin);
//display the template
$smarty->display($oPlugin->cAdminmenuPfad . 'templates/testtab.tpl');
