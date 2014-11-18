<?php

function get_actions_structure_objtaxonomy($actions)
{
    $defaults = array();
    if (in_api())
    {
        $defaults = array(
            'order' => array(
                'template' => 'structure_objtaxonomy_order.php',
                'name' => _T('Order')
            )
        );
    }
    else
    {
        $defaults = array(
            'list' => array(
                'template' => 'structure_objtaxonomy_list.php',
                'name' => _T('List')
            ),
            'new' => array(
                'template' => 'structure_objtaxonomy_edit.php',
                'name' => _T('New')
            ),
            'edit' => array(
                'template' => 'structure_objtaxonomy_edit.php',
                'name' => _T('Edit')
            ),
            'config' => array(
                'template' => 'structure_objtaxonomy_config.php',
                'name' => _T('Configuration')
            ),
            'save' => array(
                'template' => 'structure_objtaxonomy_save.php',
                'name' => _T('Save')
            ),
            'remove' => array(
                'template' => 'structure_objtaxonomy_remove.php',
                'name' => _T('Manager')
            ),
            'insert' => array(
                'template' => 'structure_objtaxonomy_insert.php',
                'name' => _T('Manager')
            ),
            'update' => array(
                'template' => 'structure_objtaxonomy_update.php',
                'name' => _T('Manager')
            )
        );
    }
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_structure_' . PageTaxonomy::$TAXONOMY_KEY, 'get_actions_structure_objtaxonomy', 5);
add_filter('get_actions_structure_' . PageType::$TAXONOMY_KEY, 'get_actions_structure_objtaxonomy', 5);
add_filter('get_actions_structure_' . PageGroup::$TAXONOMY_KEY, 'get_actions_structure_objtaxonomy', 5);
