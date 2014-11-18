<?php

function get_actions_configuration_domains($actions)
{
    $defaults = array(
        'list' => array(
            'template' => 'configuration_domains_list.php',
            'name' => _T('List')
        ),
        'edit' => array(
            'template' => 'configuration_domains_edit.php',
            'name' => _T('Edit')
        ),
        'update' => array(
            'template' => 'configuration_domains_update.php',
            'name' => _T('Update')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_configuration_domains', 'get_actions_configuration_domains', 5);
