{nocache}
{if isset($smarty.session.Vergleichsliste) && $smarty.session.Vergleichsliste->oArtikel_arr|@count > 0}
    {if isset($oBox->nAnzahl) && $oBox->nAnzahl > 0 && isset($oBox->Artikel)} {*3.50*}
        {assign var=from value=$oBox->Artikel}
        {assign var=nAnzahl value=$oBox->nAnzahl}
    {else}
        {assign var=from value=$smarty.session.Vergleichsliste->oArtikel_arr} {*3.50 compat mode*}
        {assign var=nAnzahl value=$smarty.session.Vergleichsliste->oArtikel_arr|@count}
    {/if}
    {if isset($from)}
        <section class="panel panel-default box box-compare" id="sidebox{$oBox->kBox}">
            <div class="panel-heading">
                <h5 class="panel-title"><i class="fa fa-tasks"></i> {lang key="compare" section="global"}</h5>
            </div>{* /panel-heading *}
            <table class="table table-striped vtable">
                {foreach name=vergleich from=$from item=oArtikel}
                    {if $smarty.foreach.vergleich.iteration <= $nAnzahl}
                        <tr class="item">
                            <td>
                                <a href="{$oArtikel->cURL}" class="image"><img src="{$oArtikel->Bilder[0]->cPfadMini}" alt="{$oArtikel->cName|strip_tags|escape:"quotes"|truncate:60}" class="img-xs" /></a><br>
                            </td>
                            <td>
                                <a href="{$oArtikel->cURL}" class="name">{$oArtikel->cName|truncate:25:"..."}</a>
                            </td>
                            <td class="text-right">
                                <a href="{$oArtikel->cURLDEL}" class="remove pull-right"><span class="fa fa-trash-o"></span></a>
                            </td>
                        </tr>
                    {/if}
                {/foreach}
            </table>
            <div class="panel-body">
                <a class="btn btn-default btn-sm btn-block{if $Einstellungen.vergleichsliste.vergleichsliste_target === 'popup'} popup{/if}" href="vergleichsliste.php"{if $Einstellungen.vergleichsliste.vergleichsliste_target === 'blank'} target="_blank"{/if}>{lang key="gotToCompare" section="global"}</a>
            </div>
        </section>
    {/if}
{/if}
{/nocache}
