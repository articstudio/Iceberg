<?php

function get_actions_configuration_maintenance($actions)
{
    $defaults = array(
        'edit' => array(
            'template' => 'configuration_maintenance_edit.php',
            'name' => _T('Edit')
        ),
        'save' => array(
            'template' => 'configuration_maintenance_save.php',
            'name' => _T('Save')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_configuration_maintenance', 'get_actions_configuration_maintenance', 5);
