<?php
/**
 * HOOK_LETZTERINCLUDE_INC
 *
 * This hook is executed later then 99. Here we can read our value again.
 *
 * @package     jtl_example_plugin
 * @author      Felix Moche <felix.moche@jtl-software.com
 * @copyright   2015 JTL-Software-GmbH
 */

if (class_exists('Shop', false)) {
    //"jtl_example_foo" is set in hook 132
    if (Shop::has('jtl_example_foo')) {
        $fooBar = Shop::get('jtl_example_foo');
        //do something
        if ($oPlugin->oPluginEinstellungAssoc_arr['jtl_example_debug'] === 'Y') {
            Shop::dbg($fooBar, false, 'fooBar from registry:');
        }
    }
    //this is never set
    if (Shop::has('jtl_example_bar')) {
        die('Should not see me because "jtl_example_bar" key does not exist.');
    }
}
