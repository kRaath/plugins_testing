<div id="ts_article_reviews">
    <style>
        {literal}
        ul.semtabs li.tsbewertungen.hidden {display:none !important;}
        #ts_article_reviews_wrapper ul.ts-reviews-list {margin-left:0px;max-height:500px;}
        #ts_article_reviews_wrapper ul.ts-reviews-list li {float:left !important;}
        {/literal}
    </style>
    <script type="text/javascript">
        function ts_article_review_init (ts_review_sku,ts_review_tsid,ts_review_tabintrotext,ts_review_showtab){ldelim}
            _tsProductReviewsConfig = {ldelim}
                tsid: ts_review_tsid,
                variant: 'productreviews',
                theme: 'light',
                apiServer: '//api-qa.trustedshops.com/',
                richSnippets: 'on',
                borderColor: '#fa9600',
                sku:  ts_review_sku,
                introtext: ts_review_tabintrotext
            {rdelim}

            console.log(_tsProductReviewsConfig);

            if (ts_review_showtab == 0) {ldelim}
                $( "ul.semtabs li.tsbewertungen" ).addClass( "hidden" );
            {rdelim} else {ldelim}
                $( "ul.semtabs li.tsbewertungen" ).removeClass( "hidden" );
            {rdelim}


            var me = document.getElementById('ts_article_reviews_wrapper');
            var _ts = document.createElement('SCRIPT');
            _ts.type = 'text/javascript';
            _ts.async = true;
            _ts.src = '//qa.trustedshops.com/trustbadge/reviews/tsSticker/tsProductSticker.js';
            me.insertBefore(_ts, me.firstChild);
            _tsProductReviewsConfig.script = _ts;
        {rdelim};

        $( document ).ready(function() {ldelim}
            ts_article_review_init ('{$Artikel->cArtNr}','{$agws_ts_features_TSID}','{$agws_ts_features_tabintrotext}','{$agws_ts_features_showtab}');
        {rdelim});
    </script>
 </div>