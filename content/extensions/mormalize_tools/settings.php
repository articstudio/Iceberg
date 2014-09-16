<?php

define('NTOOLS_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR);
define('NTOOLS_DIR_ADMIN', NTOOLS_DIR . 'admin' . DIRECTORY_SEPARATOR);
define('NTOOLS_DIR_ADMIN_CONTROLLERS', NTOOLS_DIR_ADMIN . 'controllers' . DIRECTORY_SEPARATOR);


function get_admin_modes_configuration_ntools($args)
{
    list($array) = $args;
    $array['ntools'] = array(
        'template' => NTOOLS_DIR_ADMIN . 'ntools_admin.php',
        'name' => 'Normalize Tools',
        'level' => 500
    );
    $array['cleanusers'] = array(
        'template' => NTOOLS_DIR_ADMIN . 'cleanusers.php',
        'name' => 'Clean users',
        'level' => 500
    );
    return array($array);
}
add_action('get_admin_modes_configuration', 'get_admin_modes_configuration_ntools', 10, 1);


function iceberg_backend_generate_ntools($args)
{
    $template = get_mode('template');
    $template = (strpos($template, NTOOLS_DIR_ADMIN) !== false) ? str_replace(NTOOLS_DIR_ADMIN, NTOOLS_DIR_ADMIN_CONTROLLERS, $template) : '';
    if (is_file($template) && is_readable($template))
    {
        include $template;
    }
    return $args;
}
add_action('iceberg_backend_generate', 'iceberg_backend_generate_ntools', 10, 0);
