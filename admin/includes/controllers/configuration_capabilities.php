<?php

function get_actions_configuration_capabilities($actions)
{
    $defaults = array(
        'list' => array(
            'template' => 'configuration_capabilities_list.php',
            'name' => _T('List')
        ),
        'new' => array(
            'template' => 'configuration_capabilities_edit.php',
            'name' => _T('New')
        ),
        'edit' => array(
            'template' => 'configuration_capabilities_edit.php',
            'name' => _T('Edit')
        ),
        'remove' => array(
            'template' => 'configuration_capabilities_remove.php',
            'name' => _T('Remove')
        ),
        'insert' => array(
            'template' => 'configuration_capabilities_insert.php',
            'name' => _T('Insert')
        ),
        'update' => array(
            'template' => 'configuration_capabilities_update.php',
            'name' => _T('Update')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_configuration_capabilities', 'get_actions_configuration_capabilities', 5);
