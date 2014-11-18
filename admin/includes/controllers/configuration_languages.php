<?php

function get_actions_configuration_languages($actions)
{
    $defaults = array();
    if (in_api())
    {
        $defaults = array(
            'order' => array(
                'template' => 'configuration_languages_order.php',
                'name' => _T('Order')
            )
        );
    }
    else
    {
        $defaults = array(
            'list' => array(
                'template' => 'configuration_languages_list.php',
                'name' => _T('List')
            ),
            'new' => array(
                'template' => 'configuration_languages_edit.php',
                'name' => _T('New')
            ),
            'edit' => array(
                'template' => 'configuration_languages_edit.php',
                'name' => _T('Edit')
            ),
            'active' => array(
                'template' => 'configuration_languages_active.php',
                'name' => _T('Active')
            ),
            'unactive' => array(
                'template' => 'configuration_languages_unactive.php',
                'name' => _T('Unactive')
            ),
            'visible' => array(
                'template' => 'configuration_languages_visible.php',
                'name' => _T('Visible')
            ),
            'invisible' => array(
                'template' => 'configuration_languages_invisible.php',
                'name' => _T('Invisible')
            ),
            'default' => array(
                'template' => 'configuration_languages_default.php',
                'name' => _T('Default')
            ),
            'remove' => array(
                'template' => 'configuration_languages_remove.php',
                'name' => _T('Remove')
            ),
            'insert' => array(
                'template' => 'configuration_languages_insert.php',
                'name' => _T('Insert')
            ),
            'update' => array(
                'template' => 'configuration_languages_update.php',
                'name' => _T('Update')
            )
        );
    }
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_configuration_languages', 'get_actions_configuration_languages', 5);
