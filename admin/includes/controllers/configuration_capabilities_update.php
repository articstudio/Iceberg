<?php

$id = get_request_id();
$obj = UserCapability::Get($id);
$obj->SetName(get_request_p('name', '', true));
$obj->SetCapability(get_request_p('capability', array())); 

if (UserCapability::Update($id, $obj))
{
    register_alert('User capability item updated', 'success');
}
else
{
    register_alert('Failed to update user capability', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
