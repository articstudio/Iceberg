<?php

function get_actions_media_uploads($actions)
{
    $defaults = array(
        'manager' => array(
            'template' => 'media_uploads_manager.php',
            'name' => _T('Manager')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_media_uploads', 'get_actions_media_uploads', 5);
