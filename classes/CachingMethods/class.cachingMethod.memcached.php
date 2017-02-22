<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

/**
 * Class cache_memcached
 * Implements the Memcached memory object caching system - notice the "d" at the end
 *
 * @warning Untested
 */
class cache_memcached extends JTLCacheHelper implements ICachingMethod
{
    /**
     * @var cache_memcached|null
     */
    public static $instance = null;

    /**
     * @var Memcached|null
     */
    private $_memcached = null;

    /**
     * @param array $options
     */
    public function __construct($options)
    {
        if ($this->isAvailable() && !empty($options['memcache_host']) && !empty($options['memcache_port'])) {
            $this->setMemcached($options['memcache_host'], $options['memcache_port']);
            $this->isInitialized = true;
            $this->journalID     = 'memcached_journal';
            $this->options       = $options;
        }
    }

    /**
     * @param array $options
     *
     * @return cache_memcached
     */
    public static function getInstance($options)
    {
        //check if class was initialized before
        return (self::$instance !== null) ? self::$instance : new self($options);
    }

    /**
     * @param string $host
     * @param int    $port
     *
     * @return $this
     */
    public function setMemcached($host, $port)
    {
        if ($this->_memcached !== null) {
            $this->_memcached->quit();
        }
        $m = new Memcached();
        $m->addServer($host, $port);
        $this->_memcached = $m;

        return $this;
    }

    /**
     * @param string   $cacheID
     * @param mixed    $content
     * @param int|null $expiration
     *
     * @return bool
     */
    public function store($cacheID, $content, $expiration = null)
    {
        return $this->_memcached->set($this->options['prefix'] . $cacheID, $content, ($expiration === null) ? $this->options['lifetime'] : $expiration);
    }

    /**
     * @param array    $keyValue
     * @param int|null $expiration
     *
     * @return array|bool
     */
    public function storeMulti($keyValue, $expiration = null)
    {
        return $this->_memcached->setMulti($this->prefixArray($keyValue), ($expiration === null) ? $this->options['lifetime'] : $expiration);
    }

    /**
     * @param string $cacheID
     *
     * @return bool|mixed
     */
    public function load($cacheID)
    {
        return $this->_memcached->get($this->options['prefix'] . $cacheID);
    }

    /**
     * @param array $cacheIDs
     *
     * @return bool|array
     */
    public function loadMulti($cacheIDs)
    {
        if (!is_array($cacheIDs)) {
            return false;
        }
        $prefixedKeys = array();
        foreach ($cacheIDs as $_cid) {
            $prefixedKeys[] = $this->options['prefix'] . $_cid;
        }
        $res = $this->dePrefixArray($this->_memcached->getMulti($prefixedKeys));

        //fill up result
        return array_merge(array_fill_keys($cacheIDs, false), $res);
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return class_exists('Memcached');
    }

    /**
     * @param string $cacheID
     *
     * @return bool
     */
    public function flush($cacheID)
    {
        return $this->_memcached->delete($this->options['prefix'] . $cacheID);
    }

    /**
     * @return bool
     */
    public function flushAll()
    {
        return $this->_memcached->flush();
    }

    /**
     * @param string $cacheID
     *
     * @return bool
     */
    public function keyExists($cacheID)
    {
        $res = $this->_memcached->get($this->options['prefix'] . $cacheID);

        return (($res !== false || $this->_memcached->getResultCode() === Memcached::RES_SUCCESS));
    }

    /**
     * @todo: get the right array index, not just the first one
     * @return array
     */
    public function getStats()
    {
        if (method_exists($this->_memcached, 'getStats')) {
            $stats = $this->_memcached->getStats();
            if (is_array($stats)) {
                foreach ($stats as $key => $_stat) {
                    return array(
                        'entries' => $_stat['curr_items'],
                        'hits'    => $_stat['get_hits'],
                        'misses'  => $_stat['get_misses'],
                        'inserts' => $_stat['cmd_set'],
                        'mem'     => $_stat['bytes']
                    );
                }
            }
        }

        return array();
    }
}
