<?php

function get_admin_modes_content($args)
{
    $array = array(
        'publish' => array(
            'template' => 'content_publish.php',
            'name' => 'Publish'
        )
    );
    $array = array_merge(isset($args[0]) ? $args[0] : array(), $array);
    list($array) = action_event('get_admin_modes_content', $array);
    return array($array);
}
add_action('get_modes', 'get_admin_modes_content', 10, 1);

function get_admin_mode_content($args)
{
    list($data, $key) = $args;
    $mode = get_request_mode();
    $action = get_request_action();
    if ($mode == 'publish')
    {
        if ($action == 'new' || $action == 'edit')
        {
            $data['template'] = 'content_publish_edit.php';
        }
    }
    return array($data, $key);
}
add_action('get_mode', 'get_admin_mode_content', 10, 2);


function get_admin_breadcrumb_content($args)
{
    $array = array();
    $mode = get_request_mode();
    $action = get_request_action();
    $id = get_request_id();
    $group = get_request_group();
    if ($mode == 'languages')
    {
        if ($action == 'new')
        {
            $array[_T('New')] = get_admin_action_link(array(RoutingBackend::REQUEST_KEY_ACTION=>$action, RoutingBackend::REQUEST_KEY_GROUP=>$group));
        }
        if ($action == 'edit')
        {
            //$language = I18N::GetLanguageInfo($id);
            //$array[_T('Edit') . ': ' . $language['name']] = get_admin_action_link(array(RoutingBackend::REQUEST_KEY_ACTION=>$action, RoutingBackend::REQUEST_KEY_ID=>$id));
        }
    }
    $array = array_merge(isset($args[0]) ? $args[0] : array(), $array);
    list($array) = action_event('get_admin_breadcrumb_content', $array);
    return array($array);
}
add_action('get_admin_breadcrumb', 'get_admin_breadcrumb_content', 10, 1);