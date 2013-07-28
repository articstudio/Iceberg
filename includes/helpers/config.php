<?php

/**
 * Get a configuration value for a key
 * 
 * @uses Config::GetConfig();
 * @param string $keyname
 * @return mixed 
 */
function get_config($keyname, $default=false)
{
    return Config::GetConfig($keyname, $default);
}

/**
 * Set a configuration value for a key, if value is null unset the key
 * 
 * @uses Config::SetConfig()
 * @param string $keyname
 * @param mixed $value
 * @return boolean 
 */
function set_config($keyname, $value=null)
{
    return Config::SetConfig($keyname, $value);
}

/**
 * Unset a configuration value for a key
 * 
 * @uses Config::UnsetConfig();
 * @param string $keyname
 * @return boolean 
 */
function unset_config($keyname)
{
    return Config::UnsetConfig($keyname);
}

/**
 * Save configuration value for a keyname (If value is NULL Unsave it)
 * 
 * @uses Config::SaveConfig();
 * @param string $keyname
 * @param mixed $value
 * @return boolean 
 */
function save_config($keyname, $value=null)
{
    return Config::SaveConfig($keyname, $value);
}

/**
 * Unsave configuration value for keyname
 * 
 * @uses Config::UnsaveConfig();
 * @param string $keyname
 * @return boolean 
 */
function unsave_config($keyname)
{
    return Config::UnsaveConfig($keyname);
}

/**
 * Select configuration value for keyname (If is buffer not set value)
 * 
 * @param string $keyname
 * @param mixed $default
 * @param boolean $isBuffer
 * @return mixed 
 */
function select_config($keyname, $default=false, $isBuffer=false)
{
    return Config::SelectConfig($keyname, $default, $isBuffer);
}
