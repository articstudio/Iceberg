<?php

$action = get_request_action();


if ($action === 'taxonomies-fields')
{
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
    ($done ? add_alert('Search taxonomies and fields saved correctly', 'success') : add_alert('Failed to save the search taxonomies and fields', 'error'));
}
else if ($action === 'generate-metas')
{
    $langs = get_active_locales();
    $config = get_search_config();
    foreach ($config['taxonomies'] AS $tax_id)
    {
        $fields = (isset($config['fields']) && isset($config['fields'][$tax_id])) ? $config['fields'][$tax_id] : array();
        foreach ($langs AS $lang)
        {
            $args = array(
                'taxonomy' => $tax_id
            );
            $pages = Page::GetList($args, $lang);
            foreach ($pages AS $page_id => $page)
            {
                search_generate_page_meta($page, $fields, $lang);
                
                //break;
            }
            //break;
        }
        //break;
    }
    
    add_alert('Search metas generateds', 'success');
}
