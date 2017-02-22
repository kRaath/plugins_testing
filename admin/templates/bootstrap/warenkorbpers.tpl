{include file='tpl_inc/header.tpl'}
{config_load file="$lang.conf" section="warenkorbpers"}
{include file='tpl_inc/seite_header.tpl' cTitel=#warenkorbpers# cBeschreibung=#warenkorbpersDesc# cDokuURL=#warenkorbpersURL#}
<div id="content" class="container-fluid">
    {if $step === 'uebersicht'}
        <ul class="nav nav-tabs" role="tablist">
            <li class="tab{if !isset($tab) || $tab === 'warenkorbpers'} active{/if}">
                <a data-toggle="tab" role="tab" href="#massaction">{#warenkorbpers#}</a>
            </li>
            <li class="tab{if isset($tab) && $tab === 'einstellungen'} active{/if}">
                <a data-toggle="tab" role="tab" href="#settings">{#warenkorbpersSettings#}</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="massaction" class="tab-pane fade {if !isset($tab) || $tab === 'massaction' || $tab === 'uebersicht'} active in{/if}">
                <form name="suche" method="post" action="warenkorbpers.php">
                    {$jtl_token}
                    <input type="hidden" name="Suche" value="1" />
                    <input type="hidden" name="tab" value="warenkorbpers" />
                    <input type="hidden" name="s1" value="{$oBlaetterNaviKunde->nAktuelleSeite}" />
                    {if isset($cSuche) && $cSuche|count_characters > 0}
                        <input type="hidden" name="cSuche" value="{$cSuche}" />
                    {/if}

                    <div class="block input-group container left p25">
                        <span class="input-group-addon">
                            <label for="cSuche">{#warenkorbpersClientName#}:</label>
                        </span>
                        <input class="form-control" id="cSuche" name="cSuche" type="text" value="{if isset($cSuche) && $cSuche|count_characters > 0}{$cSuche}{/if}" />
                        <span class="input-group-btn">
                            <button name="submitSuche" type="submit" value="{#warenkorbpersSearchBTN#}" class="btn btn-info"><i class="fa fa-search"></i> {#warenkorbpersSearchBTN#}</button>
                        </span>
                    </div>
                </form>

                {if isset($oKunde_arr) && $oKunde_arr|@count > 0}
                    {if isset($cSuche) && $cSuche|count_characters > 0}
                        {assign var=pAdditional value="&cSuche="|cat:$cSuche}
                    {else}
                        {assign var=pAdditional value=''}
                    {/if}
                    {include file='pagination.tpl' cSite=1 cUrl='warenkorbpers.php' oBlaetterNavi=$oBlaetterNaviKunde cParams=$pAdditional hash='#massaction'}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{#warenkorbpers#}</h3>
                        </div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="tleft">{#warenkorbpersCompany#}</th>
                                <th class="tleft">{#warenkorbpersClientName#}</th>
                                <th class="th-3">{#warenkorbpersCount#}</th>
                                <th class="th-4">{#warenkorbpersDate#}</th>
                                <th class="th-5">{#warenkorbpersAction#}</th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach name=warenkorbkunden from=$oKunde_arr item=oKunde}
                                <tr class="tab_bg{$smarty.foreach.warenkorbkunden.iteration%2}">
                                    <td class="TD1">{$oKunde->cFirma}</td>
                                    <td class="TD2">{$oKunde->cVorname} {$oKunde->cNachname}</td>
                                    <td class="tcenter">{$oKunde->nAnzahl}</td>
                                    <td class="tcenter">{$oKunde->Datum}</td>
                                    <td class="tcenter">
                                        <div class="btn-group">
                                            <a href="warenkorbpers.php?a={$oKunde->kKunde}{if $oBlaetterNaviKunde->nAktiv == 1}&s1={$oBlaetterNaviKunde->nAktuelleSeite}{/if}&token={$smarty.session.jtl_token}" class="btn btn-default">{#warenkorbpersShow#}</a>
                                            <a href="warenkorbpers.php?l={$oKunde->kKunde}{if $oBlaetterNaviKunde->nAktiv == 1}&s1={$oBlaetterNaviKunde->nAktuelleSeite}{/if}&token={$smarty.session.jtl_token}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                {else}
                    <div class="alert alert-info" role="alert">{#noDataAvailable#}</div>
                {/if}
            </div>
            <div id="settings" class="tab-pane fade {if isset($tab) && $tab === 'einstellungen'} active in{/if}">
                {include file='tpl_inc/config_section.tpl' a='speichern' config=$oConfig_arr name='einstellen' action='warenkorbpers.php' buttonCaption=#save# title='Einstellungen' tab='einstellungen'}
            </div>
        </div>
    {elseif $step === 'anzeigen'}
        {assign var=pAdditional value="&a="|cat:$kKunde}
        {include file='pagination.tpl' cSite=2 cUrl='warenkorbpers.php' oBlaetterNavi=$oBlaetterNavi cParams=$pAdditional hash=''}
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{#warenkorbpersClient#} {$oWarenkorbPersPos_arr[0]->cVorname} {$oWarenkorbPersPos_arr[0]->cNachname}</h3>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th class="tleft">{#warenkorbpersProduct#}</th>
                    <th class="th-2">{#warenkorbpersCount#}</th>
                    <th class="th-3">{#warenkorbpersDate#}</th>
                </tr>
                </thead>
                <tbody>
                {foreach name=warenkorbpers from=$oWarenkorbPersPos_arr item=oWarenkorbPersPos}
                    <tr class="tab_bg{$smarty.foreach.warenkorbpers.iteration%2}">
                        <td class="tleft">
                            <a href="{$shopURL}/index.php?a={$oWarenkorbPersPos->kArtikel}" target="_blank">{$oWarenkorbPersPos->cArtikelName}</a>
                        </td>
                        <td class="tcenter">{$oWarenkorbPersPos->fAnzahl}</td>
                        <td class="tcenter">{$oWarenkorbPersPos->Datum}</td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    {/if}
</div>
{include file='tpl_inc/footer.tpl'}