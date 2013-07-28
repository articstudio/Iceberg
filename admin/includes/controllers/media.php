<?php


function get_admin_modes_media($args)
{
    $array = array(
        'manager' => array(
            'template' => 'media_manager.php',
            'name' => 'Manager'
        )
    );
    $array = array_merge(isset($args[0]) ? $args[0] : array(), $array);
    list($array) = action_event('get_admin_modes_media', $array);
    return array($array);
}
add_action('get_modes', 'get_admin_modes_media', 10, 1);
