<?php

$id = get_request_id();
if (User::Remove($id))
{
    register_alert('User removed', 'success');
}
else
{
    register_alert('Failed to remove user', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
