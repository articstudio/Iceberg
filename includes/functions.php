<?php

/**
 * Return IP of client
 * 
 * @return string IP of user
 */
function getIP()
{
    $ip='0.0.0.0';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { $ip=$_SERVER['HTTP_CLIENT_IP']; }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; }
    else { $ip=$_SERVER['REMOTE_ADDR']; }
    return $ip;
}

/**
 * Compare if $version is equal or upper than PHP version
 * 
 * @param string $version Required version of PHP
 * @return bool 
 */
function phpVersionCompatible($version)
{
    return strnatcmp(phpversion(), $version) >= 0 ? true : false;
}


function reOrderArray($arr, $from, $to)
{
    if (is_array($arr) && $from!=$to)
    {
        $el = array_splice($arr, $from, 1);
        $begin = array_splice($arr, 0, $to);
        $arr = array_merge($begin, $el, $arr);
    }
    return $arr;
}

