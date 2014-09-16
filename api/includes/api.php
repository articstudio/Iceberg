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
    return RoutingAPI::InEnvironment(API_ICEBERG_ENVIRONMENT);
}

function iceberg_api_generate_defaults($args)
{
    if (is_api_iceberg())
    {
        if (is_admin())
        {
            $module = get_request_module();
            if ($module === 'permalinks')
            {
                require ICEBERG_DIR_API_INCLUDES_DEFAULTS . 'permalinks.php';
            }
            else if ($module === 'popup')
            {
                //require_once DEFAULT_ADMIN_THEME_DIR . 'elfinder/popup.php';
            }
            else
            {
                //require_once DEFAULT_ADMIN_THEME_DIR . 'elfinder/connector.php';
            }
        }
        else
        {
            Request::Response(403);
        }
        exit();
    }
    return $args;
}
add_action('iceberg_api_generate', 'iceberg_api_generate_defaults', 0);
