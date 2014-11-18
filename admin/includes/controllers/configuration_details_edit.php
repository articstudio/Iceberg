<?php

function get_action_configuration_details_edit($action, $key)
{
    $id = get_request_id();
    $config = ConfigAll::SelectConfigObjectByID($id);
    $default = array(
        'name' => _T('Edit') . ': ' . $config->name
    );
    $action = array_merge($action, $default);
    return $action;
}
add_filter('get_action_configuration_details_edit', 'get_action_configuration_details_edit', 5, 2);
