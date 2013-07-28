<?php

function in_admin_login()
{
    $env_controller = Environment::GetController();
    return ($env_controller == 'login');
}

function in_admin_dashboard()
{
    return get_module('module') == RoutingBackend::$MODULE_DASHBOARD['module'];
}

function get_admin_dashboard()
{
    return get_link(array(RoutingBackend::REQUEST_KEY_MODULE=>RoutingBackend::$MODULE_DASHBOARD['module']));
}

function get_admin_breadcrumb()
{
    return RoutingBackend::GetBreadcrumb();
}

function get_admin_reverse()
{
    $breadcrumb = get_admin_breadcrumb();
    $back = array_pop($breadcrumb);
    if (count($breadcrumb) > 1) {
        $back = array_pop($breadcrumb);
    }
    else {
        $back = false;
    }
    list($back) = action_event('get_admin_reverse', $back);
    return $back;
}

function get_admin_action_link($params=array())
{
    if (!isset($params[RoutingBackend::REQUEST_KEY_MODULE]))
    {
        $params[RoutingBackend::REQUEST_KEY_MODULE] = get_module('module');
    }
    if (!isset($params[RoutingBackend::REQUEST_KEY_MODE]))
    {
        $params[RoutingBackend::REQUEST_KEY_MODE] = get_mode('mode');
    }
    return get_link($params, get_base_url_admin());
}

function get_admin_api_action_link($params=array())
{
    if (!isset($params[RoutingBackend::REQUEST_KEY_MODULE]))
    {
        $params[RoutingBackend::REQUEST_KEY_MODULE] = get_module('module');
    }
    if (!isset($params[RoutingBackend::REQUEST_KEY_MODE]))
    {
        $params[RoutingBackend::REQUEST_KEY_MODE] = get_mode('mode');
    }
    return get_link($params, RoutingBackend::GetAdminAPIURL());
}
