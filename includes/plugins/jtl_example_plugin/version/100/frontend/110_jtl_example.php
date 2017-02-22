<?php
/**
 * HOOK_ARTIKEL_CLASS_FUELLEARTIKEL
 *
 * This modifies a product's name, description and short description.
 * When the cache from JTL Shop4 is active, this will only be executed _once_ per product
 * as long as the cache is valid. On earlier versions or with disabled cache it will be called
 * every time a product is loaded.
 *
 * @package     jtl_example_plugin
 * @author      Felix Moche <felix.moche@jtl-software.com
 * @copyright   2015 JTL-Software-GmbH
 */

//only execute if the article object was provided by the hook, the corresponding plugin option is set to "Yes" and the product isn't cached yet
if (isset($args_arr['oArtikel']) && $oPlugin->oPluginEinstellungAssoc_arr['modify_products'] === 'Y' && (!isset($args_arr['cached']) || $args_arr['cached'] === false)) {
    require_once $oPlugin->cFrontendPfad . 'inc/class.jtl_example.helper.php';
    $helper                                  = jtlExampleHelper::getInstance($oPlugin);
    $args_arr['oArtikel']->cName             = $helper->modify($args_arr['oArtikel']->cName);
    $args_arr['oArtikel']->cBeschreibung     = $helper->modify($args_arr['oArtikel']->cBeschreibung);
    $args_arr['oArtikel']->cKurzBeschreibung = $helper->modify($args_arr['oArtikel']->cKurzBeschreibung);

    if (isset($args_arr['cacheTags'])) {
        //add the plugins's custom cache tag, so the content will be invalidated when the user flushes the plugin cache or uninstalls this plugin
        $args_arr['cacheTags'][] = $oPlugin->pluginCacheGroup;
    } else {
        //if there are no cache tags supplied by a hook, the cache entry is only associated with the default cache tags and this plugin cannot invalidate it.
        //so if you change the modification text in the plugin's options, the results will not be visible until the category cache was flushed!
        //but here you should be fine - hook 110 provides such tags
    }
}
