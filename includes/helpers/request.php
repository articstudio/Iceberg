<?php

/**
 * Returns the request protocol
 * 
 * @uses Request::GetProtocol()
 * @return string 
 */
function get_request_protocol()
{
    return Request::GetProtocol();
}

/**
 * Returns the domain
 * 
 * @uses Request::GetDomain()
 * @return string 
 */
function get_request_domain()
{
    return Request::GetDomain();
}

/**
 * Returns the URL (With or without protocol)
 * 
 * @uses Request::GetUrl()
 * @param bool $withProtocol
 * @return string 
 */
function get_url($withProtocol=true)
{
    return Request::GetUrl($withProtocol);
}

/**
 * Returns the base URL (With or without protocol)
 * 
 * @uses Request::GetBaseUrl()
 * @param bool $withProtocol
 * @return string 
 */
function get_base_url($protocol=true)
{
    return Request::GetBaseUrl($protocol);
}

/**
 * Returns the base URL ADMIN APP (With or without protocol)
 * 
 * @uses Request::GetBaseUrlAdmin()
 * @param bool $withProtocol
 * @return string 
 */
function get_base_url_admin($protocol=true)
{
    return Request::GetBaseUrlAdmin($protocol);
}

/**
 * Returns the base URL API APP (With or without protocol)
 * 
 * @uses Request::GetBaseUrlAPI()
 * @param bool $withProtocol
 * @return string 
 */
function get_base_url_api($protocol=true)
{
    return Request::GetBaseUrlAPI($protocol);
}

/**
 * Returns value of GET
 * 
 * @uses Request::GetValueG()
 * @param string $key
 * @param bool $default
 * @param bool $stripslahes
 * @return string 
 */
function get_request_g($key, $default=false, $stripslahes=false)
{
    return Request::GetValueG($key, $default, $stripslahes);
}

/**
 * Returns value of POST 
 * 
 * @uses Request::GetValueP()
 * @param string $key
 * @param bool $default
 * @param bool $stripslahes
 * @return string 
 */
function get_request_p($key, $default=false, $stripslahes=false)
{
    return Request::GetValueP($key, $default, $stripslahes);
}

/**
 * Returns value of GET > POST 
 * 
 * @uses Request::GetValueGP()
 * @param string $key
 * @param bool $default
 * @param bool $stripslahes
 * @return string 
 */
function get_request_gp($key, $default=false, $stripslahes=false)
{
    return Request::GetValueGP($key, $default, $stripslahes);
}

/**
 * Returns value of SESSION > GET > POST 
 * 
 * @uses Request::GetValueSGP()
 * @param string $key
 * @param bool $default
 * @param bool $stripslahes
 * @return string 
 */
function get_request_sgp($key, $default=false, $stripslahes=false)
{
    return Request::GetValueSGP($key, $default, $stripslahes);
}

/**
 * Set GET value for a key
 * 
 * @uses Request::SetValueG()
 * @param string $key
 * @param mixed $value
 * @return boolean 
 */
function set_request_g($key, $value=false)
{
    return Request::SetValueG($key, $value);
}

/**
 * Set POST value for a key
 * 
 * @uses Request::SetValueP()
 * @param string $key
 * @param mixed $value
 * @return boolean 
 */
function set_request_p($key, $value=false)
{
    return Request::SetValueP($key, $value);
}

/**
 * Locate request
 * 
 * @uses Request::SetValueP()
 * @param string $url
 * @param int $code 
 */
function locate($url, $code=100)
{
    Request::Locate($url, $code);
}
