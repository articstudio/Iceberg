<?php
$mode = get_mode('mode');
$action = get_request_action();
$id = get_request_id();

if ($action == 'remove')
{
    if (Config::UnsaveConfig($id))
    {
        add_alert('Configuration item removed', 'success');
    }
    else
    {
        add_alert('Failed to remove configuration item', 'error');
    }
}
else if ($action == 'insert')
{
    $name = get_request_gp('config_name', '', true);
    $value = get_request_gp('config_value', '', true);
    if (Config::SaveConfig($name, $value))
    {
        add_alert('Configuration item inserted', 'success');
    }
    else
    {
        add_alert('Failed to insert configuration item', 'error');
    }
}
else if ($action == 'update')
{
    $value = get_request_gp('config_value', '', true);
    if (Config::SaveConfig($id, $value))
    {
        add_alert('Configuration item updated', 'success');
    }
    else
    {
        add_alert('Failed to update configuration item', 'error');
    }
}





