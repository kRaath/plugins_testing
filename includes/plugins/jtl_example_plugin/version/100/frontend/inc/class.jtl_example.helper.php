<?php
/**
 * helper class for doing stuff
 *
 * @package     jtl_example_plugin
 * @author      Felix Moche <felix.moche@jtl-software.com
 * @copyright   2015 JTL-Software-GmbH
 */

/**
 * Class jtlExampleHelper
 */
class jtlExampleHelper
{
    /**
     * @var null|jtlExampleHelper
     */
    private static $_instance = null;

    /**
     * @var null|bool
     */
    private static $_isModern = null;

    /**
     * @var null|NiceDB
     */
    private $db = null;

    /**
     * @var null|Plugin
     */
    private $plugin = null;

    /**
     * constructor
     *
     * @param Plugin $oPlugin
     */
    public function __construct(Plugin $oPlugin)
    {
        $this->plugin = $oPlugin;
        //get database instance - do not do this, use Shop::DB()/$GLOBALS['DB'] instead
        if (self::isModern()) {
            $this->db = Shop::DB();
        } else {
            $this->db = $GLOBALS['DB'];
        }
    }

    /**
     * singleton getter
     *
     * @param Plugin $oPlugin
     * @return jtlExampleHelper
     */
    public static function getInstance(Plugin $oPlugin)
    {
        return (self::$_instance === null) ? new self($oPlugin) : self::$_instance;
    }

    /**
     * calculate PI and cache the result
     *
     * @param int $accuracy
     * @return float
     */
    public function calculatePi($accuracy = 10000000)
    {
        //a unique cache ID for just this cache entry
        $cacheID = 'jtl_xmpl_pi_' . $accuracy;
        $cached  = false;
        //check if cache is available and if the result is already cached
        if (!isset($this->plugin->pluginCacheGroup) || ($pi = Shop::Cache()->get($cacheID)) === false) {
            //not cached, so do the calculation
            $pi    = 4;
            $top   = 4;
            $bot   = 3;
            $minus = true;

            for ($i = 0; $i < $accuracy; $i++) {
                $pi += ($minus ? -($top / $bot) : ($top / $bot));
                $minus = ($minus ? false : true);
                $bot += 2;
            }
            //if the cache is available, store
            if (self::isModern()) {
                //save with the previously set cache ID and add two caching groups:
                //one which will be invalidated, when the plugin is uninstalled/upgraded and one to make custom invalidation easier
                Shop::Cache()->set($cacheID, $pi, array(CACHING_GROUP_PLUGIN, $this->plugin->pluginCacheGroup));
            }
        } else {
            //we have a cache-hit
            $cached = true;
        }
        if ($this->plugin->oPluginEinstellungAssoc_arr['jtl_example_debug'] === 'Y') {
            Shop::dbg($cached, false, 'Cached?');
        }

        return $pi;
    }

    /**
     * fetch, render and insert template into DOM
     *
     * @return $this
     */
    public function insertStuff()
    {
        if (self::isModern()) {
            $smarty = Shop::Smarty();
        } else {
            global $smarty;
        }
        //assign the calculated value of PI for smarty
        $dbRes    = $this->getSomethingFromDB();
        $someText = null;
        $file     = $this->plugin->cFrontendPfad . 'template/frontend_example.tpl';
        if (isset($dbRes->text)) {
            $someText = $dbRes->text;
        }
        $smarty->assign('some_text', $someText);
        $smarty->assign('lang_var_1', vsprintf($this->plugin->oPluginSprachvariableAssoc_arr['xmlp_lang_var_1'],
            array($this->calculatePi(), $this->plugin->nVersion)));
        //render template
        $html = $smarty->fetch($file);
        //get user options for inserting the template
        $function = $this->plugin->oPluginEinstellungAssoc_arr['jtl_example_pqfunction'];
        $selector = $this->plugin->oPluginEinstellungAssoc_arr['jtl_exmple_pqselector'];
        //call pq
        pq($selector)->$function($html);
        //make this function chainable
        return $this;
    }

