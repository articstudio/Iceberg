<?php

function get_install_url()
{
    return get_link_dir(ICEBERG_DIR_INSTALL);
}

function get_install_step($key=null)
{
    return Install::GetStep($key);
}

function get_install_steps()
{
    return Install::GetSteps();
}

function get_install_request_step()
{
    return Install::GetRequestStep();
}

function get_install_step_link($step=null)
{
    $step = is_null($step) ? get_install_request_step() : $step;
    $query = array(
        Install::REQUEST_KEY_STEP => $step
    );
    return get_base_url() . '?' . http_build_query($query);
}

function get_install_next_step_link()
{
    $step = (int)get_install_request_step() + 1;
    return get_install_step_link($step);
}

function get_install_reinstall_link()
{
    $query = array(
        Install::REQUEST_KEY_REINSTALL => IcebergSecurity::MakeNonce('iceberg_reinstall')
    );
    return get_base_url() . '?' . http_build_query($query);
}

function install_compatible_version()
{
    return phpVersionCompatible(ICEBERG_PHP_VERSION_REQUIRED);
}

function install_db_file_writable()
{
    return (is_file(ICEBERG_DB_FILE) && is_writable(ICEBERG_DB_FILE));
}

function install_uploads_dir_writable()
{
    return (is_dir(ICEBERG_DIR_UPLOADS) && is_writable(ICEBERG_DIR_UPLOADS));
}

function install_temp_dir_writable()
{
    return (is_dir(ICEBERG_DIR_TEMP) && is_writable(ICEBERG_DIR_TEMP));
}

function install_check_requeriments()
{
    return (install_compatible_version() && install_db_file_writable() && install_uploads_dir_writable() && install_temp_dir_writable());
}

function add_install_alert($text, $type='success')
{
    global $_INSTALL_ALERTS;
    $_INSTALL_ALERTS = is_array($_INSTALL_ALERTS) ? $_INSTALL_ALERTS : array();
    array_push($_INSTALL_ALERTS, array('text'=>$text, 'type'=>$type));
}

function get_install_alerts()
{
    global $_INSTALL_ALERTS;
    return is_array($_INSTALL_ALERTS) ? $_INSTALL_ALERTS : array();
}

function add_install_error($text)
{
    global $_INSTALL_ERRORS;
    $_INSTALL_ERRORS = is_int($_INSTALL_ERRORS) ? $_INSTALL_ERRORS+1 : 1;
    add_install_alert($text, 'error');
}

function count_install_errors()
{
    global $_INSTALL_ERRORS;
    return is_int($_INSTALL_ERRORS) ? $_INSTALL_ERRORS : 0;
}
