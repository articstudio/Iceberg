<?php

$allconfig = (bool)get_request_gp('allconfig');
$name = get_request_gp('config_name', '', true);
$value = get_request_gp('config_value', '', true);
if ($allconfig ? ConfigAll::InsertConfig($name, $value) :  Config::InsertConfig($name, $value))
{
    register_alert('Configuration item inserted', 'success');
}
else
{
    register_alert('Failed to insert configuration item', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
