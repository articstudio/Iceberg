<?php

$id = get_request_id();
$user = array(
    'email' => get_request_gp('email', '', true),
    'username' => get_request_gp('username', '', true),
    'role' => get_request_gp('role', -1, true),
    'capabilities' => get_request_gp('capabilities', array()),
    'status' => get_request_gp('status', User::STATUS_ACTIVE, true)
);
$password = get_request_gp('password', '', true);
if ($password !== '')
{
    $user['password'] = $password;
}
if (User::Update($id, $user))
{
    register_alert('User updated', 'success');
    do_action('configuration_users_update', $id);
}
else
{
    register_alert('Failed to update user', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
