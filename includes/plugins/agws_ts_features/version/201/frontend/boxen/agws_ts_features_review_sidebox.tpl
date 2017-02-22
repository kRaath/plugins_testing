{if $ReviewStickerCode|@count > 0}
    <div class="sidebox" id="sidebox_ts_review">
        <h3 class="boxtitle">{lang key="trustedshopsRating" section="global"}</h3>
        <div class="sidebox_content tcenter">
            {$ReviewStickerCode}
        </div>
    </div>
{/if}