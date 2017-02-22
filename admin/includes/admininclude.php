<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

// Defines
if (!isset($bExtern) || !$bExtern) {
    define('DEFINES_PFAD', dirname(__FILE__) . '/../../includes/');
    require DEFINES_PFAD . 'config.JTL-Shop.ini.php';
    require DEFINES_PFAD . 'defines.php';
    require PFAD_ROOT . PFAD_ADMIN . PFAD_INCLUDES . 'admindefines.php';
    // Existiert Konfiguration?
    defined('DB_HOST') || die('Kein MySql-Datenbank Host angegeben. Bitte config.JTL-Shop.ini.php bearbeiten!');
    defined('DB_NAME') || die('Kein MySql Datenbanknamen angegeben. Bitte config.JTL-Shop.ini.php bearbeiten!');
    defined('DB_USER') || die('Kein MySql-Datenbank Benutzer angegeben. Bitte config.JTL-Shop.ini.php bearbeiten!');
    defined('DB_PASS') || die('Kein MySql-Datenbank Passwort angegeben. Bitte config.JTL-Shop.ini.php bearbeiten!');
}

require PFAD_ROOT . PFAD_INCLUDES . 'sprachfunktionen.php';
require PFAD_ROOT . PFAD_INCLUDES . 'error_handler.php';
require PFAD_ROOT . PFAD_INCLUDES . 'plugin_inc.php';
require PFAD_ROOT . PFAD_INCLUDES . 'autoload.php';
require PFAD_ROOT . PFAD_INCLUDES . 'tools.Global.php';
$shop = Shop::getInstance();
require PFAD_ROOT . PFAD_BLOWFISH . 'xtea.class.php';
require PFAD_ROOT . PFAD_CLASSES_CORE . 'class.core.NiceDB.php';
require PFAD_ROOT . PFAD_CLASSES_CORE . 'class.core.Nice.php';
require PFAD_ROOT . PFAD_ADMIN . PFAD_INCLUDES . 'benutzerverwaltung_inc.php';
require PFAD_ROOT . PFAD_ADMIN . PFAD_INCLUDES . 'admin_tools.php';
// Datenbankverbindung aufbauen - ohne Debug Modus
$DB    = new NiceDB(DB_HOST, DB_USER, DB_PASS, DB_NAME, true);
$cache = JTLCache::getInstance();
$cache->setJtlCacheConfig();

$oSprache            = Sprache::getInstance(true);
$nSystemlogFlag      = getSytemlogFlag();
$oGlobaleEinstellung = Shop::getSettings(array(CONF_GLOBAL));
$oAccount            = new AdminAccount();

require PFAD_ROOT . PFAD_ADMIN . PFAD_INCLUDES . 'smartyinclude.php';
