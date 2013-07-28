<?php

function exec_api_controller($template)
{
    return IcebergAPI::ExecController($template);
}

function get_api_environment()
{
    return RoutingAPI::GetRequestEnvironment();
}

function is_api_admin()
{
    return RoutingAPI::InEnvironment(RoutingBackendAPI::REQUEST_ENVIRONMENT_ADMIN_API);
}