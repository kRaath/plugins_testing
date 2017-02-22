<?php
/**
 * Created by PhpStorm.
 * User: ag-websolutions.de
 * Date: 20.03.2015
 * Time: 09:59
 *
 * File: agws_ts_features_predefine.php
 * Project: agws_trustedshops
 */

/** Defines für Shop-Backend */
define('TS_URL_BACKEND_LANGUAGE','de');
define('TS_URL_SHOPSW','JTL');
define('TS_URL_SHOPSW_VERSION',JTL_VERSION.".".JTL_MINOR_VERSION);
define('TS_URL_PLUGIN_VERSION',number_format($oPlugin->nVersion/100,2,".",""));
define('TS_GRAFIK_FILENAME','20150919-customer_reviews_grafik-de-450x300_1v0001.png');



/** Defines für Shop-Frontend - Review-Sticker */
define('TS_REVIEW_PQ_SELECTOR_FOOTER','#footer');
define('TS_REVIEW_PQ_METHOD_FOOTER','after');

/** Defines für Shop-Frontend - RichSnippets */
define('TS_RICHSNIPPET_PQ_SELECTOR','body');
define('TS_RICHSNIPPET_PQ_METHOD','append');
define('TS_RICHSNIPPET_API_URL','http://api.trustedshops.com/rest/public/v2/shops/TS_ID/quality.json');

/** Defines für Shop-Frontend - Checkout-DIV */
define('TS_CHECKOUT_PQ_SELECTOR_ORDERCOMPLETE','.order_completed');
define('TS_CHECKOUT_PQ_METHOD_ORDERCOMPLETE','append');
define('TS_CHECKOUT_PQ_SELECTOR_STATUSPAGE','table#customerorder');
define('TS_CHECKOUT_PQ_METHOD_STATUSPAGE','after');

/** Defines für Shop-Frontend - Rating-Widget */
define('TS_RATING_PQ_SELECTOR_FOOTER','#ftr_newsletter');
define('TS_RATING_PQ_METHOD_FOOTER','append');

define('TS_RATING_LINK_IMG_URL','//widgets.trustedshops.com/reviews/widgets/TS_ID.gif');

define('TS_RATING_LINK_TEXT_DE','Kundenmeinungen ansehen');
define('TS_RATING_LINK_TEXT_EN','Show customer reviews');
define('TS_RATING_LINK_TEXT_ES','Ver opiniones de clientes');
define('TS_RATING_LINK_TEXT_FR','Voir évaluations clients');
define('TS_RATING_LINK_TEXT_PL','Przeczytaj opinie klientów');
define('TS_RATING_LINK_TEXT_NL','Toon klantenreviews');
define('TS_RATING_LINK_TEXT_IT','Mostra le recensioni');

define('TS_RATING_LINK_URL_DE','https://www.trustedshops.de/bewertung/info_TS_ID.html');
define('TS_RATING_LINK_URL_EN','https://www.trustedshops.co.uk/buyerrating/info_TS_ID.html');
define('TS_RATING_LINK_URL_ES','https://www.trustedshops.es/evaluacion/info_TS_ID.html');
define('TS_RATING_LINK_URL_FR','https://www.trustedshops.fr/evaluation/info_TS_ID.html');
define('TS_RATING_LINK_URL_PL','https://www.trustedshops.pl/opinia/info_TS_ID.html');
define('TS_RATING_LINK_URL_NL','https://www.trustedshops.nl/verkopersbeoordeling/info_TS_ID.html');
define('TS_RATING_LINK_URL_IT','https://www.trustedshops.it/valutazione-del-negozio/info_TS_ID.html');

/** Defines für Shop-Frontend - Artikeldetailseite-Bewertungen */
define('TS_REVIEW_ARTIKEL_PQ_SELECTOR_SEMTABS','#mytabset .semtabs');
define('TS_REVIEW_ARTIKEL_PQ_METHOD_SEMTABS','append');
