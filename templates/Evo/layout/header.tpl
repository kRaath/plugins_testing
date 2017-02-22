<!DOCTYPE html>
<html lang="{$meta_language}">
<head>
    {block name="head-meta"}
        <meta http-equiv="content-type" content="text/html; charset=windows-1252">
        <meta name="description" content={block name="head-meta-description"}"{$meta_description|truncate:1000:"":true}{/block}">
        <meta name="keywords" content="{block name="head-meta-keywords"}{$meta_keywords|truncate:255:"":true}{/block}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="robots" content="{if $bNoIndex === true  || (isset($Link->cNoFollow) && $Link->cNoFollow === 'Y')}noindex{else}index, follow{/if}">
    {/block}

    <title>{block name="head-title"}{$meta_title}{/block}</title>

    {if !empty($cCanonicalURL)}
        <link rel="canonical" href="{$cCanonicalURL}">
    {/if}

    {block name="head-base"}
        <base href="{$ShopURL}/">
    {/block}

    {block name="head-icons"}
        <link type="image/x-icon" href="{$currentTemplateDir}themes/base/images/{if !empty($Einstellungen.template.theme.favicon)}{$Einstellungen.template.theme.favicon}{else}favicon-jtl.ico{/if}" rel="shortcut icon">
        <link href="{$currentTemplateDir}themes/base/images/apple-touch-icon.png" rel="apple-touch-icon">
        {if $nSeitenTyp == 1 && isset($Artikel) && !empty($Artikel->Bilder)}
            <link rel="image_src" href="{$ShopURL}/{$Artikel->Bilder[0]->cPfadGross}">
            <meta property="og:image" content="{$ShopURL}/{$Artikel->Bilder[0]->cPfadGross}">
        {/if}
    {/block}

    {block name="head-resources"}
        {* css *}
        {if !isset($Einstellungen.template.general.use_minify) || $Einstellungen.template.general.use_minify === 'N'}
            {foreach from=$cCSS_arr item="cCSS"}
                <link type="text/css" href="{$cCSS}?v={$nTemplateVersion}" rel="stylesheet">
            {/foreach}

            {if isset($cPluginCss_arr)}
                {foreach from=$cPluginCss_arr item="cCSS"}
                    <link type="text/css" href="{$cCSS}?v={$nTemplateVersion}" rel="stylesheet">
                {/foreach}
            {/if}
        {else}
            <link type="text/css" href="asset/{$Einstellungen.template.theme.theme_default}.css?v={$nTemplateVersion}" rel="stylesheet">
            {if isset($cPluginCss_arr) && $cPluginCss_arr|@count > 0}
                <link type="text/css" href="asset/plugin_css?v={$nTemplateVersion}" rel="stylesheet">
            {/if}
        {/if}
        {* RSS *}
        {if isset($Einstellungen.rss.rss_nutzen) && $Einstellungen.rss.rss_nutzen === 'Y'}
            <link rel="alternate" type="application/rss+xml" title="Newsfeed {$Einstellungen.global.global_shopname}" href="rss.xml">
        {/if}
    {/block}

    {* Pagination *}
    {if isset($Suchergebnisse->Seitenzahlen->maxSeite) && $Suchergebnisse->Seitenzahlen->maxSeite > 1 && isset($oNaviSeite_arr) && $oNaviSeite_arr|@count > 0}
        {if $Suchergebnisse->Seitenzahlen->AktuelleSeite>1}
            <link rel="prev" href="{$oNaviSeite_arr.zurueck->cURL}">
        {/if}
        {if $Suchergebnisse->Seitenzahlen->AktuelleSeite < $Suchergebnisse->Seitenzahlen->maxSeite}
            <link rel="next" href="{$oNaviSeite_arr.vor->cURL}">
        {/if}
    {/if}

    {if !empty($Einstellungen.template.theme.backgroundcolor) && $Einstellungen.template.theme.backgroundcolor|strlen > 1}
        <style>
            body { background-color: {$Einstellungen.template.theme.backgroundcolor}!important; }
        </style>
    {/if}
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    {include file='layout/header_inline_js.tpl'}
</head>
{assign var="isFluidContent" value=false}
{if isset($Einstellungen.template.theme.pagelayout) && $Einstellungen.template.theme.pagelayout === 'fluid' && isset($Link) && $Link->bIsFluid}
    {assign var="isFluidContent" value=true}
{/if}
{has_boxes position='left' assign='hasLeftPanel'}
{block name="body-tag"}
<body data-page="{$nSeitenTyp}" class="body-offcanvas"{if isset($Link) && !empty($Link->cIdentifier)} id="{$Link->cIdentifier}"{/if}>
{/block}
<div id="main-wrapper" class="main-wrapper{if $bExclusive} exclusive{/if}{if isset($Einstellungen.template.theme.pagelayout) && $Einstellungen.template.theme.pagelayout === 'boxed'} boxed{else} fluid{/if}{if $hasLeftPanel} aside-active{/if}">
{if !$bExclusive}

