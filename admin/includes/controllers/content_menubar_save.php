<?php

$names = get_request_p('menubar-name', array(), true);
$types = get_request_p('menubar-type', array(), true);
$urls = get_request_p('menubar-url', array(), true);
$page = get_request_p('menubar-page', array(), true);
$externals = get_request_p('menubar-external', array(), true);
$submenus = get_request_p('menubar-submenu', array(), true);
$cssclassess = get_request_p('menubar-cssclassess', array(), true);
$links = array();
foreach ($names AS $k => $name)
{
    $links[$k] = array(
        'type' => isset($types[$k]) ? $types[$k] : 'link',
        'name' => $name,
        'url' => isset($urls[$k]) ? $urls[$k] : '',
        'page' => isset($page[$k]) ? $page[$k] : '',
        'external' => isset($externals[$k]) ? $externals[$k] : '',
        'submenu' => isset($submenus[$k]) ? $submenus[$k] : '',
        'cssclassess' => isset($cssclassess[$k]) ? $cssclassess[$k] : '',
    );
}
if (Menubar::SaveLinks($links))
{
    register_alert('Menubar saved', 'success');
}
else
{
    register_alert('Failed to save the menubar', 'error');
}

locate(get_admin_action_link(array('action'=>'edit')));
