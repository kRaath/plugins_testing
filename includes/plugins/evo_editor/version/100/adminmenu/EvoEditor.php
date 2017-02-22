<?php

/**
 * Class EvoEditor
 */
class EvoEditor
{
    /**
     * @var EvoEditor
     */
    private static $instance;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $themesPath;

    /**
     * @var string
     */
    private $parentThemesPath = null;

    /**
     * @var string
     */
    private $jsPath;

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $parentTemplate;

    /**
     * Constructor
     */
    public function __construct()
    {
        global $oPlugin;

        require_once realpath(__DIR__ . '/../../../') . '/vendor/autoload.php';
        require_once realpath(__DIR__ . '/.././../../../../') . '/config.JTL-Shop.ini.php';
        require_once realpath(__DIR__ . '/.././../../../../') . '/defines.php';
        require_once realpath(__DIR__ . '/.././../../../../../classes/class.JTL-Shop.Template.php');
        require_once realpath(__DIR__ . '/.././../../../../../classes/core/class.core.Shop.php');

        $template             = Template::getInstance();
        $this->template       = $template->getFrontendTemplate();
        $this->parentTemplate = $template->getParent();
        if ($this->parentTemplate !== null) {
            $this->parentThemesPath = PFAD_ROOT . PFAD_TEMPLATES . $this->parentTemplate . '/themes/';
        }

        $this->path       = __DIR__;
        $this->url        = Shop::getURL() . '/' . PFAD_PLUGIN . $oPlugin->cVerzeichnis . '/' . PFAD_PLUGIN_VERSION . $oPlugin->nVersion . '/' . PFAD_PLUGIN_ADMINMENU;
        $this->themesPath = PFAD_ROOT . PFAD_TEMPLATES . $this->template . '/themes/';
        $this->jsPath     = PFAD_ROOT . PFAD_TEMPLATES . $this->template . '/js/';
    }

    /**
     * Returns class singleton instance
     *
     * @return EvoEditor
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * Shows editor in admin backend
     */
    public function showEditor()
    {
        global $smarty;

        $smarty->assign('URL', $this->url)
               ->assign('themes', $this->getThemes())
               ->display($this->path . '/templates/editor.tpl');
    }

    /**
     * @param string|null $theme
     * @return array
     */
    public function getThemes($theme = null)
    {
        $themes = array();
        if (is_dir($this->themesPath) && $handle = opendir($this->themesPath)) {
            while (false !== ($file = readdir($handle))) {
                if (($file !== '.' && $file !== '..' && $file !== 'fonts' && $file !== 'base') && ($theme === null || (is_array($theme) && in_array($file, $theme)))) {
                    $themes[] = array('template' => $this->template, 'theme' => $file);
                }
            }
            closedir($handle);
        }
        if ($this->parentThemesPath !== null && is_dir($this->parentThemesPath) && $handle = opendir($this->parentThemesPath)) {
            while (false !== ($file = readdir($handle))) {
                if (($file !== '.' && $file !== '..' && $file !== 'fonts' && $file !== 'base') && ($theme === null || (is_array($theme) && in_array($file, $theme)))) {
                    $themes[] = array('template' => $this->parentTemplate, 'theme' => $file);
                }
            }
            closedir($handle);
        }
        asort($themes);

        return $themes;
    }

