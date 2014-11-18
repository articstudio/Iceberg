<?php

function get_actions_content_publish($actions)
{
    if (in_api())
    {
        $defaults = array(
            'list-ajax' => array(
                'template' => 'content_publish_list_ajax.php',
                'name' => _T('Order')
            )
        );
    }
    else
    {
        $defaults = array(
            'list' => array(
                'template' => 'content_publish_list.php',
                'name' => _T('List')
            ),
            'new' => array(
                'template' => 'content_publish_edit.php',
                'name' => _T('New Page')
            ),
            'edit' => array(
                'template' => 'content_publish_edit.php',
                'name' => _T('Edit Page')
            ),
            'insert' => array(
                'template' => 'content_publish_insert.php',
                'name' => _T('Insert')
            ),
            'update' => array(
                'template' => 'content_publish_update.php',
                'name' => _T('Update')
            ),
            'active' => array(
                'template' => 'content_publish_active.php',
                'name' => _T('Active')
            ),
            'unactive' => array(
                'template' => 'content_publish_unactive.php',
                'name' => _T('Unactive')
            ),
            'remove' => array(
                'template' => 'content_publish_remove.php',
                'name' => _T('Remove')
            )
        );
    }
    $actions = array_merge($actions, $defaults);
    return $actions;
}

$groups = PageGroup::GetList();
if (!empty($groups))
{
    foreach ($groups AS $group)
    {
        add_filter('get_actions_content_group-' . $group->GetID(), 'get_actions_content_publish', 5);
    }
}


