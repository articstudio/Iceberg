<?php
define('DEFAULT_ADMIN_THEME_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR);
define('DEFAULT_ADMIN_THEME_URL', get_file_url(DEFAULT_ADMIN_THEME_DIR, ICEBERG_DIR_ADMIN, get_base_url_admin()));


/* TABLES */
require_once DEFAULT_ADMIN_THEME_DIR . 'tables/TableThemes.class.php';
require_once DEFAULT_ADMIN_THEME_DIR . 'tables/TableLanguages.class.php';
require_once DEFAULT_ADMIN_THEME_DIR . 'tables/TableRoles.class.php';
require_once DEFAULT_ADMIN_THEME_DIR . 'tables/TableCapabilities.class.php';
require_once DEFAULT_ADMIN_THEME_DIR . 'tables/TableUsers.class.php';
require_once DEFAULT_ADMIN_THEME_DIR . 'tables/TableDomains.class.php';
require_once DEFAULT_ADMIN_THEME_DIR . 'tables/TableConfigs.class.php';
require_once DEFAULT_ADMIN_THEME_DIR . 'tables/TableExtensions.class.php';
require_once DEFAULT_ADMIN_THEME_DIR . 'tables/TableObjTaxonomy.class.php';
require_once DEFAULT_ADMIN_THEME_DIR . 'tables/TablePages.class.php';

/* THEME */
function default_admin_theme_head()
{
    theme_enqueue_style('bootstrap');
    theme_enqueue_style('bootstrap-theme');
    theme_enqueue_style('iceberg-bootstrap');
    if (in_admin_login())
    {
        theme_enqueue_style('iceberg-login');
    }
    else
    {
        theme_enqueue_style('bootstrap-tabdrop');
        theme_enqueue_style('bootstrap-timepicker');
        theme_enqueue_style('bootstrap-select');
        theme_enqueue_style('jquery-ui');
        theme_enqueue_style('datatables-bootstrap');
        theme_enqueue_style('iceberg-application');
    }
    theme_enqueue_script('modernizr');
}
function default_admin_theme_foot()
{
    theme_enqueue_script('jquery');
    theme_enqueue_script('jquery-migrate');
    theme_enqueue_script('jquery-validate');
    theme_enqueue_script('iceberg-jquery-validate');
    theme_enqueue_script('bootstrap');
    
    if (in_admin_login())
    {
        theme_enqueue_script('iceberg-login');
    }
    else
    {
        theme_enqueue_script('bootstrap-tabdrop');
        theme_enqueue_script('bootstrap-timepicker');
        theme_enqueue_script('bootstrap-select');
        theme_enqueue_script('jquery-ui');
        theme_enqueue_script('datatables');
        theme_enqueue_script('datatables-tabletools');
        theme_enqueue_script('datatables-rowreordering');
        theme_enqueue_script('datatables-bootstrap');
        theme_enqueue_script('iceberg-application');
        
        $iceberg_application_translations = array(
            'close' => _T('Close'),
            'save' => _T('Save'),
            'confirm' => _T('Confirm'),
            'new' => _T('New'),
            'translate' => _T('Translate'),
            'duplicate' => _T('Duplicate'),
            'loading' => _T('Loading...'),
            'error' => array(
                'reorder' => _T('Failed to reorder items')
            )
        );
        $iceberg_application_translations = apply_filters('iceberg_application_translations', $iceberg_application_translations);
        theme_localize_script('iceberg-application', 'js_iceberg_i18n', $iceberg_application_translations);
    }
}

if (in_admin())
{
    /* CSS */
    theme_register_style('iceberg-bootstrap', get_theme_url() . 'css/iceberg-bootstrap.css', '1.0');
    theme_register_style('iceberg-login', get_theme_url() . 'css/iceberg-login.css');
    theme_register_style('iceberg-application', get_theme_url() . 'css/iceberg-application.css');
    
    /* SCRIPTS */
    theme_register_script('iceberg-jquery-validate', get_theme_url() . 'js/iceberg-jquery-validate.js', '1.0', array('jquery','jquery-validate'), true);
    theme_register_script('iceberg-login', get_theme_url() . 'js/iceberg-login.js', '1.0', array('jquery','bootstrap','jquery-validate','jquery-validate-iceberg'), true);
    theme_register_script('iceberg-application', get_theme_url() . 'js/iceberg-application.js', '1.0', array('jquery','bootstrap','jquery-validate','jquery-validate-iceberg'), true);
    
    add_action('theme_print_foot', 'default_admin_theme_foot');
    add_action('theme_print_head', 'default_admin_theme_head');
}

/* THEME SUPPORT DEPENDENCES */
require_once get_dependences_dir() . 'elfinder/iceberg.php';
require_once get_dependences_dir() . 'ckeditor/iceberg.php';
