<?php
$mode = get_mode('mode');
$action = get_request_action();
$id = get_request_id();

if ($action == 'remove')
{
    if (ObjectTaxonomy::Remove($id))
    {
        add_alert('Taxonomy item removed', 'success');
    }
    else
    {
        add_alert('Failed to remove taxonomy item', 'error');
    }
}
else if ($action == 'insert')
{
    $obj = false;
    $args = array(
        'name' => get_request_gp('name', '', true)
    );
    if ($mode === 'pagegroups')
    {
        $args['type'] = get_request_gp('type', array());
        $obj = new PageGroup($args);
    }
    else if ($mode === 'pagetypes')
    {
        $args['taxnomy'] = get_request_gp('taxonomy', array());
        $obj = new PageType($args);
    }
    else if ($mode === 'pagetaxonomies')
    {
        $obj = new PageTaxonomy($args);
    }
    if ($obj && ObjectTaxonomy::Insert($obj))
    {
        add_alert('Taxonomy item inserted', 'success');
    }
    else
    {
        add_alert('Failed to insert taxonomy item', 'error');
    }
}
else if ($action == 'update')
{
    $obj = ObjectTaxonomy::Get($id);
    $obj->SetName(get_request_gp('name', '', true));
    if ($mode === 'pagegroups')
    {
        $obj->SetType(get_request_gp('type', array())); 
    }
    else if ($mode === 'pagetypes')
    {
        $obj->SetTaxonomy(get_request_gp('taxonomy', array()));
    }
    else if ($mode === 'pagetaxonomies')
    {
        
    }
    if (ObjectTaxonomy::Update($id, $obj))
    {
        add_alert('Taxonomy item updated', 'success');
    }
    else
    {
        add_alert('Failed to update taxonomy item', 'error');
    }
}
else if ($action == 'order')
{
    $from = get_request_gp('fromPosition');
    $to = get_request_gp('toPosition');
    $class = 'ObjectTaxonomy';
    if ($mode === 'pagegroups')
    {
        $class = 'PageGroup';
    }
    else if ($mode === 'pagetypes')
    {
        $class = 'PageType';
    }
    else if ($mode === 'pagetaxonomies')
    {
        $class = 'PageTaxonomy';
    }
    
    if ($from!==false && $to!==false)
    {
        $from = (int)$from ;
        $to = (int)$to;
        if ($class::ReOrder($from, $to))
        {
            add_alert('Taxonomy item reordered', 'success');
        }
        else
        {
            add_alert('Failed to reorder taxonomy item', 'error');
        }
    }
    else
    {
        add_alert('Failed to reorder taxonomy item', 'error');
    }
}



