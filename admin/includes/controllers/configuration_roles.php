<?php

function get_actions_configuration_roles($actions)
{
    $defaults = array(
        'list' => array(
            'template' => 'configuration_roles_list.php',
            'name' => _T('List')
        ),
        'new' => array(
            'template' => 'configuration_roles_edit.php',
            'name' => _T('New')
        ),
        'edit' => array(
            'template' => 'configuration_roles_edit.php',
            'name' => _T('Edit')
        ),
        'default' => array(
            'template' => 'configuration_roles_default.php',
            'name' => _T('Default')
        ),
        'remove' => array(
            'template' => 'configuration_roles_remove.php',
            'name' => _T('Remove')
        ),
        'insert' => array(
            'template' => 'configuration_roles_insert.php',
            'name' => _T('Insert')
        ),
        'update' => array(
            'template' => 'configuration_roles_update.php',
            'name' => _T('Update')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_configuration_roles', 'get_actions_configuration_roles', 5);
