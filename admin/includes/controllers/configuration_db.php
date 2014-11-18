<?php

function get_actions_configuration_db($actions)
{
    $defaults = array(
        'panel' => array(
            'template' => 'configuration_db_panel.php',
            'name' => _T('Panel')
        ),
        'backup' => array(
            'template' => 'configuration_db_backup.php',
            'name' => _T('Backup')
        ),
        'searchreplace' => array(
            'template' => 'configuration_db_searchreplace.php',
            'name' => _T('Search&Replace')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_configuration_db', 'get_actions_configuration_db', 5);
