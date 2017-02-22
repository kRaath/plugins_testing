<?php
/**
 * Created by PhpStorm.
 * User: ag-websolutions.de
 * Date: 14.03.2015
 * Time: 20:57
 *
 * File: agws_ts_features_config2.php
 * Project: agws_ts_features
 */

global $smarty, $oPlugin;

if($_SESSION['ts_features_error_add']!="1")
{
    $ts_id_sprache_arr = $GLOBALS["DB"]->executeQuery("SELECT tsprache.* FROM tsprache",2);
    $ts_id_all_arr = $GLOBALS["DB"]->executeQuery("SELECT tsprache.cNameDeutsch,xplugin_agws_ts_features_config.*
                                                    FROM xplugin_agws_ts_features_config
                                                    LEFT JOIN tsprache ON tsprache.kSprache = xplugin_agws_ts_features_config.iTS_Sprache
                                                    WHERE xplugin_agws_ts_features_config.cTS_ID='".filterXSS($_POST['ts_id'])."'",2);
}



$smarty->assign('ts_id_all_arr',$ts_id_all_arr);

$smarty->assign('ts_id_shopsprachen',$ts_id_sprache_arr);
$smarty->assign('ts_id',filterXSS($_POST['ts_id']));
$smarty->assign("URL_ADMINMENU", URL_SHOP . "/" . PFAD_PLUGIN . $oPlugin->cVerzeichnis . "/" . PFAD_PLUGIN_VERSION . $oPlugin->nVersion . "/" . PFAD_PLUGIN_ADMINMENU);

print($smarty->fetch($oPlugin->cAdminmenuPfad . "template/agws_ts_features_config2.tpl"));
?>