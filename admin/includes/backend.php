<?php

/* CONTROLLERS ADMINS */

//require_once ICEBERG_DIR_ADMIN_INCLUDES . 'controllers.php';

function exec_admin_controller($template)
{
    return IcebergBackend::ExecController($template);
}


/* API ADMIN */
function iceberg_api_generate_admin()
{
    if (is_api_admin())
    {
        if (is_admin())
        {
            exec_admin_controller(get_module('template', false));
            exec_admin_controller(get_mode('template', false));
            exec_admin_controller(get_action('template', false));
            print get_env_alerts_json();
        }
        else
        {
            Request::Response(403);
        }
        exit();
    }
}
add_action('iceberg_api_generate', 'iceberg_api_generate_admin', 5);
