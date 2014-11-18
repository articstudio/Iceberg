<?php

function get_default_modules($modules)
{
    $defaults = array(
        'structure' => array(
            'template' => 'structure.php',
            'name' => _T('Structure'),
        ),
        'content' => array(
            'template' => 'content.php',
            'name' => _T('Content'),
        ),
        'media' => array(
            'template' => 'media.php',
            'name' => _T('Media'),
        ),
        'extensions' => array(
            'template' => 'extensions.php',
            'name' => _T('Extensions'),
        ),
        'configuration' => array(
            'template' => 'configuration.php',
            'name' => _T('Configuration'),
        )
    );
    $modules = array_merge($modules, $defaults);
    $modules = apply_filters('get_default_modules', $modules);
    return $modules;
}
add_filter('get_modules', 'get_default_modules', 5);

function get_default_module($module, $key)
{
    $module = apply_filters('get_default_module', $module, $key);
    return $module;
}
add_filter('get_module', 'get_default_module', 5, 2);
