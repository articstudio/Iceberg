<?php

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
}
