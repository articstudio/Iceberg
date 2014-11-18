<?php

function get_actions_profile_data($actions)
{
    $defaults = array(
        'edit' => array(
            'template' => 'profile_data_edit.php',
            'name' => _T('Edit')
        ),
        'update' => array(
            'template' => 'profile_data_update.php',
            'name' => _T('Update')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_profile_data', 'get_actions_profile_data', 5);
