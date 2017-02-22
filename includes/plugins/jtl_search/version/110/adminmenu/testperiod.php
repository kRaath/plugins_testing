<?php

require_once JTLSEARCH_PFAD_ADMINMENU_TESTPERIOD_CLASSES . 'class.JTLSearch_Form_Activate.php';
require_once JTLSEARCH_PFAD_ADMINMENU_TESTPERIOD_CLASSES . 'class.JTLSearch.Filterbox.php';

$bStartedTestperiod = false;
$stepPlugin         = 'testperiod';
$cHinweis           = '';
$cFehler            = '';

$oForm = new JTLSearch_Form_Activate($oDebugger, 'JTLSearch_testperiod_form', 'post');
$oForm->addElement('kPlugin', 'hidden', '', array('value' => $oPlugin->kPlugin));
$oForm->addElement('cPluginTab', 'hidden', '', array('value' => 'Einstellungen'));
$oForm->addElement('stepPlugin', 'hidden', '', array('value' => $stepPlugin));

$oForm->addElement('cCode', 'textarea', 'Lizenzschl�ssel: ', array('style' => 'width: 500px; height: 120px;'));
$oForm->addElement('btn_serverinfo', 'submit', '', array('value' => 'Suche aktivieren', 'class' => 'btn btn-primary button orange'));

$oForm->addRule('cCode', 'Es muss ein Lizenzschl�ssel angegeben werden.', 'required');
$oForm->addRule('cCode', 'Der Lizenzschl�ssel muss aus mindestens 3 Buchstaben bestehen.', 'minlength', 3);
$oForm->addRule('cCode', 'Kein g�ltiger Lizenzschl�ssel.', 'base64decodeable');

if (isset($_POST['kPlugin']) && isset($_POST['btn_serverinfo'])) {
    $oDebugger->doDebug(__FILE__ . ': Usereingaben validieren.');
    if ($oForm->isValid()) {
        $oDebugger->doDebug(__FILE__ . ': Usereingaben valide.');
        require_once JTLSEARCH_PFAD_CLASSES . 'class.Communication.php';
        require_once JTLSEARCH_PFAD_CLASSES . 'class.Security.php';

        $cData_arr = explode(':::', base64_decode($_POST['cCode']));

        // Security Objekt erstellen und Parameter zum senden der Daten setzen
        $oSecurity = new Security($cData_arr[0], $cData_arr[1]);
        $oSecurity->setParam_arr(array('getsearchserver'));

        $xData_arr['a']   = 'getsearchserver';
        $xData_arr['pid'] = $cData_arr[0];
        $xData_arr['p']   = $oSecurity->createKey();
        $cResult          = Communication::postData(JTLSEARCH_MANAGER_SERVER_URL, $xData_arr);
        $oDebugger->doDebug(__FILE__ . ': $cResult : ' . $cResult, JTLSEARCH_DEBUGGING_LEVEL_DEBUG);
        $oResult = json_decode($cResult);

        if (isset($oResult->_serverurl) && strlen($oResult->_serverurl) > 0 && isset($oResult->_code) && $oResult->_code == 1) {
            $bStartedTestperiod = true;
            try {
                Shop::DB()->query('TRUNCATE TABLE tjtlsearchserverdata', 10);
                $oObject         = new stdClass();
                $oObject->cKey   = 'cProjectId';
                $oObject->cValue = $cData_arr[0];
                Shop::DB()->insert('tjtlsearchserverdata', $oObject);
                $oObject         = new stdClass();
                $oObject->cKey   = 'cAuthHash';
                $oObject->cValue = $cData_arr[1];
                Shop::DB()->insert('tjtlsearchserverdata', $oObject);
                $oObject         = new stdClass();
                $oObject->cKey   = 'cServerUrl';
                $oObject->cValue = $oResult->_serverurl;
                Shop::DB()->insert('tjtlsearchserverdata', $oObject);
                unset($oObject);

                // Sprachauswahl
                Shop::DB()->query('TRUNCATE TABLE tjtlsearchexportlanguage', 10);
                Shop::DB()->query("INSERT INTO tjtlsearchexportlanguage (`cISO`) SELECT tsprache.cISO FROM tsprache GROUP BY tsprache.cISO", 3);

                // Boxenverwaltung aktualisieren
                Filterbox::Create();
            } catch (Exception $oEx) {
                $oDebugger->doDebug(__FILE__ . ': Es ist ein unerwarteter Fehler aufgetreten', JTLSEARCH_DEBUGGING_LEVEL_ERROR);
                $oDebugger->doDebug(__FILE__ . ': Exception $oEx : ' . $oEx, JTLSEARCH_DEBUGGING_LEVEL_DEBUG);
            }
        } else {
            if (strlen($cResult) > 0) {
                $oForm->setError('Entweder haben Sie keinen korrekten Lizenzschl�ssel eingetragen oder es gibt ein Problem mit Ihrem Lizenzschl�ssel.');
            } else {
                $oForm->setError('Unerwartetes Ergebnis vom Server. Es gibt ein Problem mit Ihrem Lizenzschl�ssel.');
            }
            $oDebugger->doDebug(__FILE__ . ': Es ist ein Fehler beim Export aufgetreten. Unerwartete Antwort vom Verwaltungsserver', JTLSEARCH_DEBUGGING_LEVEL_ERROR);
            $oDebugger->doDebug(__FILE__ . ': $oResult : ' . print_r($oResult, true), JTLSEARCH_DEBUGGING_LEVEL_DEBUG);
        }
    } else {
        $oDebugger->doDebug(__FILE__ . ': Usereingaben nicht valide.', JTLSEARCH_DEBUGGING_LEVEL_DEBUG);
    }
} else {
    $oDebugger->doDebug(__FILE__ . ': Form wurde nicht abgeschickt.', JTLSEARCH_DEBUGGING_LEVEL_DEBUG);
}

Shop::Smarty()->assign('URL_SHOP', Shop::getURL())
    ->assign('PFAD_ROOT', PFAD_ROOT)
    ->assign('URL_ADMINMENU', Shop::getURL() . '/' . PFAD_PLUGIN . $oPlugin->cVerzeichnis . '/' . PFAD_PLUGIN_VERSION . $oPlugin->nVersion . '/' . PFAD_PLUGIN_ADMINMENU)
    ->assign('cBaseCssURL', JTLSEARCH_URL_ADMINMENU_TESTPERIOD_TEMPLATES_CSS_BASE)
    ->assign('oForm', $oForm)
    ->assign('bStartedTestperiod', $bStartedTestperiod)
    ->display(JTLSEARCH_PFAD_ADMINMENU_TESTPERIOD_TEMPLATES . 'testperiod.tpl');
$oDebugger->doDebug(__FILE__ . ': Ende der export.php', JTLSEARCH_DEBUGGING_LEVEL_DEBUG);
