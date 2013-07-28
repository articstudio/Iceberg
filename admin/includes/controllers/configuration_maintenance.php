<?php

$action = get_request_action();

if ($action == 'save')
{
    $start = get_request_gp('maintenance_start_date', '1/1/1970', true) . ' ' . get_request_gp('maintenance_start_hour', '0', true) . ':' . get_request_gp('maintenance_start_minute', '0', true) . ':' . get_request_gp('maintenance_start_second', '0', true);
    $stop = get_request_gp('maintenance_stop_date', '1/1/1970', true) . ' ' . get_request_gp('maintenance_stop_hour', '0', true) . ':' . get_request_gp('maintenance_stop_minute', '0', true) . ':' . get_request_gp('maintenance_stop_second', '0', true);
    $config = array(
        'active' => (bool)get_request_gp('maintenance_active', false),
        'permanent' => (bool)get_request_gp('maintenance_permanent', false),
        'start' => strtotime($start),
        'stop' => strtotime($stop),
        'allowed' => get_request_gp('maintenance_allowed', '')
    );
    if (Maintenance::Save($config))
    {
        add_alert('Maintenance configuration saved', 'success');
    }
    else
    {
        add_alert('Failed to save the maintenance configuration', 'error');
    }
}

