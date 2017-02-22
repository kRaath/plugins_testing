<div id="ts_richsnippets"  class="page_width {if $Einstellungen.template.general.page_align == 'L'}page_left{else}page_center{/if} tcenter">
    <a href="http://www.trustedshops.eu/customer-review/" target="_blank">Trusted Shops customer reviews</a>:
    <span xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Review-aggregate">
        <span rel="v:rating">
            <span property="v:value">{$ts_features_richsnippet_result} </span>
         /
			<span property="v:best">{$ts_features_richsnippet_max} </span>
		</span> of
        <span property="v:votes">{$ts_features_richsnippet_count} </span>
        <a href="https://www.trustedshops.com/buyerrating/info_{$ts_features_richsnippet_tsid}.html" title="{$ts_features_richsnippet_shopName} custom reviews" target="_blank">
            {$ts_features_richsnippet_shopName} reviews
        </a>
    </span>
</div>