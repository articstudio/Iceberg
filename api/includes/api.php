<?php

function exec_api_controller($template)
{
    return IcebergAPI::ExecController($template);
}

function get_api_environment()
{
    return RoutingAPI::GetRequestEnvironment();
}

/* API ADMIN */
function is_api_admin()
{
    return RoutingAPI::InEnvironment(RoutingBackendAPI::REQUEST_ENVIRONMENT_ADMIN_API);
}

/* API ICEBERG */
function is_api_iceberg()
{
    return RoutingAPI::InEnvironment(RoutingBackendAPI::REQUEST_ENVIRONMENT_ICEBERG_API);
}

function iceberg_api_generate_defaults()
{
    if (is_api_iceberg())
    {
        $module = get_request_module();
        if (is_admin() && isset(RoutingAPI::$DEFAULT_APIS[$module]))
        {
            UserCapability::DisableApplyCapabilities();
            require ICEBERG_DIR_API_INCLUDES_DEFAULTS . RoutingAPI::$DEFAULT_APIS[$module];
        }
        else
        {
            Request::Response(403);
        }
        exit();
    }
}
add_action('iceberg_api_generate', 'iceberg_api_generate_defaults', 5);