{if isset($bAdminWartungsmodus) && $bAdminWartungsmodus}
    <div id="maintenance-mode" class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-text text-center">
                {lang key="adminMaintenanceMode" section="global"}
            </div>
        </div>
     </div>
{/if}

<header class="{if isset($Einstellungen.template.theme.pagelayout) && $Einstellungen.template.theme.pagelayout === 'fluid'}container-block{/if}{if $Einstellungen.template.theme.static_header === 'Y'} fixed-navbar{/if}" id="evo-nav-wrapper">
    <div class="container">
        {if isset($Einstellungen.template.theme.pagelayout) && $Einstellungen.template.theme.pagelayout !== 'fluid'}
            <div class="container-block clearfix">
        {/if}
        <div id="header-branding" >
            {block name="header-branding"}
                <div id="top-bar" class="text-right hidden-xs clearfix">
                    {include file="layout/header_top_bar.tpl"}
                </div>
                <div class="row">
                    <div class="col-xs-4 col-sm-2 col-md-4" id="logo">
                        {block name="logo"}
                        <a href="{$ShopURL}" title="{$Einstellungen.global.global_shopname}">
                            {if isset($ShopLogoURL)}
                                {image src=$ShopLogoURL alt=$Einstellungen.global.global_shopname class="img-responsive"}
                            {else}
                                <span class="h1">{$Einstellungen.global.global_shopname}</span>
                            {/if}
                        </a>
                        {/block}
                    </div>

                    <div class="col-xs-8 col-sm-10 col-md-8" id="shop-nav">
                        {include file='layout/header_shop_nav.tpl'}
                    </div>
                </div>
            {/block}{* /header-branding *}
        </div>
        {if isset($Einstellungen.template.theme.pagelayout) && $Einstellungen.template.theme.pagelayout !== 'fluid'}
            </div>{* /container-block *}
        {/if}
    </div>{* /container *}
    {if isset($Einstellungen.template.theme.pagelayout) && $Einstellungen.template.theme.pagelayout !== 'fluid'}
        <div class="container">
    {/if}
    <div class="category-nav navbar-wrapper hidden-xs">
        {include file="layout/header_category_nav.tpl"}
    </div>{* /category-nav *}
    {if isset($Einstellungen.template.theme.pagelayout) && $Einstellungen.template.theme.pagelayout !== 'fluid'}
        </div>{* /container *}
    {/if}
    {include file="layout/header_xs_nav.tpl"}
</header>
{/if}
<div id="content-wrapper">
    {assign var="isFluidSlider" value=isset($Einstellungen.template.theme.slider_full_width) && $Einstellungen.template.theme.slider_full_width === 'Y' &&  isset($Einstellungen.template.theme.pagelayout) && $Einstellungen.template.theme.pagelayout === 'fluid' && isset($oSlider->oSlide_arr) && count($oSlider->oSlide_arr) > 0}
    {if $isFluidSlider}
        {include file="snippets/slider.tpl"}
    {/if}
    <div{if !$bExclusive} class="container{if $isFluidContent}-fluid{/if}{/if}">
        <div class="container-block{if !$isFluidContent} beveled{/if}">
            {if $Einstellungen.artikeldetails.artikeldetails_navi_blaettern === 'Y' && isset($NavigationBlaettern)}
                <div class="visible-lg product-pagination next">
                    {if isset($NavigationBlaettern->naechsterArtikel) && $NavigationBlaettern->naechsterArtikel->kArtikel}<a href="{$NavigationBlaettern->naechsterArtikel->cURL}" title="{$NavigationBlaettern->naechsterArtikel->cName}"><span class="fa fa-chevron-right"></span></a>{/if}
                </div>
                <div class="visible-lg product-pagination previous">
                    {if isset($NavigationBlaettern->vorherigerArtikel) && $NavigationBlaettern->vorherigerArtikel->kArtikel}<a href="{$NavigationBlaettern->vorherigerArtikel->cURL}" title="{$NavigationBlaettern->vorherigerArtikel->cName}"><span class="fa fa-chevron-left"></span></a>{/if}
                </div>
            {/if}
            <div class="row">
                <div id="content" class="col-xs-12{if isset($boxes.left) && !$bExclusive && !empty($boxes.left)} {if $nSeitenTyp === 2} col-md-8 col-md-push-4 {/if} col-lg-9 col-lg-push-3{/if}">
                    {include file='layout/breadcrumb.tpl'}