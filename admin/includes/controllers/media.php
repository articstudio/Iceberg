<?php

function get_modes_media($modes)
{
    $defaults = array(
        'uploads' => array(
            'template' => 'media_uploads.php',
            'name' => _T('Uploads')
        )
    );
    $modes = array_merge($modes, $defaults);
    return $modes;
}
add_filter('get_modes_media', 'get_modes_media', 5);
