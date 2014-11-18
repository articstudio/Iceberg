<?php

$id = get_request_id();
if (User::Unactive($id))
{
    register_alert('User unactived', 'success');
}
else
{
    register_alert('Failed to unactivate the user', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
