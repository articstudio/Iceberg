<?php

$action = get_request_action();
$id = get_request_id();

if ($action == 'update')
{
    $done = false;
    
    $canonical = get_domain();
    $canonical_childs = get_domains_by_parent($canonical->id);
    
    $canonical_name = get_request_gp('canonical_name', $canonical->name, true);
    $canonical_name .= (substr($canonical_name, -1) !== '/') ? '/' : '';
    
    $alias_names = get_request_gp('alias_name', array(), true);
    $alias_ids = get_request_gp('alias_id', array(), true);
    
    $args = array(
        'name' => $canonical_name
    );
    $done = Domain::DB_Update($id, $args);
    if ($done)
    {
        foreach ($alias_names AS $key => $alias)
        {
            $alias .= (substr($alias, -1) !== '/') ? '/' : '';
            $alias_id = isset($alias_ids[$key]) ? (int)$alias_ids[$key] : -1;
            if ($alias_id > 0 && isset($canonical_childs[$alias_id]))
            {
                $canonical_childs[$alias_id] = null;
                unset($canonical_childs[$alias_id]);
                $args = array(
                    'name' => $alias
                );
                Domain::DB_Update($alias_id, $args);
            }
            else
            {
                $args = array(
                    'name' => $alias
                );
                $relations = array(
                    Domain::RELATION_KEY_CANONICAL => $id
                );
                Domain::DB_Insert($args, $relations);
            }
        }
        foreach ($canonical_childs As $child_id => $child)
        {
            $where = array(
                'id' => $child_id
            );
            Domain::DB_Delete($where);
        }
    }
    
    if ($done)
    {
        add_alert('Domain updated', 'success');
    }
    else
    {
        add_alert('Failed to update the domain', 'error');
    }
}

