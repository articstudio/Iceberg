<?php


function get_admin_modes_structure($args)
{
    $array = array(
        'pagegroups' => array(
            'template' => 'structure_pagegroups.php',
            'name' => 'Page groups'
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
    if ($mode == 'pagegroups')
    {
        if ($action == 'new' || $action == 'edit')
        {
            $data['template'] = 'structure_pagegroups_edit.php';
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
    if ($mode == 'pagegroups')
    {
        if ($action == 'new')
        {
            $array[_T('New')] = get_admin_action_link(array(RoutingBackend::REQUEST_KEY_ACTION=>$action));
        }
        if ($action == 'edit')
        {
            //$language = I18N::GetLanguageInfo($id);
            //$array[_T('Edit') . ': ' . $language['name']] = get_admin_action_link(array(RoutingBackend::REQUEST_KEY_ACTION=>$action, RoutingBackend::REQUEST_KEY_ID=>$id));
        }
    }
    $array = array_merge(isset($args[0]) ? $args[0] : array(), $array);
    list($array) = action_event('get_admin_breadcrumb_structure', $array);
    return array($array);
}
add_action('get_admin_breadcrumb', 'get_admin_breadcrumb_structure', 10, 1);
