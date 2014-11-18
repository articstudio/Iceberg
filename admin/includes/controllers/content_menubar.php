<?php

function get_actions_content_menubar($actions)
{
    $defaults = array(
        'edit' => array(
            'template' => 'content_menubar_edit.php',
            'name' => _T('Edit')
        ),
        'save' => array(
            'template' => 'content_menubar_save.php',
            'name' => _T('Save')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_content_menubar', 'get_actions_content_menubar', 5);
