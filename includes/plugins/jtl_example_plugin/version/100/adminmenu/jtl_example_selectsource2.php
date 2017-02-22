<?php
/**
 * dynamic data source for selectbox2 with articles
 *
 * return value of these functions has to be an array of objects
 * where every object should have the members cWert, cName and optional nSort
 *
 * @package     jtl_example_plugin
 * @author      Felix Moche <felix.moche@jtl-software.com
 * @copyright   2016 JTL-Software-GmbH
 */

return Shop::DB()->query("SELECT kArtikel AS cWert, cName FROM tartikel LIMIT 50", 2);
