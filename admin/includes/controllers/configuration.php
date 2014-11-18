<?php

function get_modes_configuration($modes)
{
    $defaults = array(
        'settings' => array(
            'template' => 'configuration_settings.php',
            'name' => _T('Settings')
        ),
        'maintenance' => array(
            'template' => 'configuration_maintenance.php',
            'name' => _T('Maintenance')
        ),
        'themes' => array(
            'template' => 'configuration_themes.php',
            'name' => _T('Themes')
        ),
        'languages' => array(
            'template' => 'configuration_languages.php',
            'name' => _T('Languages')
        ),
        'roles' => array(
            'template' => 'configuration_roles.php',
            'name' => _T('User Roles')
        ),
        'capabilities' => array(
            'template' => 'configuration_capabilities.php',
            'name' => _T('User Capabilities')
        ),
        'users' => array(
            'template' => 'configuration_users.php',
            'name' => _T('Users')
        ),
        'routing' => array(
            'template' => 'configuration_routing.php',
            'name' => _T('Routing')
        ),
        'domains' => array(
            'template' => 'configuration_domains.php',
            'name' => _T('Domains')
        ),
        'db' => array(
            'template' => 'configuration_db.php',
            'name' => _T('Database')
        ),
        'details' => array(
            'template' => 'configuration_details.php',
            'name' => _T('Configuration')
        )
    );
    $modes = array_merge($modes, $defaults);
    return $modes;
}
add_filter('get_modes_configuration', 'get_modes_configuration', 5);
