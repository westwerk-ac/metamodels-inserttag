<?php

    $GLOBALS['TL_DCA']['tl_metamodel_rendersettings']['metapalettes']['default']['expert'][]  = 'insertTag';


    $GLOBALS['TL_DCA']['tl_metamodel_rendersettings']['fields']['insertTag'] = array
    (
    'label'                   => &$GLOBALS['TL_LANG']['tl_metamodel_rendersettings']['insertTag'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     => "varchar(64) NOT NULL default ''",
    'eval'                    => array('unique'=>true),
    );