    /**
     * Returns JSON data for called action
     *
     * @param  string $action action
     * @return string - json action output
     */
    public function json($action)
    {
        try {
            $data = $this->call($action);
        } catch (\Exception $e) {
            $data = $this->msg('danger', $e->getMessage());
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * @param $action
     * @return array|mixed
     */
    public function call($action)
    {
        if (method_exists($this, $action)) {
            return call_user_func(array(&$this, $action));
        }
        return $this->msg('danger', 'Method not found');
    }

    /**
     * @return array
     */
    private function minify()
    {
        require_once 'Compiler.php';
        $out = $this->jsPath . 'evo.min.js';

        if (file_exists($out)) {
            unlink($out);
        }

        $data  = '';
        $files = glob($this->jsPath . '*.js');

        foreach ($files as $file) {
            $data .= file_get_contents($file);
        }

        try {
            $min = JTL\Evo\Minify_JS_ClosureCompiler::minify($data);
            if (file_put_contents($out, $min) === false) {
                return $this->msg('danger', 'Fehler beim Speichern der Datei');
            } else {
                return $this->msg('success', 'Javascript wurde kompiliert');
            }
        } catch (\Exception $e) {
            return $this->msg('danger', $e->getMessage());
        }
    }

    /**
     * Read themes current less files
     *
     * @param string|null $theme
     * @return array json output
     */
    private function changeTheme($theme = null)
    {
        if ($_REQUEST['theme'] === '') {
            return '';
        }
        $theme    = ($theme === null) ? $_REQUEST['theme'] : $theme;
        $template = (!empty($_REQUEST['template'])) ? $_REQUEST['template'] : null;
        if ($template !== null) {
            $this->themesPath = PFAD_ROOT . PFAD_TEMPLATES . $template . '/themes/';
        }
        if (is_writable($this->themesPath . $theme)) {
            $files   = array();
            $customs = array();

            if ($handle = opendir(realpath($this->themesPath . $theme . '/less'))) {
                while (false !== ($file = readdir($handle))) {
                    if (strpos($file, '.') > 0 && $file !== '_assigns.less' && $file !== 'theme.less.tmp.less' && strpos($file, '.less.original') === false) {
                        $files[]   = array('file' => $file, 'path' => $this->themesPath . $theme . '/less/' . $file);
                        $customs[] = array('file' => $file, 'path' => $this->themesPath . $theme . '/less/' . $file);
                    }
                }
                closedir($handle);
            }
            if ($handle = opendir(realpath($this->themesPath . 'base/less'))) {
                while (false !== ($file = readdir($handle))) {
                    if (strpos($file, '.') > 0 && $file !== '_assigns.less' && $file !== 'theme.less.tmp.less' && strpos($file, '.less.original') === false) {
                        $files[] = array('file' => $file, 'path' => $this->themesPath . 'base/less/' . $file);
                    }
                }
                closedir($handle);
            }
            sort($files);

            $return['fn']              = 'showFiles';
            $return['data']['files']   = $files;
            $return['data']['customs'] = $customs;

            return $return;
        }

        return $this->msg('danger', 'Dieser Theme-Ordner hat keine Schreibrechte!');
    }

    /**
     * Saves less file
     *
     * @return array json output
     */
    private function save()
    {
        if (isset($_REQUEST['data']['content']) && isset($_REQUEST['data']['file'])) {
            if (!file_exists($_REQUEST['data']['file'] . '.original')) {
                if (!copy($_REQUEST['data']['file'], $_REQUEST['data']['file'] . '.original')) {
                    return $this->msg('danger', 'Fehler beim Erstellen des Backups ' . $_REQUEST['data']['file'] . '.original');
                }
            }
            if (file_put_contents($_REQUEST['data']['file'], base64_decode($_REQUEST['data']['content'])) === false) {
                return $this->msg('danger', 'Fehler beim Speichern der Datei');
            }

            return $this->msg('success', 'Datei wurde gespeichert');
        } else {
            $source = (strpos($_REQUEST['data']['file'], PFAD_ROOT) !== false) ?
                $_REQUEST['data']['file'] :
                (realpath($this->path . '/../../../') . '/less/' . $_REQUEST['data']['file']);
            if (!copy($source, $this->themesPath . $_REQUEST['theme'] . '/less/' . $_REQUEST['data']['name'])
            ) {
                return $this->msg('danger', 'Fehler beim Kopieren der Datei (Schreibrechte Theme-Ordner)');
            }

            return $this->open();
        }
    }

    /**
     * Open less file
     *
     * @return array json output
     */
    private function open()
    {
        $filePath = (strpos($_REQUEST['data']['file'], PFAD_ROOT) === false) ?
            ($this->themesPath . $_REQUEST['theme'] . '/less/' . $_REQUEST['data']['file']) :
            $_REQUEST['data']['file'];
        $return['fn']              = 'openFile';
        $return['data']['file']    = $_REQUEST['data']['file'];
        $return['data']['name']    = $_REQUEST['data']['name'];
        $return['data']['content'] = base64_encode(file_get_contents($filePath));

        return $return;
    }

    /**
     * Removes less file
     *
     * @return array json output
     */
    private function reset()
    {
        if (empty($_REQUEST['data']['file'])) {
            return $this->msg('danger', 'Datei nicht gefunden.');
        }
        $file     = $_REQUEST['data']['file'];
        $original = $_REQUEST['data']['file'] . '.original';
        $return   = array();
        if (!file_exists($original)) {
            return $this->msg('danger', 'Original nicht gefunden oder Datei noch nicht bearbeitet.');
        }
        if (unlink($_REQUEST['data']['file']) === true) {
            copy($original, $file);
            $return['fn']              = 'enableFile';
            $return['data']['name']    = $_REQUEST['data']['name'];
            $return['data']['file']    = $_REQUEST['data']['file'];
            $return['data']['content'] = base64_encode(file_get_contents($original));
        } else {
            $return = $this->msg('danger', 'Fehler beim Löschen der Datei');
        }

        return $return;
    }

    /**
     * Compile theme
     *
     * @param string|null $theme
     * @param string|null $template
     * @return array json output
     */
    public function compile($theme = null, $template = null)
    {
        $cacheDir  = PFAD_ROOT . PFAD_COMPILEDIR . 'less';
        $options   = array();
        $theme     = ($theme === null) ? $_REQUEST['theme'] : $theme;
        $template  = ($template !== null) ? $template : ((isset($_REQUEST['template'])) ? $_REQUEST['template'] : null);
        $directory = ($template === null) ?
            $this->themesPath . $theme :
            PFAD_ROOT . PFAD_TEMPLATES . $template . '/themes/' . $theme;
        if (file_exists($directory . '/less/theme.less')) {
            try {
                if (defined('EVO_COMPILE_CACHE') && EVO_COMPILE_CACHE === true) {
                    if (!file_exists($cacheDir)) {
                        mkdir($cacheDir, 0777);
                    }
                    else { //truncate cachedir
                        array_map('unlink', glob($cacheDir.'/lessphp*'));
                    }
                    $options = array('cache_dir' => $cacheDir);
                }
                $parser  = new Less_Parser($options);
                $parser->parseFile($directory . '/less/theme.less', '/');
                $css = $parser->getCss();
                file_put_contents($directory . '/bootstrap.css', $css);

                return $this->msg('success', 'Theme wurde erfolgreich nach ' . $directory . '/bootstrap.css kompiliert.');
            } catch (\Exception $e) {
                return $this->msg('danger', $e->getMessage());
            }
        }

        return $this->msg('danger', 'Theme-Ordner wurde nicht gefunden.');
    }

    /**
     * Generate a message callback
     *
     * @param  string $type message class (danger, success, info)
     * @param  string $msg message text
     * @return array json output
     */
    private function msg($type, $msg)
    {
        return array(
            'fn'   => 'message',
            'data' => array(
                'type' => $type,
                'msg'  => $msg
            )
        );
    }
}
