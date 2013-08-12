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
        PageMeta::META_IMAGE => get_request_p('image', '', true)
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
    if (Page::Insert($args, $lang))
    {
        add_alert('Page inserted', 'success');
    }
    else
    {
        add_alert('Failed to inesrt page', 'error');
    }
}
else if ($action == 'update')
{
    $metas = array(
        PageMeta::META_TITLE => get_request_p('name', '', true),
        PageMeta::META_PERMALINK => get_request_p('permalink', '', true),
        PageMeta::META_TEXT => get_request_p('text', '', true),
        PageMeta::META_IMAGE => get_request_p('image', '', true)
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
    if (Page::Update($id, $args, $lang))
    {
        add_alert('Page group updated', 'success');
    }
    else
    {
        add_alert('Failed to update page group', 'error');
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
    /*$from = get_request_gp('fromPosition');
    $to = get_request_gp('toPosition');
    
    
    if ($from!==false && $to!==false)
    {
        $from = (int)$from ;
        $to = (int)$to;
        if (PageGroup::ReOrder($from, $to))
        {
            add_alert('Page group reordered', 'success');
        }
        else
        {
            add_alert('Failed to reorder page group', 'error');
        }
    }
    else
    {
        add_alert('Failed to reorder page group', 'error');
    }*/
}





