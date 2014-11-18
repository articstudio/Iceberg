<?php

function get_action_configuration_domains_edit($action, $key)
{
    $id = get_request_id();
    $domain = Domain::GetDomain($id);
    $default = array(
        'name' => _T('Edit') . ': ' . $domain->name
    );
    $action = array_merge($action, $default);
    return $action;
}
add_filter('get_action_configuration_domains_edit', 'get_action_configuration_domains_edit', 5, 2);
