<?php

function get_actions_configuration_settings($actions)
{
    $defaults = array(
        'edit' => array(
            'template' => 'configuration_settings_edit.php',
            'name' => _T('Edit')
        ),
        'save' => array(
            'template' => 'configuration_settings_save.php',
            'name' => _T('Save')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_configuration_settings', 'get_actions_configuration_settings', 5);
