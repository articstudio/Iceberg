<?php
/*
$array = array (0 => array ( 'id' => '1', 'parent' => '0', ),
                1 => array ( 'id' => '2', 'parent' => '1', ),
                2 => array ( 'id' => '3', 'parent' => '0', ),
                3 => array ( 'id' => '5', 'parent' => '0', ),
                4 => array ( 'id' => '17', 'parent' => '3', ),
                5 => array ( 'id' => '31', 'parent' => '2', ),
                6 => array ( 'id' => '32', 'parent' => '2', ));
print_r($array);

// Building a tree. We also save a map of references to avoid searching the tree for nodes

//Helper to create nodes                                                                     
$tree_node = function($id, $parent) {
  return array('id' => $id, 'parent' => $parent, 'children' => array());
};

$tree = $tree_node(0, null); //root node                                                     
$map = array(0 => &$tree);
foreach($array as $cur) {
  $id = (int) $cur['id'];
  $parentId = (int) $cur['parent'];
  $map[$id] =& $map[$parentId]['children'][];
  $map[$id] = $tree_node($id, $parentId);
}
print_r($map);

//Now recursively flatten the tree:                                                          
function flatter($node) {
  //Create an array element of the node                                            
  $array_element = array('id' => (string) $node['id'],
                         'parent' => (string) $node['parent']);
  //Add all children after me                                                                
  $result = array($array_element);
  foreach($node['children'] as $child) {
    $result = array_merge($result, flatter($child));
  }
  return $result;
}

$array = flatter($tree);
array_shift($array); //Remove the root node, which was only added as a helper                

print_r($array);
*/
/*
error_reporting(-1);
ini_set('display_errors', 1);


$arr = array();

$obj = new stdClass();
$obj->id = 4;
$obj->parent = null;
$arr[$obj->id] = $obj;

$obj = new stdClass();
$obj->id = 8;
$obj->parent = 5;
$arr[$obj->id] = $obj;

$obj = new stdClass();
$obj->id = 3;
$obj->parent = 8;
$arr[$obj->id] = $obj;

$obj = new stdClass();
$obj->id = 5;
$obj->parent = null;
$arr[$obj->id] = $obj;

$obj = new stdClass();
$obj->id = 60;
$obj->parent = 4;
$arr[$obj->id] = $obj;

$obj = new stdClass();
$obj->id = 23;
$obj->parent = 4;
$arr[$obj->id] = $obj;

var_dump($arr);

function order_pages_tree($arr)
{
    $output = array();
    $all = array();
    $dangling = array();
    foreach ($arr AS $id => $v) {
        $entry = array(
            'id' => $id,
            'parent' => $v->parent,
            'obj' => $v,
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
    return $result;
}

function get_node_order_pages_tree($node) {
    $result = array();
    $result[$node['id']] = $node['obj'];
    foreach ($node['children'] AS $child)
    {
        $buffer = get_node_order_pages_tree($child);
        $result = array_merge($result, $buffer);
    }
    return $result;
}

$arr = order_pages_tree($arr);
var_dump($arr);

*/

phpinfo();
