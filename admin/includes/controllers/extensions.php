<?php

function get_modes_extensions($modes)
{
    $defaults = array(
        'addeds' => array(
            'template' => 'extensions_addeds.php',
            'name' => _T('Addeds')
        )
    );
    $modes = array_merge($modes, $defaults);
    return $modes;
}
add_filter('get_modes_extensions', 'get_modes_extensions', 5);
