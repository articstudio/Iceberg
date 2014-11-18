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
function save_config($keyname, $value=null, $lang=null)
{
    return $lang===false ? ConfigAll::SaveConfig($keyname, $value) :  Config::SaveConfig($keyname, $value, $lang);
}

/**
 * Unsave configuration value for keyname
 * 
 * @uses Config::UnsaveConfig();
 * @param string $keyname
 * @return boolean 
 */
function unsave_config($keyname, $lang=null)
{
    return $lang===false ? ConfigAll::UnsaveConfig($keyname) : Config::UnsaveConfig($keyname, $lang);
}

/**
 * Select configuration value for keyname (If is buffer not set value)
 * 
 * @param string $keyname
 * @param mixed $default
 * @param boolean $isBuffer
 * @return mixed 
 */
function select_config($keyname, $default=false, $isBuffer=false, $lang=null)
{
    return $lang===false ? ConfigAll::SelectConfig($keyname, $default, $isBuffer) : Config::SelectConfig($keyname, $default, $isBuffer, $lang);
}

function select_all_config($isBuffer=false, $lang=null)
{
    return $lang===false ? ConfigAll::SelectAll($isBuffer) : Config::SelectAll($isBuffer, $lang);
}

function select_all_config_objects($lang=null)
{
    return $lang===false ? ConfigAll::SelectAllObjects() : Config::SelectAllObjects($lang);
}

function select_config_object($keyname, $lang=null)
{
    return $lang===false ? ConfigAll::SelectConfigObject($keyname) : Config::SelectConfigObject($keyname, $lang);
}

function select_config_object_by_id($id)
{
    return ConfigAll::SelectConfigObjectByID($id);
}
