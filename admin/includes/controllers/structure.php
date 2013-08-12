<?php


function get_admin_modes_structure($args)
{
    $array = array(
        'pagetaxonomies' => array(
            'template' => 'structure_objtaxonomy.php',
            'name' => 'Taxonomies'
        ),
        'pagetypes' => array(
            'template' => 'structure_objtaxonomy.php',
            'name' => 'Types'
        ),
        'pagegroups' => array(
            'template' => 'structure_objtaxonomy.php',
            'name' => 'Groups'
        )
    );
    $array = array_merge(isset($args[0]) ? $args[0] : array(), $array);
    list($array) = action_event('get_admin_modes_structure', $array);
    return array($array);
}
add_action('get_modes', 'get_admin_modes_structure', 10, 1);


function get_admin_mode_structure($args)
{
    list($data, $key) = $args;
    $mode = get_request_mode();
    $action = get_request_action();
    if ($mode == 'pagegroups' || $mode == 'pagetypes' || $mode == 'pagetaxonomies')
    {
        if ($action == 'new' || $action == 'edit')
        {
            $data['template'] = 'structure_objtaxonomy_edit.php';
        }
        else if ($action == 'config')
        {
            $data['template'] = 'structure_objtaxonomy_config.php';
        }
    }
    return array($data, $key);
}
add_action('get_mode', 'get_admin_mode_structure', 10, 2);


function get_admin_breadcrumb_structure($args)
{
    $array = array();
    $mode = get_request_mode();
    $action = get_request_action();
    $id = get_request_id();
    if ($mode == 'pagegroups' || $mode == 'pagetypes' || $mode == 'pagetaxonomies')
    {
        if ($action == 'new')
        {
            $array[_T('New')] = get_admin_action_link(array(RoutingBackend::REQUEST_KEY_ACTION=>$action));
        }
        else if ($action == 'edit')
        {
            $obj = get_objtaxonomy($id);
            $array[_T('Edit') . ': ' . $obj->GetName()] = get_admin_action_link(array(RoutingBackend::REQUEST_KEY_ACTION=>$action, RoutingBackend::REQUEST_KEY_ID=>$id));
        }
        else if ($action == 'config')
        {
            $obj = get_objtaxonomy($id);
            $array[_T('Configuration') . ': ' . $obj->GetName()] = get_admin_action_link(array(RoutingBackend::REQUEST_KEY_ACTION=>$action, RoutingBackend::REQUEST_KEY_ID=>$id));
        }
    }
    $array = array_merge(isset($args[0]) ? $args[0] : array(), $array);
    list($array) = action_event('get_admin_breadcrumb_structure', $array);
    return array($array);
}
add_action('get_admin_breadcrumb', 'get_admin_breadcrumb_structure', 10, 1);
