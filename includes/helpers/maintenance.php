<?php

function in_maintenance()
{
    return Maintenance::InMaintenance();
}

function is_maintenance_allowed()
{
    return Maintenance::IsAllowed();
}

function maintenance() {
    action_event('maintenance_start');
    Config::SetConfig('maintenance_theme_file', 'maintenance.php');
    Config::SetConfig('maintenance_theme_url', Config::GetConfig('theme_url'));
    Config::SetConfig('maintenance_theme_path', Config::GetConfig('theme_path'));
    action_event('maintenance_postconfig');
    $file=Config::GetConfig('maintenance_theme_path') . Config::GetConfig('maintenance_theme_file');
    if (is_file($file)) { theme_load($file); }
    else {
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
        header('Retry-After: 3600');
        ?>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title><?php printf( _T('This site is under maintenance') ); ?></title>
            </head>
            <body>
                <h1><?php printf( _T('This site is under maintenance') ); ?></h1>
            </body>
        </html>
        <?php
    }
    action_event('maintenance_stop');
    die();
}
