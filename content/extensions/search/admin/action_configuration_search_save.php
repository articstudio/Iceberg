<?php

$config = get_search_config();
    
$taxs = get_request_p('taxonomy', array());
$config['taxonomies'] = $taxs;

$fields = array();
foreach ($taxs AS $tax_id)
{
    $fields[$tax_id] = get_request_p('taxonomy'.$tax_id.'-fields', array());
}
$config['fields'] = $fields;
$done = save_search_config($config);
($done ? register_alert('Search taxonomies and fields saved correctly', 'success') : register_alert('Failed to save the search taxonomies and fields', 'error'));
locate(get_admin_action_link(array('action'=>'panel')), 302);
