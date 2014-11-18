<?php

function get_modes_content($modes)
{
    $defaults = array();
    $groups = PageGroup::GetList();
    if (!empty($groups))
    {
        foreach ($groups AS $group)
        {
            $defaults['group-' . $group->GetID()] = array(
                'template' => 'content_publish.php',
                'name' => $group->GetName()
            );
        }
        
        /*$defaults['menubar'] = array(
            'template' => 'content_menubar.php',
            'name' => _T('Menubar')
        );*/
    }
    $modes = array_merge($modes, $defaults);
    return $modes;
}
add_filter('get_modes_content', 'get_modes_content', 5);
