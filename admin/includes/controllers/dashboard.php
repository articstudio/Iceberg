<?php

function get_admin_modes_dashboard($args)
{
    $array = array(
        'information' => array(
            'template' => 'dashboard_information.php',
            'name' => 'Information'
        )
    );
    $array = array_merge(isset($args[0]) ? $args[0] : array(), $array);
    list($array) = action_event('get_admin_modes_dashboard', $array);
    return array($array);
}
add_action('get_modes', 'get_admin_modes_dashboard', 10, 1);

function get_admin_mode_dashboard($args)
{
    list($data, $key) = $args;
    list($data, $key) = action_event('get_admin_mode_dashboard', $data, $key);
    return array($data, $key);
}
add_action('get_mode', 'get_admin_mode_dashboard', 10, 2);


/*
function get_admin_dashboard_breadcrumb($args)
{
    $routing_module = get_module();
    $array = array(
        'Information' => get_link(array())
    );
    $array = array_merge($array, $args[0]);
    list($array) = action_event('get_admin_dashboard_breadcrumb', $array);
    return array($array);
}
add_action('get_admin_breadcrumb', 'get_admin_breadcrumb_dasboard', 10, 1);
*/