<?php

/**
 * Return if Iceberg is in Administrator environment
 * 
 * @global bool $__ICEBERG_ADMIN
 * @return bool 
 */
function in_admin()
{
    global $__ICEBERG_ADMIN;
    return is_bool($__ICEBERG_ADMIN) ? $__ICEBERG_ADMIN : false;
}

/**
 * Return if Iceberg is in API environment
 * 
 * @global bool $__ICEBERG_API
 * @return bool 
 */
function in_api()
{
    global $__ICEBERG_API;
    return is_bool($__ICEBERG_API) ? $__ICEBERG_API : false;
}

/**
 * Return if Iceberg is in WEB application
 * 
 * @uses in_api()
 * @uses in_admin()
 * @return bool 
 */
function in_web()
{
    return (!in_api() && !in_admin());
}



function add_alert($txt, $type='info')
{
    $env = Environment::GetEnvironment();
    return $env->AddAlert($txt, $type);
}

function get_alerts()
{
    $env = Environment::GetEnvironment();
    return $env->GetAlerts();
}

function get_json_alerts()
{
    $alerts = get_alerts();
    return json_encode($alerts);
}
