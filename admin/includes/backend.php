<?php

/* CONTROLLERS ADMINS */
function exec_admin_controller($template)
{
    return IcebergBackend::ExecController($template);
}




/* API ADMIN */
function iceberg_api_generate_admin($args)
{
    if (is_api_admin())
    {
        if (is_admin())
        {
            exec_admin_controller(get_module('template'));
            exec_admin_controller(get_mode('template'));
            print get_json_alerts();
        }
        else
        {
            Request::Response(403);
        }
        exit();
    }
    return $args;
}
add_action('iceberg_api_generate', 'iceberg_api_generate_admin', 0);
