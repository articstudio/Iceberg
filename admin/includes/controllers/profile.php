<?php

function get_modes_profile($modes)
{
    $defaults = array(
        'data' => array(
            'template' => 'profile_data.php',
            'name' => _T('Data')
        )
    );
    $modes = array_merge($modes, $defaults);
    return $modes;
}
add_filter('get_modes_profile', 'get_modes_profile', 5);
