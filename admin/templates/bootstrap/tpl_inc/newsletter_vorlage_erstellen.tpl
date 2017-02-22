<link type="text/css" rel="stylesheet" href="{$shopURL}/{$PFAD_ADMIN}{$currentTemplateDir}js/js_calender/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen" />
<script type="text/javascript" src="{$shopURL}/{$PFAD_ADMIN}{$currentTemplateDir}js/js_calender/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript">
var fields = 0;

function neu() {ldelim}
    if (fields != 10) {ldelim}
        document.getElementById('ArtNr').innerHTML += '<input name="cArtNr[]" type="text" class="field" />';
        fields += 1;
    {rdelim} else {ldelim}
        document.getElementById('ArtNr').innerHTML += '';
        document.form.add.disabled=true;
    {rdelim}
{rdelim}

function checkNewsletterSend() {ldelim}
    var bCheck = confirm("{#newsletterSendAuthentication#}");
    if(bCheck) {ldelim}
        var input1 = document.createElement('input');
        input1.type = 'hidden';
        input1.name = 'speichern_und_senden';
        input1.value = '1';
        document.getElementById('formnewslettervorlage').appendChild(input1);
        document.formnewslettervorlage.submit();
    {rdelim}
{rdelim}
</script>

<div id="page">
   {include file='tpl_inc/seite_header.tpl' cTitel=#newsletterdraft# cBeschreibung=#newsletterdraftdesc#}
    <div id="content" class="container-fluid">
        <form name="formnewslettervorlage" id="formnewslettervorlage" method="post" action="newsletter.php">
            {$jtl_token}
            <input name="newslettervorlagen" type="hidden" value="1">
            <input name="tab" type="hidden" value="newslettervorlagen">

            {if isset($oNewsletterVorlage->kNewsletterVorlage) && $oNewsletterVorlage->kNewsletterVorlage}
                <input name="kNewsletterVorlage" type="hidden" value="{$oNewsletterVorlage->kNewsletterVorlage}">
            {/if}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Vorlage erstellen</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <label for="cName">{#newsletterdraftname#}</label>
                        </span>
                        <input id="cName" name="cName" type="text" class="form-control {if isset($cPlausiValue_arr.cName)}fieldfillout{else}field{/if}" value="{if isset($cPostVar_arr.cName)}{$cPostVar_arr.cName}{elseif isset($oNewsletterVorlage->cName)}{$oNewsletterVorlage->cName}{/if}">
                        {if isset($cPlausiValue_arr.cName)}<font class="fillout">{#newsletterdraftFillOut#}</font>{/if}
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <label for="cBetreff">{#newsletterdraftsubject#}</label>
                        </span>
                        <input name="cBetreff" type="text" class="form-control {if isset($cPlausiValue_arr.cBetreff)}fieldfillout{else}field{/if}" value="{if isset($cPostVar_arr.cBetreff)}{$cPostVar_arr.cBetreff}{elseif isset($oNewsletterVorlage->cBetreff)}{$oNewsletterVorlage->cBetreff}{/if}">
                        {if isset($cPlausiValue_arr.cBetreff)}<font class="fillout">{#newsletterdraftFillOut#}</font>{/if}
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <label for="kKundengruppe">{#newslettercustomergrp#}</label>
                        </span>
                        <span class="input-group-wrap">
                            <select id="kKundengruppe" name="kKundengruppe[]" multiple="multiple" class="form-control {if isset($cPlausiValue_arr.kKundengruppe_arr)}fieldfillout{else}combo{/if}">
                                <option value="0"
                                        {if isset($kKundengruppe_arr)}
                                            {foreach name=kkundengruppen from=$kKundengruppe_arr item=kKundengruppe}
                                                {if $kKundengruppe == "0"}selected{/if}
                                            {/foreach}
                                        {elseif isset($cPostVar_arr.kKundengruppe)}
                                            {foreach name=kkundengruppen from=$cPostVar_arr.kKundengruppe item=kKundengruppe}
                                                {if $kKundengruppe == "0"}selected{/if}
                                            {/foreach}
                                        {/if}
                                        >Newsletterempf&auml;nger ohne Kundenkonto</option>
                                {foreach name=kundengruppen from=$oKundengruppe_arr item=oKundengruppe}
                                    <option value="{$oKundengruppe->kKundengruppe}"
                                            {if isset($kKundengruppe_arr)}
                                                {foreach name=kkundengruppen from=$kKundengruppe_arr item=kKundengruppe}
                                                    {if $oKundengruppe->kKundengruppe == $kKundengruppe}selected{/if}
                                                {/foreach}
                                            {elseif isset($cPostVar_arr.kKundengruppe)}
                                                {foreach name=kkundengruppen from=$cPostVar_arr.kKundengruppe item=kKundengruppe}
                                                    {if $oKundengruppe->kKundengruppe == $kKundengruppe}selected{/if}
                                                {/foreach}
                                            {/if}
                                            >{$oKundengruppe->cName}</option>
                                {/foreach}
                            </select>
                        </span>
                        {if isset($cPlausiValue_arr.kKundengruppe_arr)}<font class="fillout">{#newsletterdraftFillOut#}</font>{/if}
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <label for="cArt">{#newsletterdraftcharacter#}</label>
                        </span>
                        <span class="input-group-wrap">
                            <select id="cArt" name="cArt" class="form-control combo">
                                <option {if isset($oNewsletterVorlage->cArt) && $oNewsletterVorlage->cArt === 'text/html'}selected{/if}>text/html</option>
                                <option {if isset($oNewsletterVorlage->cArt) && $oNewsletterVorlage->cArt === 'text'}selected{/if}>text</option>
                            </select>
                        </span>
                    </div>
                    <div class="input-group input-group-select">
                        <span class="input-group-addon">
                            <label for="cArt">{#newsletterdraftdate#}</label>
                        </span>
                        <span class="input-group-wrap">
                            <select name="dTag" class="form-control combo" style="width:100%;">
                                {section name=dTag start=1 loop=32 step=1}
                                    {if $smarty.section.dTag.index < 10}
                                        <option value="0{$smarty.section.dTag.index}"{if isset($oNewsletterVorlage->oZeit->cZeit_arr) && $oNewsletterVorlage->oZeit->cZeit_arr|@count > 0}{if $oNewsletterVorlage->oZeit->cZeit_arr[0] == $smarty.section.dTag.index} selected{/if}{else}{if $smarty.now|date_format:"%d" == $smarty.section.dTag.index} selected{/if}{/if}>0{$smarty.section.dTag.index}</option>
                                    {else}
                                        <option value="{$smarty.section.dTag.index}"{if isset($oNewsletterVorlage->oZeit->cZeit_arr) && $oNewsletterVorlage->oZeit->cZeit_arr|@count > 0}{if $oNewsletterVorlage->oZeit->cZeit_arr[0] == $smarty.section.dTag.index} selected{/if}{else}{if $smarty.now|date_format:"%d" == $smarty.section.dTag.index} selected{/if}{/if}>{$smarty.section.dTag.index}</option>
                                    {/if}
                                {/section}
                            </select>
                        </span>
                        <span class="input-group-wrap">
                            <select name="dMonat" class="form-control combo" style="width:100%;">
                                {section name=dMonat start=1 loop=13 step=1}
                                    {if $smarty.section.dMonat.index < 10}
                                        <option value="0{$smarty.section.dMonat.index}"{if isset($oNewsletterVorlage->oZeit->cZeit_arr) && $oNewsletterVorlage->oZeit->cZeit_arr|@count > 0}{if $oNewsletterVorlage->oZeit->cZeit_arr[1] == $smarty.section.dMonat.index} selected{/if}{else}{if $smarty.now|date_format:"%m" == $smarty.section.dMonat.index} selected{/if}{/if}>0{$smarty.section.dMonat.index}</option>
                                    {else}
                                        <option value="{$smarty.section.dMonat.index}"{if isset($oNewsletterVorlage->oZeit->cZeit_arr) && $oNewsletterVorlage->oZeit->cZeit_arr|@count > 0}{if $oNewsletterVorlage->oZeit->cZeit_arr[1] == $smarty.section.dMonat.index} selected{/if}{else}{if $smarty.now|date_format:"%m" == $smarty.section.dMonat.index} selected{/if}{/if}>{$smarty.section.dMonat.index}</option>
                                    {/if}
                                {/section}
                            </select>
                        </span>
                        <span class="input-group-wrap">
                            <select name="dJahr" class="form-control combo" style="width:100%;">
                                {$Y = $smarty.now|date_format:"%Y"}
                                {section name=dJahr start=$Y loop=($Y+2) step=1}
                                    <option value="{$smarty.section.dJahr.index}"{if isset($oNewsletterVorlage->oZeit->cZeit_arr) && $oNewsletterVorlage->oZeit->cZeit_arr|@count > 0}{if $oNewsletterVorlage->oZeit->cZeit_arr[2] == $smarty.section.dJahr.index} selected{/if}{else}{if $smarty.now|date_format:"%Y" == $smarty.section.dJahr.index} selected{/if}{/if}>{$smarty.section.dJahr.index}</option>
                                {/section}
                            </select>
                        </span>
                        <span class="input-group-wrap">
                            <select name="dStunde" class="form-control combo" style="width:100%;">
                                {section name=dStunde start=0 loop=24 step=1}
                                    {if $smarty.section.dStunde.index < 10}
                                        <option value="0{$smarty.section.dStunde.index}"{if isset($oNewsletterVorlage->oZeit->cZeit_arr) && $oNewsletterVorlage->oZeit->cZeit_arr|@count > 0}{if $oNewsletterVorlage->oZeit->cZeit_arr[3] == $smarty.section.dStunde.index} selected{/if}{else}{if $smarty.now|date_format:"%H" == $smarty.section.dStunde.index} selected{/if}{/if}>0{$smarty.section.dStunde.index}</option>
                                    {else}
                                        <option value="{$smarty.section.dStunde.index}"{if isset($oNewsletterVorlage->oZeit->cZeit_arr) && $oNewsletterVorlage->oZeit->cZeit_arr|@count > 0}{if $oNewsletterVorlage->oZeit->cZeit_arr[3] == $smarty.section.dStunde.index} selected{/if}{else}{if $smarty.now|date_format:"%H" == $smarty.section.dStunde.index} selected{/if}{/if}>{$smarty.section.dStunde.index}</option>
                                    {/if}
                                {/section}
                            </select>
                        </span>
                        <span class="input-group-wrap">
                            <select name="dMinute" class="form-control combo" style="width:100%;">
                                {section name=dMinute start=0 loop=60 step=1}
                                    {if $smarty.section.dMinute.index < 10}
                                        <option value="0{$smarty.section.dMinute.index}"{if isset($oNewsletterVorlage->oZeit->cZeit_arr) && $oNewsletterVorlage->oZeit->cZeit_arr|@count > 0}{if $oNewsletterVorlage->oZeit->cZeit_arr[4] == $smarty.section.dMinute.index} selected{/if}{else}{if $smarty.now|date_format:"%M" == $smarty.section.dMinute.index} selected{/if}{/if}>0{$smarty.section.dMinute.index}</option>
                                    {else}
                                        <option value="{$smarty.section.dMinute.index}"{if isset($oNewsletterVorlage->oZeit->cZeit_arr) && $oNewsletterVorlage->oZeit->cZeit_arr|@count > 0}{if $oNewsletterVorlage->oZeit->cZeit_arr[4] == $smarty.section.dMinute.index} selected{/if}{else}{if $smarty.now|date_format:"%M" == $smarty.section.dMinute.index} selected{/if}{/if}>{$smarty.section.dMinute.index}</option>
                                    {/if}
                                {/section}
                            </select>
                        </span>
                        <span class="input-group-addon">{#newsletterdraftformat#}</span>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <label for="kKampagne">{#newslettercampaign#}</label>
                        </span>
                        <span class="input-group-wrap">
                            <select class="form-control " id="kKampagne" name="kKampagne">
                                <option value="0"></option>
                                {foreach name="" from=$oKampagne_arr item=oKampagne}
                                    <option value="{$oKampagne->kKampagne}"{if isset($oNewsletterVorlage->kKampagne) && $oKampagne->kKampagne == $oNewsletterVorlage->kKampagne || (isset($cPostVar_arr.kKampagne) && isset($oKampagne->kKampagne) && $cPostVar_arr.kKampagne == $oKampagne->kKampagne)} selected{/if}>{$oKampagne->cName}</option>
                                {/foreach}
                            </select>
                        </span>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <label for="assign_article_list">{#newsletterartnr#}</label>
                        </span>
                        <input class="form-control" name="cArtikel" id="assign_article_list" type="text" value="{if isset($cPostVar_arr.cArtikel) && $cPostVar_arr.cArtikel|count_characters > 0}{$cPostVar_arr.cArtikel}{elseif isset($oNewsletterVorlage->cArtikel)}{$oNewsletterVorlage->cArtikel}{/if}" />
                        <span class="input-group-btn">
                            <a href="#" class="btn btn-default btn-info" id="show_article_list">Artikel verwalten</a>
                        </span>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <label for="assign_manufacturer_list">{#newslettermanufacturer#}</label>
                        </span>
                        <input class="form-control" name="cHersteller" id="assign_manufacturer_list" type="text" value="{if isset($cPostVar_arr.cHersteller) && $cPostVar_arr.cHersteller|count_characters > 0}{$cPostVar_arr.cHersteller}{elseif isset($oNewsletterVorlage->cHersteller)}{$oNewsletterVorlage->cHersteller}{/if}" />
                        <span class="input-group-btn">
                            <a href="#" class="btn btn-default btn-info" id="show_manufacturer_list">Hersteller verwalten</a>
                        </span>

                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <label for="assign_categories_list">{#newslettercategory#}</label>
                        </span>
                        <input class="form-control" name="cKategorie" id="assign_categories_list" type="text" value="{if isset($cPostVar_arr.cKategorie) && $cPostVar_arr.cKategorie|count_characters > 0}{$cPostVar_arr.cKategorie}{elseif isset($oNewsletterVorlage->cKategorie)}{$oNewsletterVorlage->cKategorie}{/if}" />
                        <span class="input-group-btn">
                            <a href="#" class="btn btn-default btn-info" id="show_categories_list">Kategorien verwalten</a>
                        </span>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <label for="cHtml">{#newsletterHtml#}</label>
                        </span>
                        <textarea class="codemirror smarty form-control" id="cHtml" name="cHtml" style="width: 750px; height: 400px;">{if isset($cPostVar_arr.cHtml)}{$cPostVar_arr.cHtml}{elseif isset($oNewsletterVorlage->cInhaltHTML)}{$oNewsletterVorlage->cInhaltHTML}{/if}</textarea>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <label for="cText">{#newsletterText#}</label>
                        </span>
                        <textarea class="codemirror smarty form-control" id="cText" name="cText" style="width: 750px; height: 400px;">{if isset($cPostVar_arr.cText)}{$cPostVar_arr.cText}{elseif isset($oNewsletterVorlage->cInhaltText)}{$oNewsletterVorlage->cInhaltText}{/if}</textarea>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="btn-group">
                        <button class="btn btn-primary" name="speichern" type="submit" value="{#newsletterdraftsave#}"><i class="fa fa-save"></i> {#newsletterdraftsave#}</button>
                        {if $cOption !== 'editieren'}
                            <button class="btn btn-warning" name="speichern_und_senden" type="button" value="{#newsletterdraftsaveandsend#}" onclick="checkNewsletterSend();">{#newsletterdraftsaveandsend#}</button>
                        {/if}
                        <button class="btn btn-default" name="speichern_und_testen" type="submit" value="{#newsletterdraftsaveandtest#}">{#newsletterdraftsaveandtest#}</button>
                    </div>
                </div>
            </div>

            <div id="ajax_list_picker1" class="ajax_list_picker article">{include file="tpl_inc/popup_artikelsuche.tpl"}</div>
            <div id="ajax_list_picker2" class="ajax_list_picker manufacturer">{include file="tpl_inc/popup_herstellersuche.tpl"}</div>
            <div id="ajax_list_picker3" class="ajax_list_picker categories">{include file="tpl_inc/popup_kategoriesuche.tpl"}</div>
        </form>
        <form method="post" action="newsletter.php">
            {$jtl_token}
            <input name="tab" type="hidden" value="newslettervorlagen" />
            <p>
                <button class="btn btn-default" name="back" type="submit" value="{#newsletterback#}"><i class="fa fa-angle-double-left"></i> {#newsletterback#}</button>
            </p>
        </form>
    </div>
</div>
