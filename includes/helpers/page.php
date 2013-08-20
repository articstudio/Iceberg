<?php

function get_pages($args=array(), $lang=null)
{
    return Page::GetList($args, $lang);
}

function get_page($id, $lang=null)
{
    return Page::GetPage($id, $lang);
}

function order_pages_tree($arr)
{
    $output = array();
    $all = array();
    $dangling = array();
    foreach ($arr AS $id => $v) {
        $entry = array(
            'id' => $id,
            'parent' => $v->parent,
            'children' => array()
        );
        if ($entry['parent'] === null) {
            $all[$id] = $entry;
            $output[] =& $all[$id];
        } else {
            $dangling[$id] = $entry; 
        }
    }
    while (count($dangling) > 0) {
        foreach($dangling as $entry) {
            $id = $entry['id'];
            $pid = $entry['parent'];
            if (isset($all[$pid])) {
                $all[$id] = $entry;
                $all[$pid]['children'][] =& $all[$id]; 
                unset($dangling[$entry['id']]);
            }
        }
    }
    $result = array();
    foreach ($output AS $node)
    {
        $buffer = get_node_order_pages_tree($node);
        $result = array_merge($result, $buffer);
    }
    foreach ($result AS $k => $v)
    {
        $result[$k] = $arr[$v];
    }
    return $result;
}

function get_node_order_pages_tree($node) {
    $result = array();
    $result[$node['id']] = $node['id'];
    foreach ($node['children'] AS $child)
    {
        $buffer = get_node_order_pages_tree($child);
        $result = array_merge($result, $buffer);
    }
    return $result;
}


