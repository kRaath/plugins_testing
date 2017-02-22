{if (isset($Boxen.Schnellkauf) && $Boxen.Schnellkauf->anzeigen === 'Y') || (isset($oBox->anzeigen) && $oBox->anzeigen)}
    <section class="panel panel-default box box-direct-purchase" id="sidebox{$oBox->kBox}">
        <div class="panel-heading">
            <h5 class="panel-title">{lang key="quickBuy" section="global"}</h5>
        </div>{* /panel-heading *}
        <div class="panel-body box-body">
            <form class="top10" action="warenkorb.php" method="post">
                {$jtl_token}
                <input type="hidden" name="schnellkauf" value="1">
                <div class="input-group">
                    <div class="form-group float-label-control">
                        <input type="text" placeholder="{lang key="productNoEAN" section="global"}" class="form-control" name="ean" id="quick-purchase">
                    </div>
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default" title="{lang key="intoBasket" section="global"}"><span class="fa fa-shopping-cart"></span></button>
                    </div>
                </div>
            </form>
        </div>
    </section>
{/if}