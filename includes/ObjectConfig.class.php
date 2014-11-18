<?php


/**
 * Configuration object base
 * 
 * Manage configuration objects
 *  
 * @package Iceberg
 * @subpackage Configuration
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0 
 */
abstract class ObjectConfigBase
{
    /**
     * Configuration use language
     * @var boolean
     */
    public static $CONFIG_USE_LANGUAGE = true;
    
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'object_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array();
    
    /**
     * Configuration event to get
     * @var string
     */
    public static $CONFIG_EVENT_GET = 'object_get_config';
    
    /**
     * Configuration event to set
     * @var string
     */
    public static $CONFIG_EVENT_SET = 'object_set_config';
    
    /**
     * Configuration event to save
     * @var string
     */
    public static $CONFIG_EVENT_SAVE = 'object_save_config';
    
    /**
     * Configuration event to select
     * @var string
     */
    public static $CONFIG_EVENT_SELECT = 'object_select_config';
    
    /**
     * Normalize config
     * 
     * @param mixed $config
     * @return mixed
     */
    static public function NormalizeConfig($config)
    {
        if (is_null($config))
        {
            return $config;
        }
        $defaults = static::$CONFIG_DEFAULTS;
        if (is_array($defaults))
        {
            return array_merge((array) $defaults, (array) $config);
        }
        else if (is_object($defaults))
        {
            return (object) array_merge((array) $defaults, (array) $config);
        }
        return $config;
    }
    
    /**
     * Get object configuration
     * 
     * @return mixed 
     */
    static public function GetConfig()
    {
        $value = static::$CONFIG_USE_LANGUAGE ? Config::GetConfig(static::$CONFIG_KEY, static::$CONFIG_DEFAULTS) :  ConfigAll::GetConfig(static::$CONFIG_KEY, static::$CONFIG_DEFAULTS);
        $value = static::NormalizeConfig($value);
        $value = apply_filters(static::$CONFIG_EVENT_GET, $value, get_called_class());
        return $value;
    }
    
    /**
     * Set object configuration
     * 
     * @param mixed $value
     * @return boolean 
     */
    static public function SetConfig($value=null)
    {
        $done = false;
        if (is_null($value)) {
            $done = static::$CONFIG_USE_LANGUAGE ? Config::UnsetConfig(static::$CONFIG_KEY) : ConfigAll::UnsetConfig(static::$CONFIG_KEY);
        }
        else {
            $value = static::NormalizeConfig($value);
            $done = static::$CONFIG_USE_LANGUAGE ? Config::SetConfig(static::$CONFIG_KEY, $value) : ConfigAll::SetConfig(static::$CONFIG_KEY, $value);
        }
        $value = apply_filters(static::$CONFIG_EVENT_SET, $value, get_called_class());
        return $done;
    }
    
    /**
     * Unset object configuration
     * 
     * @return boolean 
     */
    static public function UnsetConfig()
    {
        return static::SetConfig();
    }
    
    /**
     * Save object configuration
     * 
     * @param mixed $value
     * @return boolean 
     */
    static public function SaveConfig($value=null, $lang=null)
    {
        $value = is_null($value) ? $value : static::NormalizeConfig($value);
        $value = apply_filters(static::$CONFIG_EVENT_SAVE, $value, get_called_class(), static::$CONFIG_KEY, $lang);
        $done = (is_null($lang) || $lang === I18N::GetLanguage() || $lang === Config::REPLICATE_ALL_LANGUAGES || !static::$CONFIG_USE_LANGUAGE) ? static::SetConfig($value) : true;
        if ($done) {
            $value = static::NormalizeConfig($value);
            $done = static::$CONFIG_USE_LANGUAGE ? Config::SaveConfig(static::$CONFIG_KEY, $value, $lang) : ConfigAll::SaveConfig(static::$CONFIG_KEY, $value);
        }
        return $done;
    }
    
    /**
     * Unsave object configuration
     * 
     * @return boolean 
     */
    static public function UnsaveConfig($lang=null)
    {
        return static::SaveConfig(null, $lang);
    }
    
    /**
     * Select object configuration
     * 
     * @param mixed $default
     * @param boolean $inBuffer
     * @return mixed 
     */
    static public function SelectConfig($default=false, $inBuffer=false, $lang=null)
    {
        $value = static::$CONFIG_USE_LANGUAGE ? Config::SelectConfig(static::$CONFIG_KEY, $default, $inBuffer, $lang) : ConfigAll::SelectConfig(static::$CONFIG_KEY, $default, $inBuffer);
        $value = static::NormalizeConfig($value);
        $value = apply_filters(static::$CONFIG_EVENT_SELECT, $value, $default, $inBuffer, get_called_class(), $lang);
        return $value;
    }
    
}

interface ObjectConfigInterface
{
    static public function GetConfigValue($key, $default=false);
    static public function SetConfigValue($key, $value);
    static public function SaveConfigValue($key, $value, $lang=null);
}

/**
 * Configuration object
 * 
 * Manage configuration objects
 *  
 * @package Iceberg
 * @subpackage Configuration
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0 
 */
abstract class ObjectConfig extends ObjectConfigBase implements ObjectConfigInterface
{
    
    /**
     * Configuration event to get
     * @var string
     */
    public static $CONFIG_EVENT_GET_VALUE = 'object_get_config_value';
    
    /**
     * Configuration event to set
     * @var string
     */
    public static $CONFIG_EVENT_SET_VALUE = 'object_Set_config_value';
    
    /**
     * Configuration event to save
     * @var string
     */
    public static $CONFIG_EVENT_SAVE_VALUE = 'object_save_config_value';
    
    
    static public function GetConfigValue($key, $default=false)
    {
        $config = static::GetConfig();
        $value = isset($config[$key]) ? $config[$key] : $default;
        $value = apply_filters(static::$CONFIG_EVENT_GET_VALUE, $value, $key, $default, get_called_class());
        return $value;
    }
    
    static public function SetConfigValue($key, $value)
    {
        $config = static::GetConfig();
        $value = apply_filters(static::$CONFIG_EVENT_SET_VALUE, $value, $key, get_called_class());
        $config[$key] = $value;
        return static::SetConfig($config);
    }
    
    static public function SaveConfigValue($key, $value, $lang=null)
    {
        $config = static::GetConfig();
        $value = apply_filters(static::$CONFIG_EVENT_SAVE_VALUE, $value, $key, get_called_class(), $lang);
        $config[$key] = $value;
        return static::SaveConfig($config, $lang);
    }
}