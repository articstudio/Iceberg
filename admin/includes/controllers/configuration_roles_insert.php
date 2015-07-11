<?php

$args = array(
    'name' => get_request_p('name', '', true),
    'capabilities' => get_request_p('capabilities', array())
);
$obj = new UserRole($args);
if (UserRole::Insert($obj))
{
    register_alert('User role inserted', 'success');
}
else
{
    register_alert('Failed to insert user role', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
