<?php
/**
 * Created by PhpStorm.
 * User: Oli
 * Date: 05/08/14
 * Time: 11:53
 */

$GLOBALS['TL_HOOKS']['replaceInsertTags'][]      = array('Westwerk\InsertHelper', 'replaceTags');