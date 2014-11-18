<?php

$id = get_request_id();
$type = get_request_p('type', 'frontend', true);
if (Theme::Active($type, $id))
{
    register_alert('Theme actived', 'success');
}
else
{
    register_alert('Failed to activate the theme', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
