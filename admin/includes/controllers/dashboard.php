<?php

function get_modes_dashboard($modes)
{
    $defaults = array(
        'home' => array(
            'template' => 'dashboard_home.php',
            'name' => _T('Home')
        )
    );
    $modes = array_merge($modes, $defaults);
    return $modes;
}
add_filter('get_modes_dashboard', 'get_modes_dashboard', 5);
