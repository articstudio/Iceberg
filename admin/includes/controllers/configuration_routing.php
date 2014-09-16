<?php

$action = get_request_action();

if ($action == 'save')
{
    $args = array(
        'canonical' => get_request_gp('routing_canonical', -1),
        'type' => get_request_gp('routing_type', -1),
        'domains_by_language' => array(),
        'domains' => array()
    );
    $l2d = get_request_gp('language_domains', array());
    foreach ($l2d AS $l => $d)
    {
        $args['domains_by_language'][$l] = explode(',', $d);
        $args['domains'] = array_merge($args['domains'], $args['domains_by_language'][$l]);
    }
    if (Routing::Save($args))
    {
        add_alert('Routing settings saved', 'success');
    }
    else
    {
        add_alert('Failed to save the routing settings', 'error');
    }
}


