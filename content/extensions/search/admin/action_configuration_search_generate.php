<?php

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

register_alert('Search metas generateds', 'success');
locate(get_admin_action_link(array('action'=>'panel')), 302);
