<?php

function get_actions_configuration_routing($actions)
{
    $defaults = array(
        'edit' => array(
            'template' => 'configuration_routing_edit.php',
            'name' => _T('Edit')
        ),
        'save' => array(
            'template' => 'configuration_routing_save.php',
            'name' => _T('Save')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_configuration_routing', 'get_actions_configuration_routing', 5);
