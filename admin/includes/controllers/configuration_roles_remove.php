<?php

$id = get_request_id();
if (UserRole::Remove($id))
{
    register_alert('User role removed', 'success');
}
else
{
    register_alert('Failed to remove user role', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
