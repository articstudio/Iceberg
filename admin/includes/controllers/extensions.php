<?php


function get_admin_modes_extensions($args)
{
    $array = array(
        'list' => array(
            'template' => 'extensions_list.php',
            'name' => 'List'
        )
    );
    $array = array_merge(isset($args[0]) ? $args[0] : array(), $array);
    list($array) = action_event('get_admin_modes_extensions', $array);
    return array($array);
}
add_action('get_modes', 'get_admin_modes_extensions', 10, 1);
