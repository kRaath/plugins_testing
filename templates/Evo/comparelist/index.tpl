{include file='layout/header.tpl'}
<h1>{lang key="compare" section="global"}
    <div class="pull-right">
        <a href="index.php?vla=1&print=1" title="{lang key="comparePrintThisPage" section="comparelist"}"><i class="fa fa-print"></i></a>
    </div>
</h1>

{if !empty($cHinweis)}
    <p class="alert alert-success">{$cHinweis}</p>
{/if}

{include file="snippets/extension.tpl"}

{if $oVergleichsliste->oArtikel_arr|@count >1}
    <div class="comparelist table-responsive">
        <table class="table table-striped table-bordered table-condensed table">
            <tr>
                <td>&nbsp;</td>
                {foreach name=vergleich from=$oVergleichsliste->oArtikel_arr item=oArtikel}
                    <td style="vertical-align:bottom; width:{$Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_spaltengroesse}px;" class="text-center">
                        <div class="thumbnail">
                            <a href="{$oArtikel->cURL}">
                                {image src=$oArtikel->cVorschaubild alt=$oArtikel->cName class="image"}
                            </a>
                        </div>
                        <p>
                            <a href="{$oArtikel->cURL}">{$oArtikel->cName}</a>
                        </p>

                        <p>
                            <strong class="price text-nowrap">{$oArtikel->Preise->cVKLocalized[$NettoPreise]}</strong
                        {*
                        {if $oArtikel->cLocalizedVPE}
                        <br/><small><b>{lang key="basePrice" section="global"}:</b> {$oArtikel->cLocalizedVPE[$NettoPreise]}</small>
                        {/if}
                        <br /><span class="vat_info">{include file='snippets/shipping_tax_info.tpl' taxdata=$oArtikel->taxData}</span>
                        *}
                        </p>
                        <p>
                            <a href="{$oArtikel->cURLDEL}" class="remove"><span class="fa fa-trash-o"></span></a>
                        </p>
                    </td>
                {/foreach}
            </tr>
            {foreach name=priospalten from=$cPrioSpalten_arr item=cPrioSpalten}
                {if $cPrioSpalten !== 'Merkmale' && $cPrioSpalten !== 'Variationen'}
                    {if $smarty.foreach.priospalten.iteration % 2 == 0}
                        <tr class="first">
                            {else}
                        <tr class="last">
                    {/if}
                {/if}

                {if $cPrioSpalten === 'cArtNr' && $Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_artikelnummer != 0}
                    <!-- Artikelnummer-->
                    <td valign="top">
                        <b>{lang key="productNumber" section="comparelist"}</b>
                    </td>
                {/if}
                {if $cPrioSpalten === 'cHersteller' && $Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_hersteller != 0}
                    <!-- Hersteller -->
                    <td valign="top">
                        <b>{lang key="manufacturer" section="comparelist"}</b>
                    </td>
                {/if}
                {if $cPrioSpalten === 'cBeschreibung' && $Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_beschreibung != 0}
                    <!-- Beschreibung -->
                    <td valign="top">
                        <div class="custom_content">
                            <b>{lang key="description" section="comparelist"}</b>
                        </div>
                    </td>
                {/if}
                {if $cPrioSpalten === 'cKurzBeschreibung' && $Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_kurzbeschreibung != 0}
                    <!-- Kurzbeschreibung -->
                    <td valign="top">
                        <b>{lang key="shortDescription" section="comparelist"}</b>
                    </td>
                {/if}
                {if $cPrioSpalten === 'fArtikelgewicht' && $Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_artikelgewicht != 0}
                    <!-- Artikelgewicht -->
                    <td valign="top">
                        <b>{lang key="productWeight" section="comparelist"}</b>
                    </td>
                {/if}
                {if $cPrioSpalten === 'fGewicht' && $Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_versandgewicht != 0}
                    <!-- Versandgewicht -->
                    <td valign="top">
                        <b>{lang key="shippingWeight" section="comparelist"}</b>
                    </td>
                {/if}
                {if $cPrioSpalten !== 'Merkmale' && $cPrioSpalten !== 'Variationen'}
                    {foreach name=vergleich from=$oVergleichsliste->oArtikel_arr item=oArtikel}
                        {if $oArtikel->$cPrioSpalten !== ''}
                            <td valign="top" style="min-width: {$Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_spaltengroesse}px">
                                {if $cPrioSpalten === 'fArtikelgewicht' || $cPrioSpalten === 'fGewicht'}
                                    {$oArtikel->$cPrioSpalten} {lang key="weightUnit" section="comparelist"}
                                {else}
                                    {$oArtikel->$cPrioSpalten}
                                {/if}
                            </td>
                        {else}
                            <td>--</td>
                        {/if}
                    {/foreach}
                    </tr>
                {/if}

                {if $cPrioSpalten === 'Merkmale' && $Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_merkmale != 0}
                    <!-- Merkmale -->
                    {foreach name=merkmale from=$oMerkmale_arr item=oMerkmale}
                        {if $smarty.foreach.merkmale.iteration % 2 == 0}
                            <tr class="first">
                                {else}
                            <tr class="last">
                        {/if}
                        <td valign="top">
                            <b>{$oMerkmale->cName}</b>
                        </td>
                        {foreach name=vergleich from=$oVergleichsliste->oArtikel_arr item=oArtikel}
                            <td valign="top" style="min-width: {$Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_spaltengroesse}px">
                                {if count($oArtikel->oMerkmale_arr) > 0}
                                    {foreach name=merkmale from=$oArtikel->oMerkmale_arr item=oMerkmaleArtikel}
                                        {if $oMerkmale->cName == $oMerkmaleArtikel->cName}
                                            {foreach name=merkmalwerte from=$oMerkmaleArtikel->oMerkmalWert_arr item=oMerkmalWert}
                                                {$oMerkmalWert->cWert}{if !$smarty.foreach.merkmalwerte.last}, {/if}
                                            {/foreach}
                                        {/if}
                                    {/foreach}
                                {else}
                                    --
                                {/if}
                            </td>
                        {/foreach}
                        </tr>
                    {/foreach}
                {/if}

                {if $cPrioSpalten === 'Variationen' && $Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_variationen != 0}
                    <!-- Variationen -->
                    {foreach name=variationen from=$oVariationen_arr item=oVariationen}
                        {if $smarty.foreach.variationen.iteration % 2 == 0}
                            <tr class="first">
                                {else}
                            <tr class="last">
                        {/if}
                        <td valign="top">
                            <b>{$oVariationen->cName}</b>
                        </td>
                        {foreach name=vergleich from=$oVergleichsliste->oArtikel_arr item=oArtikel}
                            <td valign="top">
                                {if isset($oArtikel->oVariationenNurKind_arr) && $oArtikel->oVariationenNurKind_arr|@count > 0}
                                    {foreach name=variationen from=$oArtikel->oVariationenNurKind_arr item=oVariationenArtikel}
                                        {if $oVariationen->cName == $oVariationenArtikel->cName}

                                            {foreach name=variationswerte from=$oVariationenArtikel->Werte item=oVariationsWerte}

                                                {$oVariationsWerte->cName}
                                                {if $oArtikel->nVariationOhneFreifeldAnzahl == 1 && ($oArtikel->kVaterArtikel > 0 || $oArtikel->nIstVater==1)}
                                                    {assign var=kEigenschaftWert value=$oVariationsWerte->kEigenschaftWert}
                                                    ({$oArtikel->oVariationDetailPreisKind_arr[$kEigenschaftWert]->Preise->cVKLocalized[$NettoPreise]}{if !empty($oArtikel->oVariationDetailPreisKind_arr[$kEigenschaftWert]->Preise->PreisecPreisVPEWertInklAufpreis[$NettoPreise])}, {$oArtikel->oVariationDetailPreisKind_arr[$kEigenschaftWert]->Preise->PreisecPreisVPEWertInklAufpreis[$NettoPreise]}{/if})
                                                {/if}
                                            {/foreach}
                                        {/if}
                                    {/foreach}
                                {elseif $oArtikel->Variationen|@count > 0}
                                    {foreach name=variationen from=$oArtikel->Variationen item=oVariationenArtikel}
                                        {if $oVariationen->cName == $oVariationenArtikel->cName}
                                            {foreach name=variationswerte from=$oVariationenArtikel->Werte item=oVariationsWerte}
                                                {$oVariationsWerte->cName}
                                                {if $Einstellungen_Vergleichsliste.artikeldetails.artikel_variationspreisanzeige==1 && $oVariationsWerte->fAufpreisNetto!=0}
                                                    ({$oVariationsWerte->cAufpreisLocalized[$NettoPreise]}{if !empty($oVariationsWerte->cPreisVPEWertAufpreis[$NettoPreise])}, {$oVariationsWerte->cPreisVPEWertAufpreis[$NettoPreise]}{/if})
                                                {elseif $Einstellungen_Vergleichsliste.artikeldetails.artikel_variationspreisanzeige==2 && $oVariationsWerte->fAufpreisNetto!=0}
                                                    ({$oVariationsWerte->cPreisInklAufpreis[$NettoPreise]}{if !empty($oVariationsWerte->cPreisVPEWertInklAufpreis[$NettoPreise])}, {$oVariationsWerte->cPreisVPEWertInklAufpreis[$NettoPreise]}{/if})
                                                {/if}
                                                {if !$smarty.foreach.variationswerte.last},{/if}
                                            {/foreach}
                                        {/if}
                                    {/foreach}
                                {else}
                                    &nbsp;
                                {/if}
                            </td>
                        {/foreach}
                        </tr>
                    {/foreach}
                {/if}
            {/foreach}
            <tr>
                {* to do: wait for update @FM
                                <td valign="top">
                                    &nbsp;
                                </td>
                                {foreach name=vergleich from=$oVergleichsliste->oArtikel_arr item=oArtikel}
                                    <td class="text-center" style="min-width: {$Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_spaltengroesseattribut}px">
                                        <a href="{$oArtikel->cURLDEL}" class="btn btn-default"><span class="fa fa-trash-o"></span></a>
                                    </td>
                                {/foreach}
                            </tr>
                *}
                {if !empty($bWarenkorb)}
            <tr>
                <td style="min-width: {$Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_spaltengroesseattribut}px">
                    &nbsp;
                </td>
                {foreach name=vergleich from=$oVergleichsliste->oArtikel_arr item=oArtikel}
                    <td valign="top" class="text-center" style="min-width: {$Einstellungen_Vergleichsliste.vergleichsliste.vergleichsliste_spaltengroesse}px">
                        <!--
                  <form action="vergleichsliste.php" method="get">
                     <input type="hidden" name="vlph" value="1" />
                     <input type="hidden" name="a" value="{$oArtikel->kArtikel}" />
                     <input type="submit" value="{lang key="addToCart" section="global"}" />
                  </form>
               -->
                        <button class="btn btn-default submit" onclick="window.location.href = '{$oArtikel->cURL}'">{lang key="details" section="global"}</button>
                    </td>
                {/foreach}
            </tr>
            {/if}
        </table>
    </div>
{else}
    {lang key="compareListNoItems" sektion="global"}
{/if}

{if !empty($cFehler)}
    <br>
    <div class="alert alert-danger">
        {$cFehler}
    </div>
{/if}

{include file='layout/footer.tpl'}