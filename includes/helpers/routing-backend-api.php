<?php

function filter_array_by_capability($arr)
{
    foreach ($arr AS $key => $value)
    {
        if (isset($value['capability']) && !User::HasCapability($value['capability']))
        {
            unset($arr[$key]);
        }
    }
    return $arr;
}
add_filter('get_modules_after', 'filter_array_by_capability', 100);
add_filter('get_modes_after', 'filter_array_by_capability', 100);
add_filter('get_actions_after', 'filter_array_by_capability', 100);



function get_logout()
{
    return RoutingBackendAPI::GetLogoutURL();
}

function get_modules($cache=true)
{
    return RoutingBackendAPI::GetModules($cache);
}

function get_module($key=null, $cache=true)
{
    return RoutingBackendAPI::GetModule($key, $cache);
}

function get_request_module()
{
    return RoutingBackendAPI::GetRequestModule();
}

function get_modes($cache=true)
{
    return RoutingBackendAPI::GetModes($cache);
}

function get_mode($key=null, $cache=true)
{
    return RoutingBackendAPI::GetMode($key, $cache);
}

function get_request_mode()
{
    return RoutingBackendAPI::GetRequestMode();
}

function get_actions($cache=true)
{
    return RoutingBackendAPI::GetActions($cache);
}

function get_action($key=null, $cache=true)
{
    return RoutingBackendAPI::GetAction($key, $cache);
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

function sanitize_admin_action_params($params)
{
    return RoutingBackendAPI::SanitizeActionParams($params);
}

function get_admin_action_link($params=array())
{
    return RoutingBackendAPI::GetAdminActionURL($params);
}

function sanitize_api_action_params($params)
{
    return RoutingBackendAPI::SanitizeActionParams($params);
}

function get_api_link($params=array())
{
    return RoutingBackendAPI::GetAPIActionURL($params);
}

function get_iceberg_api_link()
{
    return RoutingBackendAPI::GetIcebergAPIURL();
}

function get_iceberg_api_action_link($params=array())
{
    return RoutingBackendAPI::GetIcebergAPIActionURL($params);
}

function get_admin_api_link($params=array())
{
    return RoutingBackendAPI::GetAdminAPIURL();
}

function get_admin_api_action_link($params=array())
{
    return RoutingBackendAPI::GetAdminAPIActionURL($params);
}
