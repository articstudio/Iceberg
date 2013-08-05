<?php

$action = get_request_action();
$group = get_request_group();
$id = get_request_id(); 

if ($action == 'remove')
{
    /*if (PageGroup::Remove($id))
    {
        add_alert('Page group removed', 'success');
    }
    else
    {
        add_alert('Failed to remove page group', 'error');
    }*/
}
else if ($action == 'insert')
{
    $parent = null;
    $taxonomy = null;
    $type = null;
    $metas = array(
        PageMeta::META_TITLE => get_request_p('name', '', true),
        PageMeta::META_PERMALINK => get_request_p('permalink', '', true),
        PageMeta::META_TEXT => get_request_p('text', '', true),
        PageMeta::META_IMAGE => get_request_p('image', '', true)
    );
    $lang = null;
    if (Page::Insert($group, $parent, $taxonomy, $type, $metas, $lang))
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
    /*$pagegroup = PageGroup::Get($id);
    $pagegroup->SetName(
        get_request_gp('name', '', true)
    );
    if (PageGroup::Update($id, $pagegroup))
    {
        add_alert('Page group updated', 'success');
    }
    else
    {
        add_alert('Failed to update page group', 'error');
    }*/
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





