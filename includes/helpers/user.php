<?php

function get_user($id=null, $lang=null)
{
    return User::GetUser($id, $lang);
}

function get_user_list($args=array(), $lang=null)
{
    return User::GetList($args, $lang);
}

/**
 * Returns user ID
 * @uses User::GetID()
 * @return int 
 */
function get_user_id()
{
    return User::GetID();
}

/**
 * Returns user name
 * @uses User::GetUsername()
 * @return string 
 */
function get_user_name()
{
    return User::GetUsername();
}

/**
 * Returns user level
 * @uses User::GetLevel()
 * @return int 
 */
/*function get_user_level()
{
    return User::GetLevel();
}*/


function users_encrypt_password($pass)
{
    return User::EnctryptPassword($pass);
}

function is_login()
{
    return User::IsLogin();
}

function is_logged()
{
    return User::IsLogged();
}

function is_admin()
{
    return User::IsAdmin();
}

function user_has_capability($capability)
{
    return User::HasCapability($capability);
}

function user_has_full_capability($capability)
{
    return User::HasFullCapability($capability);
}

function user_has_own_capability($capability)
{
    return User::HasOwnCapability($capability);
}

