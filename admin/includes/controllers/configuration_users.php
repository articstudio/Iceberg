<?php

function get_actions_configuration_users($actions)
{
    $defaults = array(
        'list' => array(
            'template' => 'configuration_users_list.php',
            'name' => _T('List')
        ),
        'new' => array(
            'template' => 'configuration_users_edit.php',
            'name' => _T('New')
        ),
        'edit' => array(
            'template' => 'configuration_users_edit.php',
            'name' => _T('Edit')
        ),
        'active' => array(
            'template' => 'configuration_users_active.php',
            'name' => _T('Active')
        ),
        'unactive' => array(
            'template' => 'configuration_users_unactive.php',
            'name' => _T('Unactive')
        ),
        'remove' => array(
            'template' => 'configuration_users_remove.php',
            'name' => _T('Remove')
        ),
        'insert' => array(
            'template' => 'configuration_users_insert.php',
            'name' => _T('Insert')
        ),
        'update' => array(
            'template' => 'configuration_users_update.php',
            'name' => _T('Update')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_configuration_users', 'get_actions_configuration_users', 5);
