<?php

$args = array(
    'name' => get_request_p('name', '', true),
    'capability' => get_request_p('capability', '')
);
$obj = new UserCapability($args);
if (UserCapability::Insert($obj))
{
    register_alert('User capability inserted', 'success');
}
else
{
    register_alert('Failed to insert capability role', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
