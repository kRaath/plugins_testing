<?php
/**
 * HOOK_LETZTERINCLUDE_CSS_JS
 *
 * remove compiled css file - we use the less source
 *
 * @package     tpl
 * @createdAt   24.09.15
 * @author      Felix Moche <felix.moche@jtl-software.com>
 */

$tplConfig = Shop::getConfig(array(CONF_TEMPLATE));

if (isset($tplConfig['template']['demo']['demo_mode']) && $tplConfig['template']['demo']['demo_mode'] === 'Y' && isset($args_arr['cCSS_arr']) && Shop::isAdmin()) {
    //in evo, the copmiled css file is always called "bootstrap.css" - remove it
    foreach($args_arr['cCSS_arr'] as $_idx => $_file) {
        if (strpos($_file, 'bootstrap.css') !== false) {
            unset($args_arr['cCSS_arr'][$_idx]);
            break;
        }
    }
    global $smarty;
    $smarty->assign('tpl_editor_active', true);
}
