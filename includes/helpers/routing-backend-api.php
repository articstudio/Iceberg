<?php

function filter_array_by_level($args)
{
    list($arr) = $args;
    $level = get_user_level();
    foreach ($arr AS $key => $value) {
        if (isset($value['level'])) {
            if ($level<$value['level']) {
                unset($arr[$key]);
            }
        }
    }
    return array($arr);
}
add_action('get_modules', 'filter_array_by_level', 100, 1);
add_action('get_modes', 'filter_array_by_level', 100, 1);



function get_logout()
{
    return get_link(array(RoutingBackendAPI::REQUEST_KEY_LOGOUT=>time()));
}

function get_modules()
{
    return RoutingBackendAPI::GetModules();
}

function get_module($key=null)
{
    return RoutingBackendAPI::GetModule($key);
}

function get_request_module()
{
    return RoutingBackendAPI::GetRequestModule();
}

function get_modes()
{
    return RoutingBackendAPI::GetModes();
}

function get_mode($key=null)
{
    return RoutingBackendAPI::GetMode($key);
}

function get_request_mode()
{
    return RoutingBackendAPI::GetRequestMode();
}

function get_request_action()
{
    return RoutingBackendAPI::GetRequestAction();
}

function get_request_id()
{
    return RoutingBackendAPI::GetRequestID();
}

function get_request_group()
{
    return RoutingBackendAPI::GetRequestGroup();
}

function get_iceberg_api_link($params=array())
{
    $url = get_base_url_api() .  API_ICEBERG_ENVIRONMENT . DIRECTORY_SEPARATOR;
    return get_link($params, $url);
    return $url;
}










