<?php
/**
 * HOOK_SMARTY_OUTPUTFILTER
 * Used to insert scripts and styles into the DOM for old shop versions
 * and debug a little example stuff on article pages
 *
 * @package     jtl_example_plugin
 * @author      Felix Moche <felix.moche@jtl-software.com
 * @copyright   2015 JTL-Software-GmbH
 */

require_once $oPlugin->cFrontendPfad . 'inc/class.jtl_example.helper.php';

$helper = jtlExampleHelper::getInstance($oPlugin);
$helper->fallBack()//add scripts/css for older shop versions
       ->insertStuff(); //example function for inserting stuff into the DOM

//Shop4 does not use the global $nSeitenTyp - check shop class
if (jtlExampleHelper::isModern()) {
    $nSeitenTyp = Shop::$pageType;
} else {
    global $nSeitenTyp;
}
if ($nSeitenTyp === PAGE_ARTIKEL) {
    //we have an article detail page
    $fooBar = $helper->getSomeThingFromDB2(); //just get some data from the database
    if ($oPlugin->oPluginEinstellungAssoc_arr['jtl_example_debug'] === 'Y') {
        foreach ($fooBar as $foo) {
            Shop::dbg($foo, false, 'Got foobar from DB:'); //quick&dirty debugging
        }
    }
    //do something
} elseif ($nSeitenTyp === PAGE_SITEMAP) {
    //we have a sitemap page - do something else - like randomly inserting stuff into the database
    $helper->insertSomeThingIntoDB(rand(0, 100));
}
