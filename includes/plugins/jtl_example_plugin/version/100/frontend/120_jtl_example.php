<?php
/**
 * HOOK_KATEGORIE_CLASS_LOADFROMDB
 *
 * This modifies a category's name, description and short description.
 * Again, this will be called only once per category if the cache is activated and valid.
 *
 * @package     jtl_example_plugin
 * @author      Felix Moche <felix.moche@jtl-software.com
 * @copyright   2015 JTL-Software-GmbH
 */

if ($oPlugin->oPluginEinstellungAssoc_arr['modify_categories'] === 'Y') {
    $category = null;
    //from Shop4 on the category object is provided by the hook
    if (isset($args_arr['oKategorie'])) {
        //the advantage of this is that the hook is only executed if the category was not cached.
        //once modified, the result is written to the cache and will not have to be recalculated again for this category
        $category = $args_arr['oKategorie'];
    } else {
        //on earlier versions we have to get it from the session cache
        global $kKategorie;
        if ($kKategorie !== null && isset($_SESSION['oKategorie_arr'][$kKategorie])) {
            $category = $_SESSION['oKategorie_arr'][$kKategorie];
        }
    }
    //either way - modify category if it isn't cached yet
    if ($category !== null && (!isset($args_arr['cached']) || $args_arr['cached'] === false)) {
        require_once $oPlugin->cFrontendPfad . 'inc/class.jtl_example.helper.php';
        $helper                  = jtlExampleHelper::getInstance($oPlugin);
        $category->cBeschreibung = $helper->modify($category->cBeschreibung);

        if (isset($args_arr['cacheTags'])) {
            //add the plugins's custom cache tag so the content will be invalidated when the user flushes the plugin cache
            $args_arr['cacheTags'][] = $oPlugin->pluginCacheGroup;
        } else {
            //if there are no cache tags supplied by a hook, the cache entry is only associated with the default cache tags and this plugin cannot invalidate it.
            //so if you change the modification text in the plugin's options, the results will not be visible until the category cache was flushed!
            //but here you should be fine - hook 120 provides such tags
        }
    }
}
