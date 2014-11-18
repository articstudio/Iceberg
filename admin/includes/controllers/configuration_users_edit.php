<?php

function get_action_configuration_users_edit($action, $key)
{
    $id = get_request_id();
    $user = User::GetUser($id);
    $default = array(
        'name' => _T('Edit') . ': ' . $user->username
    );
    $action = array_merge($action, $default);
    return $action;
}
add_filter('get_action_configuration_users_edit', 'get_action_configuration_users_edit', 5, 2);
