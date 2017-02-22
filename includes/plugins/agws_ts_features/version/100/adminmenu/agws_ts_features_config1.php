<?php
/**
 * Created by PhpStorm.
 * User: ag-websolutions.de
 * Date: 14.03.2015
 * Time: 20:57
 *
 * File: agws_ts_features_config1.php
 * Project: agws_ts_features
 */

global $smarty, $oPlugin;

//ini_set('display_errors',1);
include_once($oPlugin->cAdminmenuPfad.'inc/agws_ts_features_predefine.php');
require_once(PFAD_ROOT . PFAD_CLASSES . "class.JTL-Shop.Boxen.php");

unset($_SESSION['ts_features_error_add']);

$smarty->assign('ts_message','');
$smarty->assign('ts_message_class','');

/** Initialisierung - Standardwerte für Trusted Shop CLASSIC - Einstellungen aus Core neutralisieren */
$ts_init_template_default = new stdClass();
$ts_init_template_default->cWert = "N";
$GLOBALS["DB"]->updateRow("ttemplateeinstellungen", "cName", "show_trustbadge3", $ts_init_template_default);

$ts_init_TSID_classic_arr = $GLOBALS["DB"]->executeQuery("SELECT cTSID FROM ttrustedshopszertifikat WHERE eType = 'CLASSIC'",2);
if (count($ts_init_TSID_classic_arr)>0){
    foreach($ts_init_TSID_classic_arr as $ts_init_TSID_classic) {
        $GLOBALS["DB"]->deleteRow ("ttrustedshopskundenbewertung", "cTSID", $ts_init_TSID_classic->cTSID, $echo=0);
        $GLOBALS["DB"]->deleteRow ("ttrustedshopsstatistik", "cTSID", $ts_init_TSID_classic->cTSID, $echo=0);
        $GLOBALS["DB"]->deleteRow ("ttrustedshopszertifikat", "cTSID", $ts_init_TSID_classic->cTSID, $echo=0);
    }
}
/**/

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['ts_id']) && filterXSS($_POST['ts_id']) != "" && isset($_POST['ts_id_is_add']) && filterXSS($_POST['ts_id_is_add'])==1)
	{
		$ts_id_add = new stdClass();
		$ts_id_add->cTS_ID = filterXSS($_POST['ts_id']);
        $ts_id_add->cTS_BadgeCode = "";

        $smarty->assign('ts_id_review_pre',filterXSS($_POST['ts_id']));
        $ts_review_pre = $smarty->fetch($oPlugin->cAdminmenuPfad . "template/tpl_inc/inc_ts_features_review_pre.tpl");
        $ts_id_add->cTS_ReviewStickerCode = $ts_review_pre;

        $queryResult = $GLOBALS["DB"]->insertRow("xplugin_agws_ts_features_config", $ts_id_add);

        if($queryResult==1)
        {
            $_SESSION['ts_features_error_add']="0";
            $smarty->assign('ts_message','Die TS-ID: '.filterXSS($_POST['ts_id']).' wurde angelegt!');
            $smarty->assign('ts_message_class','box_success');
        } else {
            $_SESSION['ts_features_error_add']="1";
            $js_redirect = '<script type="text/javascript">';
            $js_redirect .= 'window.location = "' . gibShopURL() .'/admin/plugin.php?kPlugin='.$oPlugin->kPlugin.'&cPluginTab=Konfiguration&ts_add_error=1' . '"';
            $js_redirect .= '</script>';
            echo $js_redirect;
        }
	}

    if (isset($_POST['ts_id']) && filterXSS($_POST['ts_id']) != "" && isset($_POST['ts_id_is_delete']) && filterXSS($_POST['ts_id_is_delete'])==1)
    {
        $queryResult = $GLOBALS["DB"]->deleteRow("xplugin_agws_ts_features_config", "cTS_ID",filterXSS($_POST['ts_id']));

        if($queryResult==1)
        {
            $smarty->assign('ts_message','Die TS-ID: '.filterXSS($_POST['ts_id']).' wurde gelöscht!');
            $smarty->assign('ts_message_class','box_success');
        } else {
            $smarty->assign('ts_message','Es trat ein Fehler auf - TS-ID konnte nicht gelöscht werden!');
            $smarty->assign('ts_message_class','box_error');
        }
    }

    if (isset($_POST['ts_id']) && filterXSS($_POST['ts_id']) != "" && isset($_POST['ts_id_options_cancel']) && filterXSS($_POST['ts_id_options_cancel'])==1)
    {
        $smarty->assign('ts_message','Die Bearbeitung wurde abgebrochen - Änderungen wurden nicht gespeichert!');
        $smarty->assign('ts_message_class','box_info');
    }

    if (isset($_POST['ts_id']) && filterXSS($_POST['ts_id']) != "" && isset($_POST['ts_id_options_save']) && filterXSS($_POST['ts_id_options_save'])==1)
    {
        $ts_id_edit = new stdClass();
        $ts_id_edit->cTS_ID = filterXSS($_POST['ts_id']);
        $ts_id_edit->iTS_Sprache = filterXSS($_POST['ts_sprache']);
        $ts_id_edit->cTS_BadgeCode = $_POST['ts_BadgeCode'];
        $ts_id_edit->bTS_RatingWidgetShow = filterXSS($_POST['ts_RatingWidgetShow']);
        $ts_id_edit->iTS_RatingWidgetPosition = filterXSS($_POST['ts_RatingWidgetPosition']);
        $ts_id_edit->bTS_ReviewStickerShow = filterXSS($_POST['ts_ReviewStickerShow']);
        $ts_id_edit->iTS_ReviewStickerPosition = filterXSS($_POST['ts_ReviewStickerPosition']);
        $ts_id_edit->bTS_RichSnippetsCategory = filterXSS($_POST['ts_RichSnippetsCategory']);
        $ts_id_edit->bTS_RichSnippetsProduct = filterXSS($_POST['ts_RichSnippetsProduct']);
        $ts_id_edit->bTS_RichSnippetsMain = filterXSS($_POST['ts_RichSnippetsMain']);
        $ts_id_edit->cTS_ReviewStickerCode = $_POST['ts_ReviewStickerCode'];

        $oTSFeature_Box = new Boxen();

        /** Boxensteuerung Review-Sticker **/
        $ts_check_box = $GLOBALS["DB"]->selectSingleRow('tboxen', 'cTitel', 'Trusted Shops - Reviews');
        $ts_check_box_vorlage = $GLOBALS["DB"]->selectSingleRow('tboxvorlage', 'cName', 'Trusted Shops - Reviews');

        if(count($ts_check_box) == 1)
                $oTSFeature_Box->loescheBox($ts_check_box->kBox);

        if((int)$ts_id_edit->iTS_ReviewStickerPosition==1)
            $oTSFeature_Box->setzeBox($ts_check_box_vorlage->kBoxvorlage,0,'left');

        if((int)$ts_id_edit->iTS_ReviewStickerPosition==2)
            $oTSFeature_Box->setzeBox($ts_check_box_vorlage->kBoxvorlage,0,'right');
        /**/

        /** Boxensteuerung Rating-Widget **/
        $ts_check_box = $GLOBALS["DB"]->selectSingleRow('tboxen', 'cTitel', 'Trusted Shops - Rating');
        $ts_check_box_vorlage = $GLOBALS["DB"]->selectSingleRow('tboxvorlage', 'cName', 'Trusted Shops - Rating');

        if(count($ts_check_box) == 1)
            $oTSFeature_Box->loescheBox($ts_check_box->kBox);

        if((int)$ts_id_edit->iTS_RatingWidgetPosition==1)
            $oTSFeature_Box->setzeBox($ts_check_box_vorlage->kBoxvorlage,0,'left');

        if((int)$ts_id_edit->iTS_RatingWidgetPosition==2)
            $oTSFeature_Box->setzeBox($ts_check_box_vorlage->kBoxvorlage,0,'right');

        $queryResult = $GLOBALS["DB"]->updateRow("xplugin_agws_ts_features_config", "cTS_ID", $ts_id_edit->cTS_ID, $ts_id_edit);
        if($queryResult==1)
        {
            $smarty->assign('ts_message','Die Konfiguration wurden gespeichert!');
            $smarty->assign('ts_message_class','box_success');
        } else {
            $smarty->assign('ts_message','Es trat ein Fehler auf - Konfiguration konnte nicht gespeichert werden!');
            $smarty->assign('ts_message_class','box_error');
        }
        /**/
    }
}

