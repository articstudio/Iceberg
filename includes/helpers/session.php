<?php

/**
 * Start session
 * 
 * @uses Session::Start()
 * @return boolean 
 */
function do_session_start()
{
    return Session::Start();
}

/**
 * Stop session
 * 
 * @uses Session::Stop()
 * @param boolean $drop
 * @return boolean 
 */
function do_session_stop($drop=false)
{
    return Session::Stop($drop);
}

/**
 * Restart session
 * 
 * @uses Session::Restart()
 * @param boolean $drop
 * @return boolean 
 */
function do_session_restart($drop=false)
{
    return Session::Restart($drop);
}

/**
 * Returns session ID
 * 
 * @uses Session::GetID()
 * @return string|boolean  
 */
function get_session_id()
{
    return Session::GetID();
}

/**
 * Returns session name
 * 
 * @uses Session::GetName()
 * @param boolean $from_config
 * @return string|boolean  
 */
function get_session_name($from_config=true)
{
    return Session::GetName($from_config);
}

/**
 * Returns session lifetime
 * 
 * @return int
 */
function get_session_lifetime()
{
    return Session::GetLifeTime();
}

/**
 * Returns value of session
 * 
 * @uses Session::GetValue()
 * @param string $key
 * @param mixed $default
 * @return mixed 
 */
function get_session_value($key, $default=null)
{
    return Session::GetValue($key, $default);
}

/**
 * Set a session value for a key, if value is null unset the key
 * 
 * @uses Session::SetValue()
 * @param string $key
 * @param mixed $value
 * @return boolean 
 */
function set_session_value($key, $value=null)
{
    return Session::SetValue($key, $value);
}

/**
 * Unset a session value for a key
 * 
 * @uses Session::UnsetValue()
 * @param string $key
 * @return boolean 
 */
function unset_session_value($key)
{
    return Session::UnsetValue($key);
}

/**
 * Check is set key in SESSION
 * 
 * @uses Session::IssetKey()
 * @param string $key
 * @return boolean 
 */
function isset_session_value($key)
{
    return Session::IssetKey($key);
}

/*function get_session_admin_level()
{
    return Session::GetAdminLevel();
}*/
