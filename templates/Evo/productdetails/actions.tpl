<div id="product-actions" class="btn-group btn-group-md product-actions" role="group">
    {assign var=kArtikel value=$Artikel->kArtikel}

    {if $Artikel->kArtikelVariKombi > 0}
        {assign var=kArtikel value=$Artikel->kArtikelVariKombi}
    {/if}
    {if $Einstellungen.global.global_wunschliste_anzeigen === 'Y'}
        <button name="Wunschliste" type="submit" class="btn btn-default btn-secondary wishlist" title="{lang key="addToWishlist" section="productDetails"}">
            <span class="fa fa-heart"></span>
            <span class="hidden-sm">{lang key="wishlist" section="global"}</span>
        </button>
    {/if}
    {if $Einstellungen.artikeldetails.artikeldetails_vergleichsliste_anzeigen === 'Y'}
        <button name="Vergleichsliste" type="submit" class="btn btn-default btn-secondary compare" tabindex="3" title="{lang key="addToCompare" section="productDetails"}">
            <span class="fa fa-tasks"></span>
            <span class="hidden-sm">{lang key="compare" section="global"}</span>
        </button>
    {/if}
    {if $Einstellungen.artikeldetails.artikeldetails_fragezumprodukt_anzeigen === 'P'}
        <button type="button" id="z{$kArtikel}" class="btn btn-default btn-secondary popup-dep question" title="{lang key="productQuestion" section="productDetails"}">
            <span class="fa fa-question-circle"></span>
            <span class="hidden-sm">{lang key="productQuestion" section="productDetails"}</span>
        </button>
    {/if}
    {if ($verfuegbarkeitsBenachrichtigung == 2 || $verfuegbarkeitsBenachrichtigung == 3) && $Artikel->cLagerBeachten === 'Y'}
        <button type="button" id="n{$kArtikel}" class="btn btn-default btn-secondary popup-dep notification" title="{lang key="requestNotification" section="global"}">
            <span class="fa fa-bell"></span>
            <span class="hidden-sm">{lang key="requestNotification" section="global"}</span>
        </button>
    {/if}
</div>
<div class="visible-xs clearfix">
    <hr>
</div>