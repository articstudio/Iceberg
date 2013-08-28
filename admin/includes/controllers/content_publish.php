<?php

$action = get_request_action();
$group = get_request_group();
$id = get_request_id(); 

if ($action == 'remove')
{
    if (Page::Remove($id))
    {
        add_alert('Page removed', 'success');
    }
    else
    {
        add_alert('Failed to remove page', 'error');
    }
}
else if ($action == 'insert')
{
    $metas = array(
        PageMeta::META_TITLE => get_request_p('name', '', true),
        PageMeta::META_PERMALINK => get_request_p('permalink', '', true),
        PageMeta::META_TEXT => get_request_p('text', '', true),
        PageMeta::META_IMAGE => get_request_p('image', '', true),
        PageMeta::META_TEMPLATE => get_request_p('template', '', true)
    );
    $args = array(
        'taxonomy' => get_request_p('taxonomy', get_default_pagetaxnomy()),
        'type' => get_request_p('type', get_page_pagetype()),
        'group' => $group,
        'parent' => get_request_p('parent', null, true),
        'metas' => $metas
    );
    $args['parent'] = $args['parent']==='NULL' ? null : $args['parent'];
    $lang = null;
    $id = Page::Insert($args, $lang);
    if ($id)
    {
        add_alert('Page inserted', 'success');
        action_event('content_publish_insert', $group, $id, $lang);
    }
    else
    {
        add_alert('Failed to inesrt page', 'error');
    }
}
else if ($action == 'update' || $action == 'translate')
{
    $metas = array(
        PageMeta::META_TITLE => get_request_p('name', '', true),
        PageMeta::META_PERMALINK => get_request_p('permalink', '', true),
        PageMeta::META_TEXT => get_request_p('text', '', true),
        PageMeta::META_IMAGE => get_request_p('image', '', true),
        PageMeta::META_TEMPLATE => get_request_p('template', '', true)
    );
    $args = array(
        'taxonomy' => get_request_p('taxonomy', get_default_pagetaxnomy()),
        'type' => get_request_p('type', get_page_pagetype()),
        'group' => $group,
        'parent' => get_request_p('parent', null, true),
        'metas' => $metas
    );
    $args['parent'] = $args['parent']==='NULL' ? null : $args['parent'];
    if ($action == 'translate')
    {
        $tlang = get_request_gp('tlang');
        if ($tlang && Page::Translate($id, $tlang, $args))
        {
            add_alert('Page translated', 'success');
            action_event('content_publish_translate', $group, $id, $tlang);
        }
        else
        {
            add_alert('Failed to translate page', 'error');
        }
    }
    else
    {
        $lang = null;
        if (Page::Update($id, $args, $lang))
        {
            add_alert('Page updated', 'success');
            action_event('content_publish_edit', $group, $id, $lang);
        }
        else
        {
            add_alert('Failed to update page', 'error');
        }
    }
}
else if ($action == 'unactive')
{
    if (Page::Unactive($id))
    {
        add_alert('Page unactived', 'success');
    }
    else
    {
        add_alert('Failed to unactivate the page', 'error');
    }
}
else if ($action == 'active')
{
    if (Page::Active($id))
    {
        add_alert('Page actived', 'success');
    }
    else
    {
        add_alert('Failed to activate the page', 'error');
    }
}
else if ($action == 'order')
{
    $order = get_request_gp('order');
    
    function execReorder($arr, $parent=null)
    {
        foreach ($arr AS $k => $page) {
            $id = $page['id'];
            $done = Page::UpdateParent($id, $parent);
            if ($done)
            {
                $done = Page::UpdateOrder($id, $k);
                if ($done && isset($page['children']))
                {
                    $done = execReorder($page['children'], $id);
                }
            }
        }
        return $done;
    }
    
    $done = execReorder($order);
    if ($done)
    {
        add_alert('Page reordered', 'success');
    }
    else
    {
        add_alert('Failed to reorder page', 'error');
    }
    
    /*$parent = get_request_gp('parent');
    
    
    if ($from!==false && $to!==false)
    {
        $from = (int)$from ;
        $to = (int)$to;
        if (Page::ReOrder($from, $to))
        {
            add_alert('Page reordered', 'success');
        }
        else
        {
            add_alert('Failed to reorder page', 'error');
        }
    }
    else
    {
        add_alert('Failed to reorder page', 'error');
    }*/
}


if ($action == 'insert' || $action == 'update' || $action == 'translate')
{
    $fromLang = get_lang();
    if ($action == 'translate')
    {
        $fromLang = get_request_gp('tlang');
    }
    $duplicate = get_request_gp('duplicate', array());
    if ($id && $fromLang && is_array($duplicate) && !empty($duplicate))
    {
        foreach ($duplicate AS $toLang)
        {
            $lang = get_language_info($toLang);
            if (is_active_language($toLang) && Page::Duplicate($id, $fromLang, $toLang))
            {
                add_alert('Page duplicated to ' . $lang['name'], 'success');
            }
            else
            {
                add_alert('Failed to duplicate page to ' . $lang['name'], 'error');
            }
        }
    }
}


