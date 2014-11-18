<?php

/** Include helpers metatag file */
require_once ICEBERG_DIR_HELPERS . 'metatag.php';

class Metatag extends ObjectConfig
{
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'metatags_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'title' => '',
        'description' => '',
        'keywords' => ''
    );
    
    
    public static function Save($config)
    {
        return static::SaveConfig($config);
    }
    
    public static function GetMetatag($key, $default='')
    {
        return static::GetConfigValue($key, $default);
    }
    
    public static function GetTitle()
    {
        $metatag = static::GetMetatag('title');
        $metatag = apply_filters('get_metatag_title', $metatag);
        return $metatag;
    }
    
    public static function GetDescription()
    {
        $metatag = static::GetMetatag('description');
        $metatag = apply_filters('get_metatag_description', $metatag);
        return $metatag;
    }
    
    public static function GetKeywords()
    {
        $metatag = static::GetMetatag('keywords');
        $metatag = apply_filters('get_metatag_keywords', $metatag);
        return $metatag;
    }
}
