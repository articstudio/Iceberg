<?php

function get_actions_configuration_details($actions)
{
    $defaults = array(
        'list' => array(
            'template' => 'configuration_details_list.php',
            'name' => _T('List')
        ),
        'new' => array(
            'template' => 'configuration_details_edit.php',
            'name' => _T('New')
        ),
        'edit' => array(
            'template' => 'configuration_details_edit.php',
            'name' => _T('Edit')
        ),
        'remove' => array(
            'template' => 'configuration_details_remove.php',
            'name' => _T('Remove')
        ),
        'insert' => array(
            'template' => 'configuration_details_insert.php',
            'name' => _T('Insert')
        ),
        'update' => array(
            'template' => 'configuration_details_update.php',
            'name' => _T('Update')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_configuration_details', 'get_actions_configuration_details', 5);
