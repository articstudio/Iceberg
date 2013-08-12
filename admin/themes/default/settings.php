<?php
define('DEFAULT_ADMIN_THEME_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR);
define('DEFAULT_ADMIN_THEME_URL', get_file_url(DEFAULT_ADMIN_THEME_DIR, ICEBERG_DIR_ADMIN, get_base_url_admin()));

require_once DEFAULT_ADMIN_THEME_DIR . 'elfinder/iceberg-elfinder.php';

/* THEME */
function default_admin_theme_head($args)
{
    theme_enqueue_style('jquery-ui');
    theme_enqueue_style('bootstrap');
    if (in_admin_login())
    {
        
    }
    else
    {
        theme_enqueue_style('jquery-elfinder');
        theme_enqueue_style('jquery-treetable');
        theme_enqueue_style('jquery-datatables');
    }
    theme_enqueue_style('iceberg');
    theme_enqueue_script('modernizr');
    return $args;
}
function default_admin_theme_foot($args)
{
    theme_enqueue_script('underscore');
    theme_enqueue_script('jquery');
    theme_enqueue_script('bootstrap');
    theme_enqueue_script('jquery-validate');
    theme_enqueue_script('iceberg');
    if (in_admin_login())
    {
        
    }
    else
    {
        theme_enqueue_script('bootstrap-datepicker');
        theme_enqueue_script('bootstrap-selectpicker');
        theme_enqueue_script('jquery-elfinder');
        theme_enqueue_script('jquery-treetable');
        theme_enqueue_script('jquery-datatables');
        theme_enqueue_script('ckeditor');
        theme_enqueue_script('iceberg-application');
    }
    return $args;
}

if (in_admin())
{
    /* CSS */
    theme_register_style('bootstrap', DEFAULT_ADMIN_THEME_URL . 'css/bootstrap.css', '2.3.2');
    //theme_register_style('jquery-ui', DEFAULT_ADMIN_THEME_URL . 'css/jquery-ui/jquery-ui.css', '1.10.3');
    theme_register_style('jquery-ui', DEFAULT_ADMIN_THEME_URL . 'css/jquery-ui-1.8.18/jquery-ui.css', '1.8.18');
    theme_register_style('jquery-elfinder', DEFAULT_ADMIN_THEME_URL . 'elfinder/jquery-elfinder.css', '2.0');
    theme_register_style('jquery-treetable', DEFAULT_ADMIN_THEME_URL . 'css/jquery-treetable.css', '3.0.2');
    theme_register_style('jquery-datatables', DEFAULT_ADMIN_THEME_URL . 'css/jquery-datatables.css', '3.0.2');
    theme_register_style('iceberg', DEFAULT_ADMIN_THEME_URL . 'css/iceberg.css');
    
    /* SCRIPTS */
    theme_register_script('modernizr', DEFAULT_ADMIN_THEME_URL . 'js/modernizr.js');
    
    theme_register_script('underscore', DEFAULT_ADMIN_THEME_URL . 'js/underscore.js', '1.4.4', array(), true);
    
    //theme_register_script('jquery', DEFAULT_ADMIN_THEME_URL . 'js/jquery.js', '1.10.1', array(), true);
    theme_register_script('jquery', DEFAULT_ADMIN_THEME_URL . 'js/jquery-1.7.2.js', '1.7.2', array(), true);
    theme_register_script('jquery-validate', DEFAULT_ADMIN_THEME_URL . 'js/jquery-validate.js', '2.3.2', array('jquery'), true);
    theme_register_script('jquery-treetable', DEFAULT_ADMIN_THEME_URL . 'js/jquery-treetable.js', '3.0.2', array('jquery'), true);
    theme_register_script('jquery-datatables', DEFAULT_ADMIN_THEME_URL . 'js/jquery-datatables.js', '1.9.4', array('jquery'), true);
    theme_register_script('jquery-elfinder', DEFAULT_ADMIN_THEME_URL . 'elfinder/jquery-elfinder.js', '2.0', array('jquery'), true);
    theme_register_script('ckeditor', DEFAULT_ADMIN_THEME_URL . 'ckeditor/ckeditor.js', '4.1.2', array('jquery-elfinder'), true);
    
    
    theme_register_script('bootstrap', DEFAULT_ADMIN_THEME_URL . 'js/bootstrap.js', '2.3.2', array('jquery'), true);
    theme_register_script('bootstrap-datepicker', DEFAULT_ADMIN_THEME_URL . 'js/bootstrap-datepicker.js', '1.0.1', array('bootstrap'), true);
    theme_register_script('bootstrap-selectpicker', DEFAULT_ADMIN_THEME_URL . 'js/bootstrap-selectpicker.js', null, array('bootstrap'), true);
    
    
    theme_register_script('iceberg', DEFAULT_ADMIN_THEME_URL . 'js/iceberg.js', ICEBERG_VERSION, array('jquery'), true);
    theme_register_script('iceberg-application', DEFAULT_ADMIN_THEME_URL . 'js/iceberg-application.js', ICEBERG_VERSION, array('jquery'), true);
    
    add_action('theme_print_foot', 'default_admin_theme_foot');
    add_action('theme_print_head', 'default_admin_theme_head');
}


/* OBJECT TAXONOMY */
function structure_objtaxonomy_edit_by_mode($args)
{
    $mode = get_mode('mode');
    if ($mode === 'pagegroups')
    {
        include(DEFAULT_ADMIN_THEME_DIR . 'structure_objtaxonomy_edit_pagegroup.php');
    }
    else if ($mode === 'pagetypes')
    {
        include(DEFAULT_ADMIN_THEME_DIR . 'structure_objtaxonomy_edit_pagetype.php');
    }
    else if ($mode === 'pagetaxonomies')
    {
        include(DEFAULT_ADMIN_THEME_DIR . 'structure_objtaxonomy_edit_pagetaxonomy.php');
    }
    return $args;
}
function structure_objtaxonomy_config_by_mode($args)
{
    $mode = get_mode('mode');
    if ($mode === 'pagegroups')
    {
    }
    else if ($mode === 'pagetypes')
    {
    }
    else if ($mode === 'pagetaxonomies')
    {
        include(DEFAULT_ADMIN_THEME_DIR . 'structure_objtaxonomy_config_pagetaxonomy.php');
    }
    return $args;
}

if (in_admin())
{
    add_action('structure_objtaxonomy_edit', 'structure_objtaxonomy_edit_by_mode', 1);
    add_action('structure_objtaxonomy_config', 'structure_objtaxonomy_config_by_mode', 1);
}

