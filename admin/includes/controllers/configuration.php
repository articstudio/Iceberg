<?php


function get_admin_modes_configuration($args)
{
    $array = array(
        'settings' => array(
            'template' => 'configuration_settings.php',
            'name' => 'Settings'
        ),
        'maintenance' => array(
            'template' => 'configuration_maintenance.php',
            'name' => 'Maintenance'
        ),
        'themes' => array(
            'template' => 'configuration_themes.php',
            'name' => 'Themes'
        ),
        'languages' => array(
            'template' => 'configuration_languages.php',
            'name' => 'Languages'
        ),
        'users' => array(
            'template' => 'configuration_users.php',
            'name' => 'Users'
        ),
        'domains' => array(
            'template' => 'configuration_domains.php',
            'name' => 'Domains'
        ),
        'db' => array(
            'template' => 'configuration_db.php',
            'name' => 'DB'
        )
    );
    $array = array_merge(isset($args[0]) ? $args[0] : array(), $array);
    list($array) = action_event('get_admin_modes_configuration', $array);
    return array($array);
}
add_action('get_modes', 'get_admin_modes_configuration', 10, 1);


function get_admin_mode_configuration($args)
{
    list($data, $key) = $args;
    $mode = get_request_mode();
    $action = get_request_action();
    if ($mode == 'languages')
    {
        if ($action == 'new' || $action == 'edit')
        {
            $data['template'] = 'configuration_languages_edit.php';
        }
    }
    else if ($mode == 'domains')
    {
        if ($action == 'new' || $action == 'edit')
        {
            $data['template'] = 'configuration_domains_edit.php';
        }
    }
    return array($data, $key);
}
add_action('get_mode', 'get_admin_mode_configuration', 10, 2);


function get_admin_breadcrumb_configuration($args)
{
    $array = array();
    $mode = get_request_mode();
    $action = get_request_action();
    $id = get_request_id();
    if ($mode == 'languages')
    {
        if ($action == 'new')
        {
            $array[_T('New')] = get_admin_action_link(array(RoutingBackend::REQUEST_KEY_ACTION=>$action));
        }
        if ($action == 'edit')
        {
            $language = I18N::GetLanguageInfo($id);
            $array[_T('Edit') . ': ' . $language['name']] = get_admin_action_link(array(RoutingBackend::REQUEST_KEY_ACTION=>$action, RoutingBackend::REQUEST_KEY_ID=>$id));
        }
    }
    else if ($mode == 'domains')
    {
        if ($action == 'new')
        {
            //$data['template'] = 'configuration_domains_edit.php';
        }
        if ($action == 'edit')
        {
            //$data['template'] = 'configuration_domains_edit.php';
        }
    }
    $array = array_merge(isset($args[0]) ? $args[0] : array(), $array);
    list($array) = action_event('get_admin_breadcrumb_configuration', $array);
    return array($array);
}
add_action('get_admin_breadcrumb', 'get_admin_breadcrumb_configuration', 10, 1);
