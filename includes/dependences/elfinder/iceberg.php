<?php

define('ELFINDER', 1);
define('API_ELFINDER_ENVIRONMENT', RoutingBackendAPI::REQUEST_ENVIRONMENT_ADMIN_API . DIRECTORY_SEPARATOR . 'elfinder');


/* API */
function iceberg_api_generate_elfinder()
{
    if (RoutingAPI::InEnvironment(API_ELFINDER_ENVIRONMENT))
    {
        if (is_admin()) //@todo Capabilities
        {
            $module = get_request_module();
            if ($module === 'ckeditor')
            {
                require_once get_dependences_dir() . 'elfinder/php/popup-ckeditor.php';
            }
            else if ($module === 'popup')
            {
                require_once get_dependences_dir() . 'elfinder/php/popup.php';
            }
            else
            {
                require_once get_dependences_dir() . 'elfinder/php/connector.php';
            }
        }
        else
        {
            Request::Response(403);
        }
        exit();
    }
}
add_action('iceberg_api_generate', 'iceberg_api_generate_elfinder', 5);
function get_elfinder_api_link($params=array())
{
    $url = get_base_url_api() .  API_ELFINDER_ENVIRONMENT . DIRECTORY_SEPARATOR;
    return get_link($params, $url);
}

/* THEME */
function theme_print_head_elfinder()
{
    if (!in_admin_login())
    {
        theme_enqueue_style('elfinder');
        theme_enqueue_style('elfinder-theme');
    }
}
function theme_print_foot_elfinder()
{
    if (!in_admin_login())
    {
        $elfinder = array(
            'lang' => get_lang_iso(),
            'api' => get_elfinder_api_link(),
            'popup' => get_elfinder_api_link(array('module'=>'popup')),
            'popup_ckeditor' => get_elfinder_api_link(array('module'=>'ckeditor')),
            'popup_ckeditor_image' => get_elfinder_api_link(array('module'=>'ckeditor','mode'=>'image')),
            'popup_ckeditor_flash' => get_elfinder_api_link(array('module'=>'ckeditor','mode'=>'flash'))
        );
        theme_localize_script('elfinder-iceberg', 'js_elfinder', $elfinder);
        
        theme_enqueue_script('elfinder');
        theme_enqueue_script('elfinder-iceberg');
    }
}
add_action('theme_print_foot', 'theme_print_foot_elfinder');
add_action('theme_print_head', 'theme_print_head_elfinder');

