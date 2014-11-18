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
    return get_link(array(RoutingBackend::REQUEST_KEY_MODULE=>  RoutingBackend::$MODULE_DASHBOARD['module']));
}

function get_admin_breadcrumb()
{
    return RoutingBackend::GetBreadcrumb();
}

