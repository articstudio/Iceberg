<?php

$action = get_request_action();
$id = get_request_id();

if ($action == 'active')
{
    $type = get_request_p('type', 'frontend', true);
    if (Theme::Active($type, $id))
    {
        add_alert('Theme actived', 'success');
    }
    else
    {
        add_alert('Failed to activate the theme', 'error');
    }
}

