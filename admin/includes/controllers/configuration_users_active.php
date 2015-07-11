<?php

$id = get_request_id();
if (User::Active($id))
{
    register_alert('User actived', 'success');
}
else
{
    register_alert('Failed to activate the user', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
