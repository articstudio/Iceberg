<?php

$id = get_request_id();
$allconfig = (bool)get_request_gp('allconfig');
$value = get_request_gp('config_value', '', true);
if ($allconfig ? ConfigAll::UpdateConfig($id, $value) :  Config::UpdateConfig($id, $value))
{
    register_alert('Configuration item updated', 'success');
}
else
{
    register_alert('Failed to update configuration item', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
