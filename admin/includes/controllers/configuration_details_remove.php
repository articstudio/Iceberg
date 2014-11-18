<?php

$id = get_request_id();
$allconfig = (bool)get_request_gp('allconfig');
if ($allconfig ? ConfigAll::RemoveConfig($id) : Config::RemoveConfig($id))
{
    register_alert('Configuration item removed', 'success');
}
else
{
    register_alert('Failed to remove configuration item', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