    /**
     * insert JavaScript/CSS files on older shop versions < 400
     *
     * @return $this
     */
    public function fallBack()
    {
        //the possibility to add js/css was introduced in 400, for previous versions use pq
        if (self::isModern() === false) {
            $scripts = '<script type="text/javascript" src="' . $this->plugin->cFrontendPfadURLSSL . 'js/foo.js"></script>' . "\n" .
                '<script type="text/javascript" src="' . $this->plugin->cFrontendPfadURLSSL . 'js/bar.js"></script>';
            $styles  = '<link media="screen" rel="stylesheet" href="' . $this->plugin->cFrontendPfadURLSSL . 'css/foo.css" type="text/css">' . "\n" .
                '<link media="screen" rel="stylesheet" href="' . $this->plugin->cFrontendPfadURLSSL . 'css/bar.css" type="text/css">';
            pq('head')->append($scripts . "\n" . $styles);
        }
        //make this function chainable
        return $this;
    }

    /**
     * check if there is a current shop version installed
     *
     * @return bool
     */
    public static function isModern()
    {
        if (self::$_isModern === null) {
            //cache the actual value as class variable
            self::$_isModern = version_compare(JTL_VERSION, 400, '>=') && class_exists('Shop');
        }

        return self::$_isModern;
    }

    /**
     * get a db row via NiceDB instance
     * don't use this variant on modern plugins
     *
     * @return int|object
     */
    public function getSomethingFromDB()
    {
        return $this->db->selectSingleRow('xplugin_jtl_example_foo', 'foo', 22);
    }

    /**
     * execute custom sql via two different methods depending on shop version
     * this is the right way (apart from the fact that you could Shop::DB()->select())
     *
     * @return stdClass
     */
    public function getSomeThingFromDB2()
    {
        return (self::isModern()) ?
            Shop::DB()->select('xplugin_jtl_example_foo', 'foo', 44) :
            $GLOBALS['DB']->selectSingleRow('xplugin_jtl_example_foo', 'foo', 44);
    }

    /**
     * insert a new row into our custom DB table
     *
     * @param int $random
     * @return int
     */
    public function insertSomeThingIntoDB($random)
    {
        $myObject       = new stdClass();
        $myObject->foo  = $random;
        $myObject->bar  = 2;
        $myObject->text = 'Hello World!';

        return (self::isModern()) ?
            Shop::DB()->insert('xplugin_jtl_example_foo', $myObject) :
            $GLOBALS['DB']->insertRow('xplugin_jtl_example_foo', $myObject);

    }

    /**
     * modify a string with configured text
     *
     * @param string $text
     * @return string
     */
    public function modify($text)
    {
        $modification = $this->plugin->oPluginEinstellungAssoc_arr['modification_text'];

        return $text . ((is_string($modification) && strlen($modification) > 0) ?
            (' ' . $modification) :
            '');
    }

    /**
     * @param array $post
     * @return bool
     */
    public function savePostToDB($post)
    {
        $validToken = (function_exists('validateToken')) ?
            validateToken() : //validate csrf token if possible
            true; //for older shop3 version we cannot check
        if (!$validToken || empty($post['jtl-text']) || !isset($post['jtl-number']) || !isset($post['jtl-number-two'])) {
            return false;
        }
        $data       = new stdClass();
        $data->foo  = (int)$post['jtl-number'];
        $data->bar  = (int)$post['jtl-number-two'];
        $data->text = (self::isModern()) ? $post['jtl-text'] : $GLOBALS['DB']->escape($post['jtl-text']);
        //always use NiceDB::insert() if possible, since this method uses prepared statements on shop 4
        //never use NiceDB::executeQuery() with unescaped POST/GET values!

        return Shop::DB()->insert('xplugin_jtl_example_foo', $data) > 0;
    }
}
