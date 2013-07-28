<?php

define('API_ELFINDER_ENVIRONMENT', RoutingBackendAPI::REQUEST_ENVIRONMENT_ADMIN_API . DIRECTORY_SEPARATOR . 'elfinder');


function get_elfinder_api_link($params=array())
{
    $url = get_base_url_api() .  API_ELFINDER_ENVIRONMENT . DIRECTORY_SEPARATOR;
    return get_link($params, $url);
    return $url;
}


/* ADMIN */
function elFinderAdminFoot()
{
    if (!in_admin_login())
    {
        print '<script>';
        print 'var elFinderAPI = "' . get_elfinder_api_link() . '";';
        print 'var elFinderAPI_popup = "' . get_elfinder_api_link(array('module'=>'popup')) . '";';
        print 'var elFinderAPI_popup_ckeditor = "' . get_elfinder_api_link(array('module'=>'ckeditor')) . '";';
        print 'var elFinderAPI_popup_ckeditor_Image = "' . get_elfinder_api_link(array('module'=>'ckeditor','mode'=>'image')) . '";';
        print 'var elFinderAPI_popup_ckeditor_Flash = "' . get_elfinder_api_link(array('module'=>'ckeditor','mode'=>'flash')) . '";';
        print '</script>';
    }
    
}

if (in_admin())
{
    add_action('theme_print_foot', 'elFinderAdminFoot');
}

/* API */
function iceberg_api_generate_elfinder($args)
{
    if (RoutingAPI::InEnvironment(API_ELFINDER_ENVIRONMENT))
    {
        if (is_admin())
        {
            $module = get_request_module();
            if ($module === 'ckeditor')
            {
                require_once DEFAULT_ADMIN_THEME_DIR . 'elfinder/popup-ckeditor.php';
            }
            else if ($module === 'popup')
            {
                require_once DEFAULT_ADMIN_THEME_DIR . 'elfinder/popup.php';
            }
            else
            {
                require_once DEFAULT_ADMIN_THEME_DIR . 'elfinder/connector.php';
            }
        }
        else
        {
            Request::Response(403);
        }
        exit();
    }
    return $args;
}
add_action('iceberg_api_generate', 'iceberg_api_generate_elfinder', 0);