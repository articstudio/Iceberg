<?php

$action = get_request_action();
$id = get_request_id();

if ($action == 'remove')
{
    if (PageGroup::Remove($id))
    {
        add_alert('Page group removed', 'success');
    }
    else
    {
        add_alert('Failed to remove page group', 'error');
    }
}
else if ($action == 'insert')
{
    $pagegroup = new PageGroup(
        get_request_gp('name', '', true)
    );
    if (PageGroup::Insert($pagegroup))
    {
        add_alert('Page group inserted', 'success');
    }
    else
    {
        add_alert('Failed to inesrt page group', 'error');
    }
}
else if ($action == 'update')
{
    $pagegroup = PageGroup::Get($id);
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
    }
}
else if ($action == 'order')
{
    $from = get_request_gp('fromPosition');
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
    }
}





