<?php
/**
 * dynamic data source for radio option
 *
 * return value of these functions has to be an array of objects
 * where every object should have the members cWert, cName and optional nSort
 *
 * @package     jtl_example_plugin
 * @author      Felix Moche <felix.moche@jtl-software.com
 * @copyright   2016 JTL-Software-GmbH
 */

$options = array();
$option  = new stdClass();

$option->cWert = 321;
$option->cName = 'Radiowert Eins';
$option->nSort = 1;
$options[]     = $option;

$option        = new stdClass();
$option->cWert = 654;
$option->cName = 'Radiowert Zwei';
$option->nSort = 2;
$options[]     = $option;

$option        = new stdClass();
$option->cWert = 987;
$option->cName = 'Radiowert Drei';
$option->nSort = 2;
$options[]     = $option;

return $options;
