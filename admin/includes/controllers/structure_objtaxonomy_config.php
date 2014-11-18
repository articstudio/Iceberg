<?php

function get_action_structure_objtaxonomy_config($action, $key)
{
    $id = get_request_id();
    $obj = ObjectTaxonomy::Get($id);
    $default = array(
        'name' => _T('Configuration') . ': ' . $obj->GetName()
    );
    $action = array_merge($action, $default);
    return $action;
}
add_filter('get_action_structure_' . PageTaxonomy::$TAXONOMY_KEY . '_config', 'get_action_structure_objtaxonomy_config', 5, 2);
add_filter('get_action_structure_' . PageType::$TAXONOMY_KEY . '_config', 'get_action_structure_objtaxonomy_config', 5, 2);
add_filter('get_action_structure_' . PageGroup::$TAXONOMY_KEY . '_config', 'get_action_structure_objtaxonomy_config', 5, 2);

function structure_objtaxonomy_config($mode)
{
    if ($mode === PageTaxonomy::$TAXONOMY_KEY || $mode === PageType::$TAXONOMY_KEY || $mode === PageGroup::$TAXONOMY_KEY)
    {
        $file = get_theme_dir() . 'structure_objtaxonomy_config_' . $mode . '.php';
        if (is_file($file) && is_readable($file))
        {
            require $file;
        }
    }
}
add_action('structure_objtaxonomy_config', 'structure_objtaxonomy_config', 5);
