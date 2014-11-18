<?php

function get_actions_extensions_addeds($actions)
{
    $defaults = array(
        'list' => array(
            'template' => 'extensions_addeds_list.php',
            'name' => _T('List')
        ),
        'active' => array(
            'template' => 'extensions_addeds_active.php',
            'name' => _T('Active')
        ),
        'unactive' => array(
            'template' => 'extensions_addeds_unactive.php',
            'name' => _T('Unactive')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_extensions_addeds', 'get_actions_extensions_addeds', 5);
