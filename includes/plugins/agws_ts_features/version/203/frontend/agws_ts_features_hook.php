<?php
/**
 * Created by PhpStorm.
 * User: ag-websolutions.de
 * Date: 20.03.2015
 * Time: 06:54
 *
 * File: agws_ts_features_hook.php
 * Project: agws_trustedshops
 */

global $smarty;

include_once($oPlugin->cAdminmenuPfad.'includes/agws_ts_features_predefine.php');

switch ($oPlugin->nCalledHook)
{
    case HOOK_SMARTY_OUTPUTFILTER:  //Hook140

        $queryResult = $GLOBALS["DB"]->selectSingleRow("xplugin_agws_ts_features_config", "iTS_Sprache",$_SESSION['kSprache']);

        if(count($queryResult) == 1)
        {
            //pQuery-insert "Trustbadge" auf allen Seiten
            if($queryResult->cTS_BadgeCode != "0")
                pq('body')->append($queryResult->cTS_BadgeCode);
            /**/

            //pQuery-remove "trustedShopsCheckout (alt)" auf allen Seiten
            if ( pq('#trustedShopsCheckout')->length() > 0 )
                pq('#trustedShopsCheckout')->remove();
            /**/

            //pQuery-insert "ReviewSticker" auf allen Seiten im Footer wenn aktiviert
            if($queryResult->bTS_ReviewStickerShow == "1" && $queryResult->iTS_ReviewStickerPosition == "3" && gibSeitenTyp() != PAGE_ARTIKEL)
            {
                $htmlReviewStickerWrapper = $smarty->fetch($oPlugin->cFrontendPfad . "tpl_inc/inc_ts_features_review_footer.tpl");
                $ts_review_pq_selector = TS_REVIEW_PQ_SELECTOR_FOOTER;
                $ts_review_pq_method = TS_REVIEW_PQ_METHOD_FOOTER;
                pq($ts_review_pq_selector)->$ts_review_pq_method($htmlReviewStickerWrapper);
                pq('#tsReviewStickerWrapper')->append($queryResult->cTS_ReviewStickerCode);
            }
            /**/

            //pQuery-insert "RatingWidget" auf allen Seiten im Footer wenn aktiviert
            if($queryResult->bTS_RatingWidgetShow == "1" && $queryResult->iTS_RatingWidgetPosition == "3")
            {
                $htmlRatingWidget = $smarty->fetch($oPlugin->cFrontendPfad . "tpl_inc/inc_ts_features_rating_footer.tpl");
                $ts_rating_pq_selector = TS_RATING_PQ_SELECTOR_FOOTER;
                $ts_rating_pq_method = TS_RATING_PQ_METHOD_FOOTER;
                pq($ts_rating_pq_selector)->$ts_rating_pq_method($htmlRatingWidget);
            }
            /**/

            //pQuery-inserts "Produktbewertung" auf Artikeldetailseite in Registertab
            if ( gibSeitenTyp() == PAGE_ARTIKEL)
            {
                //tab-titel quelltext erzeugen
                $smarty->assign("agws_ts_features_tabtitel",$oPlugin->oPluginSprachvariableAssoc_arr['agws_ts_features_tabtitel']);
                $newTab=$smarty->fetch($oPlugin->cFrontendPfad . "tpl_inc/inc_ts_features_review_article_tab.tpl");
                if(file_exists(PFAD_PLUGIN . $oPlugin->cVerzeichnis . "/" . PFAD_PLUGIN_VERSION . $oPlugin->nVersion . "/" . PFAD_PLUGIN_FRONTEND . "tpl_inc/inc_ts_features_review_article_tab_custom.tpl"))
                    $newTab=$smarty->fetch($oPlugin->cFrontendPfad . "tpl_inc/inc_ts_features_review_article_tab_custom.tpl");

                //tab-inhalt quelltext erzeugen
                $oArtikel_tmp = $smarty->get_template_vars('Artikel');
                ($oArtikel_tmp->nIstVater == 1) ? $smarty->assign("agws_ts_features_showtab",0) : $smarty->assign("agws_ts_features_showtab",1);

                $smarty->assign("agws_ts_features_TSID",$queryResult->cTS_ID);
                $smarty->assign("agws_ts_features_tabintrotext",$oPlugin->oPluginSprachvariableAssoc_arr['agws_ts_features_tabintrotext']);

                $tabpanelContent= $smarty->fetch($oPlugin->cFrontendPfad . "tpl_inc/inc_ts_features_review_article_tabcontent.tpl");
                if(file_exists(PFAD_PLUGIN . $oPlugin->cVerzeichnis . "/" . PFAD_PLUGIN_VERSION . $oPlugin->nVersion . "/" . PFAD_PLUGIN_FRONTEND . "tpl_inc/inc_ts_features_review_article_tabcontent_custom.tpl"))
                    $tabpanelContent= $smarty->fetch($oPlugin->cFrontendPfad . "tpl_inc/inc_ts_features_review_article_tabcontent_custom.tpl");

                //neuen Tab einfügen als letztes element
                $ts_review_artikel_pq_selector_semtabs = TS_REVIEW_ARTIKEL_PQ_SELECTOR_SEMTABS;
                $ts_review_artikel_pq_method_semtabs = TS_REVIEW_ARTIKEL_PQ_METHOD_SEMTABS;
                pq($ts_review_artikel_pq_selector_semtabs)->$ts_review_artikel_pq_method_semtabs($newTab);
                pq("body")->append($tabpanelContent);
            }

            //pQuery-inserts "trustedShopsCheckout" auf Bestellabschlussseite bzw. Statusseite
            if ( gibSeitenTyp() == PAGE_BESTELLABSCHLUSS || gibSeitenTyp() == 0 && $smarty->get_template_vars('step') == 'bestellung')
            {
                (gibSeitenTyp() == PAGE_BESTELLABSCHLUSS) ? $ts_checkout_pq_selector=TS_CHECKOUT_PQ_SELECTOR_ORDERCOMPLETE : $ts_checkout_pq_selector=TS_CHECKOUT_PQ_SELECTOR_STATUSPAGE;
                (gibSeitenTyp() == PAGE_BESTELLABSCHLUSS) ? $ts_checkout_pq_method=TS_CHECKOUT_PQ_METHOD_ORDERCOMPLETE : $ts_checkout_pq_method=TS_CHECKOUT_PQ_METHOD_STATUSPAGE;

                (gibSeitenTyp() == PAGE_BESTELLABSCHLUSS) ? $ts_max_delivery_order_pos_arr = $smarty->get_template_vars('Bestellung') : $ts_max_delivery_order_pos_arr = $smarty->get_template_vars('Bestellung');

                $ts_max_MaxDelivery_Days_arr = array();

                foreach($ts_max_delivery_order_pos_arr->Positionen as  $ts_max_delivery_order_pos) {
                    if ($ts_max_delivery_order_pos->nPosTyp == C_WARENKORBPOS_TYP_ARTIKEL)
                    {
                        $ts_max_MaxDelivery_Days_arr[] = $ts_max_delivery_order_pos->Artikel->nMaxDeliveryDays;
                    }
                }

                if(isset($_SESSION['agws_kWarenkorb_TS']) && $_SESSION['agws_kWarenkorb_TS'] > 0 )
                {
                    $agws_kWarenkorb_TS = $GLOBALS["DB"]->executeQuery("select kBestellung from tbestellung where kWarenkorb='".$_SESSION['agws_kWarenkorb_TS']."'",1);
                    $agws_bestellung_TS = new Bestellung($agws_kWarenkorb_TS->kBestellung);
                    $agws_bestellung_TS->fuelleBestellung(0);

                    $agws_oWarenkorb_Positionen_TS = $agws_bestellung_TS->Positionen;
                    $smarty->assign('agws_oWarenkorb_Positionen_TS',$agws_oWarenkorb_Positionen_TS);
                    unset($_SESSION['agws_kWarenkorb_TS']);
                }

                $ts_max_MaxDelivery_Days = max($ts_max_MaxDelivery_Days_arr);
                $ts_max_MaxDelivery_Date = mktime (0,0,0,date("m"),date("d")+$ts_max_MaxDelivery_Days,date("y"));

                $smarty->assign('ts_features_max_deliverydate',date("Y-m-d",$ts_max_MaxDelivery_Date));
                $htmlResultCardCode=$smarty->fetch($oPlugin->cFrontendPfad . "tpl_inc/inc_ts_features_confirmation_page.tpl");
                pq($ts_checkout_pq_selector)->$ts_checkout_pq_method($htmlResultCardCode);
            }
            /**/

            //pQuery-inserts "RichSnippets" auf Startseite, Kategorieseite, Artikeldetailseite
            if ( gibSeitenTyp() == PAGE_ARTIKEL && $queryResult->bTS_RichSnippetsProduct == '1' || gibSeitenTyp() == PAGE_ARTIKELLISTE && $queryResult->bTS_RichSnippetsCategory == '1' || gibSeitenTyp() == PAGE_STARTSEITE && $queryResult->bTS_RichSnippetsMain == '1')
            {
                $tsId = $queryResult->cTS_ID;
                $cacheFileName = PFAD_LOGFILES . $tsId . '.json';
                $cacheTimeOut = 43200; // half a day
                $apiUrl = str_replace("TS_ID", $queryResult->cTS_ID, TS_RICHSNIPPET_API_URL);
                $reviewsFound = false;
                if (!function_exists('agws_ts_cachecheck'))
                {
                    function agws_ts_cachecheck($filename_cache, $timeout = 10800)
                    {
                        if (file_exists($filename_cache) && time() - filemtime($filename_cache) < $timeout)
                            return true;

                        return false;
                    }
                }
                // check if cached version exists
                if (!agws_ts_cachecheck($cacheFileName, $cacheTimeOut))
                {
                    // load fresh from API
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, false);
                    curl_setopt($ch, CURLOPT_URL, $apiUrl);
                    $output = curl_exec($ch);
                    curl_close($ch);
                    // Write the contents back to the file
                    // Make sure you can write to file's destination
                    $x=file_put_contents($cacheFileName, $output);
                }
                if ($jsonObject = json_decode(file_get_contents($cacheFileName), true))
                {
                    $result = $jsonObject['response']['data']['shop']['qualityIndicators']['reviewIndicator']['overallMark'];
                    $count = $jsonObject['response']['data']['shop']['qualityIndicators']['reviewIndicator']['activeReviewCount'];
                    $shopName = $jsonObject['response']['data']['shop']['name'];
                    $max = "5.00";
                    if ($count > 0) {
                        $reviewsFound = true;
                    }
                }

                if( $reviewsFound)
                {
                    $smarty->assign('ts_features_richsnippet_result',$result);
                    $smarty->assign('ts_features_richsnippet_count',$count);
                    $smarty->assign('ts_features_richsnippet_shopName',utf8_decode($shopName));
                    $smarty->assign('ts_features_richsnippet_max',$max);
                    $smarty->assign('ts_features_richsnippet_tsid',$queryResult->cTS_ID);
                    $htmlRichSnippetsWidget = $smarty->fetch($oPlugin->cFrontendPfad . "tpl_inc/inc_ts_features_richsnippets.tpl");

                    $ts_richsnippet_pq_selector = TS_RICHSNIPPET_PQ_SELECTOR;
                    $ts_richsnippet_pq_method = TS_RICHSNIPPET_PQ_METHOD;
                    pq($ts_richsnippet_pq_selector)->$ts_richsnippet_pq_method($htmlRichSnippetsWidget);
                }
            }
            /**/
        } else {
            pq('#sidebox_ts_rating')->remove();
            pq('#sidebox_ts_review')->remove();
        }
        break;

    case HOOK_SMARTY_INC: //hook 133

        $queryResult = $GLOBALS["DB"]->selectSingleRow("xplugin_agws_ts_features_config", "iTS_Sprache",$_SESSION['kSprache']);

        if(count($queryResult) == 1 && $queryResult->bTS_ReviewStickerShow == "1")
                $smarty->assign('ReviewStickerCode',$queryResult->cTS_ReviewStickerCode);

        if (count($queryResult) == 1 && $queryResult->bTS_RatingWidgetShow == "1")
        {
            $smarty->assign('ts_ratingwidget_img', str_replace("TS_ID", $queryResult->cTS_ID, TS_RATING_LINK_IMG_URL));

            switch ($_SESSION['cISOSprache'])
            {
                case "ger":
                    $smarty->assign('ts_ratingwidget_url', str_replace("TS_ID", $queryResult->cTS_ID, TS_RATING_LINK_URL_DE));
                    $smarty->assign('ts_ratingwidget_alt_title', TS_RATING_LINK_TEXT_DE);
                    break;
                case "eng":
                    $smarty->assign('ts_ratingwidget_url', str_replace("TS_ID", $queryResult->cTS_ID, TS_RATING_LINK_URL_EN));
                    $smarty->assign('ts_ratingwidget_alt_title', TS_RATING_LINK_TEXT_EN);
                    break;
                case "spa":
                    $smarty->assign('ts_ratingwidget_url', str_replace("TS_ID", $queryResult->cTS_ID, TS_RATING_LINK_URL_ES));
                    $smarty->assign('ts_ratingwidget_alt_title', TS_RATING_LINK_TEXT_ES);
                    break;
                case "fre":
                    $smarty->assign('ts_ratingwidget_url', str_replace("TS_ID", $queryResult->cTS_ID, TS_RATING_LINK_URL_FR));
                    $smarty->assign('ts_ratingwidget_alt_title', TS_RATING_LINK_TEXT_FR);
                    break;
                case "pol":
                    $smarty->assign('ts_ratingwidget_url', str_replace("TS_ID", $queryResult->cTS_ID, TS_RATING_LINK_URL_PL));
                    $smarty->assign('ts_ratingwidget_alt_title', TS_RATING_LINK_TEXT_PL);
                    break;
                case "ita":
                    $smarty->assign('ts_ratingwidget_url', str_replace("TS_ID", $queryResult->cTS_ID, TS_RATING_LINK_URL_IT));
                    $smarty->assign('ts_ratingwidget_alt_title', TS_RATING_LINK_TEXT_IT);
                    break;
                case "dut":
                    $smarty->assign('ts_ratingwidget_url', str_replace("TS_ID", $queryResult->cTS_ID, TS_RATING_LINK_URL_NL));
                    $smarty->assign('ts_ratingwidget_alt_title', TS_RATING_LINK_TEXT_NL);
                    break;
                default:
                    $smarty->assign('ts_ratingwidget_url', str_replace("TS_ID", $queryResult->cTS_ID, TS_RATING_LINK_URL_EU));
                    $smarty->assign('ts_ratingwidget_alt_title', TS_RATING_LINK_TEXT_EU);
                    break;
            }
        }

        break;

    case HOOK_BESTELLABSCHLUSS_INC_BESTELLUNGINDB: //hook 75

        $_SESSION['agws_kWarenkorb_TS'] = $args_arr['oBestellung']->kWarenkorb;

        break;

    case HOOK_TOOLSAJAXSERVER_PAGE_TAUSCHEVARIATIONKOMBI: //hook 45

        $queryResult = $GLOBALS["DB"]->selectSingleRow("xplugin_agws_ts_features_config", "iTS_Sprache",$_SESSION['kSprache']);

        if (count($queryResult) == 1 && $queryResult->cTS_ID != "")
        {
            $agws_ts_features_ArtNr = $args_arr['oArtikel']->cArtNr;
            $agws_ts_features_IstVater = $args_arr['oArtikel']->nIstVater;
            $agws_ts_features_TSID = $queryResult->cTS_ID;
            $agws_ts_features_Intro = $oPlugin->oPluginSprachvariableAssoc_arr['agws_ts_features_tabintrotext'];

            $args_arr['objResponse']->script('ts_article_review_init("'.addslashes($agws_ts_features_ArtNr).'", "'.addslashes($agws_ts_features_TSID).'", "'.addslashes($agws_ts_features_Intro).'", "'.addslashes($agws_ts_features_IstVater).'");');
        }

        break;
}