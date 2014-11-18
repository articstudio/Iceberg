<?php

function get_action_structure_objtaxonomy_edit($action, $key)
{
    $id = get_request_id();
    $obj = ObjectTaxonomy::Get($id);
    $default = array(
        'name' => _T('Edit') . ': ' . $obj->GetName()
    );
    $action = array_merge($action, $default);
    return $action;
}
add_filter('get_action_structure_' . PageTaxonomy::$TAXONOMY_KEY . '_edit', 'get_action_structure_objtaxonomy_edit', 5, 2);
add_filter('get_action_structure_' . PageType::$TAXONOMY_KEY . '_edit', 'get_action_structure_objtaxonomy_edit', 5, 2);
add_filter('get_action_structure_' . PageGroup::$TAXONOMY_KEY . '_edit', 'get_action_structure_objtaxonomy_edit', 5, 2);

function structure_objtaxonomy_edit($mode)
{
    if ($mode === PageTaxonomy::$TAXONOMY_KEY || $mode === PageType::$TAXONOMY_KEY || $mode === PageGroup::$TAXONOMY_KEY)
    {
        $file = get_theme_dir() . 'structure_objtaxonomy_edit_' . $mode . '.php';
        if (is_file($file) && is_readable($file))
        {
            require $file;
        }
    }
}
add_action('structure_objtaxonomy_edit', 'structure_objtaxonomy_edit', 5);