if (isset($_GET['ts_add_error']) && $_GET['ts_add_error'] ==1)
{
    $smarty->assign('ts_message','Es trat ein Fehler auf - TS-ID ist bereits installiert oder konnte nicht eingefügt werden!');
    $smarty->assign('ts_message_class','box_error');
}

$ts_id_all_arr = $GLOBALS["DB"]->executeQuery("SELECT tsprache.cNameDeutsch,xplugin_agws_ts_features_config.*
                                                FROM xplugin_agws_ts_features_config
                                                LEFT JOIN tsprache ON tsprache.kSprache = xplugin_agws_ts_features_config.iTS_Sprache",2);

$smarty->assign('ts_id_all_arr',$ts_id_all_arr);
$smarty->assign('ts_id_add_form_action','plugin.php?kPlugin='.$oPlugin->kPlugin.'&cPluginTab=Erweiterte%20Konfiguration');
$smarty->assign('ts_id_edit_form_action','plugin.php?kPlugin='.$oPlugin->kPlugin.'&cPluginTab=Erweiterte%20Konfiguration');
$smarty->assign('ts_id_delete_form_action','plugin.php?kPlugin='.$oPlugin->kPlugin.'&cPluginTab=Konfiguration');
$smarty->assign('ts_id_cancel_form_action','plugin.php?kPlugin='.$oPlugin->kPlugin.'&cPluginTab=Konfiguration');
$smarty->assign('ts_id_save_form_action','plugin.php?kPlugin='.$oPlugin->kPlugin.'&cPluginTab=Konfiguration');
$smarty->assign("URL_ADMINMENU", URL_SHOP . "/" . PFAD_PLUGIN . $oPlugin->cVerzeichnis . "/" . PFAD_PLUGIN_VERSION . $oPlugin->nVersion . "/" . PFAD_PLUGIN_ADMINMENU);

print($smarty->fetch($oPlugin->cAdminmenuPfad . "template/agws_ts_features_config1.tpl"));
?>