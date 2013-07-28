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
    return Routing::MakeURL($params, $baseurl);
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

/*
function page_link($params=array(), $print=true)
{
    if (!isset($params[REQUEST_VAR_LANGUAGE])) {
        $params[REQUEST_VAR_LANGUAGE] = get_lang();
    }
    if ($print) {
        print_link(get_base_url(), $params);
    }
    else {
        return get_link(get_base_url(), $params);
    }
}
*/

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