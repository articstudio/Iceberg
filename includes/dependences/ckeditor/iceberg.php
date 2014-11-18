<?php


define('CKEDITOR', 1);
define('API_CKEDITOR_ENVIRONMENT', RoutingBackendAPI::REQUEST_ENVIRONMENT_ADMIN_API . DIRECTORY_SEPARATOR . 'ckeditor');



/* API */
function iceberg_api_generate_ckeditor()
{
    if (RoutingAPI::InEnvironment(API_CKEDITOR_ENVIRONMENT))
    {
        if (is_admin()) //@todo Capabilities
        {
            /*$action = get_request_action();
            if ($action === 'page-list')
            {
                $args = array(
                    'filter' => get_request_gp('filter', array(), true),
                    'order' => 'name'
                );
                $pages = get_pages($args);
                $json = array();
                foreach ($pages AS $page)
                {
                    array_push($json, array($page->GetLink(), $page->GetTitle()));
                }
                print json_encode($json);
            }*/
        }
        else
        {
            Request::Response(403);
        }
        exit();
    }
}
add_action('iceberg_api_generate', 'iceberg_api_generate_ckeditor', 5);
function get_ckeditor_api_link($params=array())
{
    $url = get_base_url_api() .  API_CKEDITOR_ENVIRONMENT . DIRECTORY_SEPARATOR;
    return get_link($params, $url);
}


/* THEME */
function theme_print_foot_ckeditor()
{
    if (!in_admin_login())
    {
        theme_enqueue_script('ckeditor');
    }
    
}
add_action('theme_print_foot', 'theme_print_foot_ckeditor');
