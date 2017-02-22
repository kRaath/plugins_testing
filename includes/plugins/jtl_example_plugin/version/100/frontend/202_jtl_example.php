<?php
/**
 * HOOK_SMARTY_OUTPUTFILTER_CACHE
 *
 * Used to modify the DOM via phpQuery ONLY if the document is loaded from page cache
 *
 * @package     jtl_example_plugin
 * @author      Felix Moche <felix.moche@jtl-software.com
 * @copyright   2015 JTL-Software-GmbH
 */

//this will remove the div with id "logo" on the second page view when the page cache is activated
pq('#logo')->replaceWith('Hook 202 stole the logo!');
//the resulting DOM will not be saved to the cache, so this has to be executed on every page view
