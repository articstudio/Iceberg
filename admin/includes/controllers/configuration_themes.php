<?php

function get_actions_configuration_themes($actions)
{
    $defaults = array(
        'list' => array(
            'template' => 'configuration_themes_list.php',
            'name' => _T('List')
        ),
        'active' => array(
            'template' => 'configuration_themes_active.php',
            'name' => _T('Active')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_configuration_themes', 'get_actions_configuration_themes', 5);
