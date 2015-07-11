<?php

$id = get_user_id();
$user = array(
    'email' => get_request_gp('email', '', true),
    'username' => get_request_gp('username', '', true)
);
$password = get_request_gp('password', '', true);
if ($password !== '')
{
    $user['password'] = $password;
}
if (User::Update($id, $user))
{
    register_alert('Profile updated', 'success');
}
else
{
    register_alert('Failed to update profile', 'error');
}

locate(get_admin_action_link(array('action'=>'edit')), 302);
