<?php

$id = get_request_id();
$obj = UserRole::Get($id);
$obj->SetName(get_request_p('name', '', true));
$obj->SetCapabilities(get_request_p('capabilities', array())); 

if (UserRole::Update($id, $obj))
{
    register_alert('User role item updated', 'success');
}
else
{
    register_alert('Failed to update user role', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
