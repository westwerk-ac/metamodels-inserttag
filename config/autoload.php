<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Ww-metamodels-inserttags
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Westwerk',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Westwerk\InsertHelper' => 'system/modules/ww-metamodels-inserttags/classes/InsertHelper.php',
));
