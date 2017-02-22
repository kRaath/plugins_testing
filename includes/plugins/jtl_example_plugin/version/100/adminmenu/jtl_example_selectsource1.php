<?php
/**
 * dynamic data source for selectbox1
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

$option->cWert = 123;
$option->cName = 'Wert Eins';
$option->nSort = 1;
$options[]     = $option;

$option        = new stdClass();
$option->cWert = 456;
$option->cName = 'Wert Zwei';
$option->nSort = 2;
$options[]     = $option;

$option        = new stdClass();
$option->cWert = 789;
$option->cName = 'Wert Drei';
$option->nSort = 2;
$options[]     = $option;

return $options;
