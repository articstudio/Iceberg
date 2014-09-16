<?php


/**
 * Make a URL
 * @uses Request::MakeURL()
 * @param string $url
 * @param array $params
 * @return string 
 */
function get_link($params=array(), $baseurl=null)
{
    $routing = Routing::GetRouting();
    return $routing->GenerateURL($params, $baseurl);
}

/**
 * Print a URL
 * @param string $url
 * @param array $params 
 */
function print_link($params=array(), $baseurl=null)
{
    printf( '%s', get_link($params, $baseurl) );
}

/**
 * Make a URL to dir
 * @uses Request::MakeURLDir()
 * @param string $dir
 * @param array $params
 * @return string 
 */
function get_link_dir($dir='')
{
    return File::GetURL($dir, ICEBERG_DIR, get_base_url());
}

/**
 * Print a URL to dir
 * @param string $dir
 * @param array $params 
 */
function print_link_dir($dir='')
{
    printf( '%s', get_link_dir($dir) );
}

function get_routing_types()
{
    return Routing::GetTypes();
}

function get_routing_type()
{
    return Routing::GetConfigValue('type', -1);
}

function get_routing_canonicals()
{
    return Routing::GetCanonicals();
}

function get_routing_canonical()
{
    return Routing::GetConfigValue('canonical', -1);
}

function get_routing_domains_by_language()
{
    return Routing::GetConfigValue('domains_by_language', array());
}

function get_routing_domains()
{
    return Routing::GetConfigValue('domains', array());
}