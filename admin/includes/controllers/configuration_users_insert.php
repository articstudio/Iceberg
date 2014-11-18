<?php

$user = array(
    'email' => get_request_gp('email', '', true),
    'username' => get_request_gp('username', '', true),
    'password' => get_request_gp('password', '', true),
    'role' => get_request_gp('role', -1, true),
    'capabilities' => get_request_gp('capabilities', array()),
    'status' => get_request_gp('status', User::STATUS_ACTIVE, true)
);
if (User::Insert($user))
{
    register_alert('User inserted', 'success');
}
else
{
    register_alert('Failed to insert user', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
