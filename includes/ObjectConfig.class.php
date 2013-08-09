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
        $defaults = static::$CONFIG_DEFAULTS;
        if (is_array($config) && is_array($defaults))
        {
            return array_merge((array) $defaults, (array) $config);
        }
        else if (is_object($config) && is_object($defaults))
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
        $value = Config::GetConfig(static::$CONFIG_KEY, static::$CONFIG_DEFAULTS);
        $value = self::NormalizeConfig($value);
        list($value, $class) = action_event(static::$CONFIG_EVENT_GET, $value, get_called_class());
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
            $done = Config::UnsetConfig(static::$CONFIG_KEY);
        }
        else {
            $value = static::NormalizeConfig($value);
            $done = Config::SetConfig(static::$CONFIG_KEY, $value);
        }
        list($value, $class) = action_event(static::$CONFIG_EVENT_SET, $value, get_called_class());
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
        $value = is_null($value) ? $value : self::NormalizeConfig($value);
        list($key, $value, $class, $lang) = action_event(static::$CONFIG_EVENT_SAVE, static::$CONFIG_KEY, $value, get_called_class(), $lang);
        $done = (is_null($lang) || $lang == I18N::GetLanguage() || $lang == Config::REPLICATE_CONFIG_ALL_LANGUAGES) ? static::SetConfig($value) : true;
        if ($done) {
            $value = static::NormalizeConfig($value);
            $done = Config::SaveConfig(static::$CONFIG_KEY, $value, $lang);
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
        $value = Config::SelectConfig(static::$CONFIG_KEY, $default, $inBuffer, $lang);
        $value = static::NormalizeConfig($value);
        list($value, $default, $inBuffer, $class, $lang) = action_event(static::$CONFIG_EVENT_SELECT, $value, $default, $inBuffer, get_called_class(), $lang);
        return $value;
    }
    
}

interface ObjectConfigInterface
{
    static public function GetConfigValue($key, $default=false);
    static public function SetConfigValue($key, $value);
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
    public static $CONFIG_EVENT_SET_VALUE = 'object_set_config_value';
    
    
    static public function GetConfigValue($key, $default=false)
    {
        $config = static::GetConfig();
        $value = isset($config[$key]) ? $config[$key] : $default;
        list($value, $key, $default, $class) = action_event(static::$CONFIG_EVENT_GET_VALUE, $value, $key, $default, get_called_class());
        return $value;
    }
    
    static public function SetConfigValue($key, $value)
    {
        $config = static::GetConfig();
        list($key, $value, $class) = action_event(static::$CONFIG_EVENT_SET_VALUE, $key, $value, get_called_class());
        $config[$key] = $value;
        return static::SetConfig($config);
    }
    
}