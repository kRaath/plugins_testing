<?php
/**
 * HOOK_INDEX_NAVI_HEAD_POSTGET
 *
 * This hook is executed very early.
 * So we use it to set some arbitrary values to the registry
 *
 * @package     jtl_example_plugin
 * @author      Felix Moche <felix.moche@jtl-software.com
 * @copyright   2015 JTL-Software-GmbH
 */

if (class_exists('Shop')) {
    Shop::set('jtl_example_foo', array('foo' => 'bar'));
}

if (isset($_POST['jtl-example-post'])) {
    require_once $oPlugin->cFrontendPfad . 'inc/class.jtl_example.helper.php';

    $helper = jtlExampleHelper::getInstance($oPlugin);
    $res    = $helper->savePostToDB($_POST);
    global $smarty;
    if ($res === true) {
        $smarty->assign('jtlExmpleSuccess', 'Erfolgreich gespeichert.');
    } else {
        $smarty->assign('jtlExmpleError', 'Konnte Eingabe nicht speichern.');
    }
}
