<?php

$action = get_request_action();
$id = get_request_id();

if ($action == 'active')
{
    if (I18N::Active($id))
    {
        add_alert('Language actived', 'success');
    }
    else
    {
        add_alert('Failed to activate the language', 'error');
    }
}
else if ($action == 'unactive')
{
    if (I18N::Unactive($id))
    {
        add_alert('Language unactived', 'success');
    }
    else
    {
        add_alert('Failed to unactivate the language', 'error');
    }
}
else if ($action == 'visible')
{
    if (I18N::Visible($id))
    {
        add_alert('Language is visible', 'success');
    }
    else
    {
        add_alert('Failed to set language to visible', 'error');
    }
}
else if ($action == 'invisible')
{
    if (I18N::Invisible($id))
    {
        add_alert('Language is invisible', 'success');
    }
    else
    {
        add_alert('Failed to set language to invisible', 'error');
    }
}
else if ($action == 'default')
{
    if (I18N::MakeDefault($id))
    {
        add_alert('Language is default', 'success');
    }
    else
    {
        add_alert('Failed to set language to default', 'error');
    }
}
else if ($action == 'remove')
{
    if (I18N::Remove($id))
    {
        add_alert('Language removed', 'success');
    }
    else
    {
        add_alert('Failed to remove language', 'error');
    }
}
else if ($action == 'insert')
{
    $lang = array(
        'name' => get_request_gp('name', '', true),
        'locale' => get_request_gp('locale', '', true),
        'iso' => get_request_gp('iso', '', true),
        'flag' => get_request_gp('flag', '', true)
    );
    if (I18N::Insert($lang))
    {
        add_alert('Language inserted', 'success');
    }
    else
    {
        add_alert('Failed to insert language', 'error');
    }
}
else if ($action == 'update')
{
    $lang = array(
        'name' => get_request_gp('name', '', true),
        'locale' => get_request_gp('locale', '', true),
        'iso' => get_request_gp('iso', '', true),
        'flag' => get_request_gp('flag', '', true)
    );
    if (I18N::Update($id, $lang))
    {
        add_alert('Language updated', 'success');
    }
    else
    {
        add_alert('Failed to update language', 'error');
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
        if (I18N::ReOrder($from, $to))
        {
            add_alert('Items reordered', 'success');
        }
        else
        {
            add_alert('Failed to reorder items', 'error');
        }
    }
    else
    {
        add_alert('Failed to reorder items', 'error');
    }
}





