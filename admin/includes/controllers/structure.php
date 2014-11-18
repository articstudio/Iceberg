<?php

function get_modes_structure($modes)
{
    $defaults = array(
        PageTaxonomy::$TAXONOMY_KEY => array(
            'template' => 'structure_objtaxonomy.php',
            'name' => _T('Taxonomies')
        ),
        PageType::$TAXONOMY_KEY => array(
            'template' => 'structure_objtaxonomy.php',
            'name' => _T('Types')
        ),
        PageGroup::$TAXONOMY_KEY => array(
            'template' => 'structure_objtaxonomy.php',
            'name' => _T('Groups')
        )
    );
    $modes = array_merge($modes, $defaults);
    return $modes;
}
add_filter('get_modes_structure', 'get_modes_structure', 5);
