<?php

$id = get_request_id();
if (UserCapability::Remove($id))
{
    register_alert('User capability removed', 'success');
}
else
{
    register_alert('Failed to remove user capability', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
