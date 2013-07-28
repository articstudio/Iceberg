<?php

abstract class ThemeBackendAPI extends Theme
{
    
    /**
     * Theme frontend config key to load
     * @var string 
     */
    public static $THEME_FRONTEND_CONFIG_KEY = 'frontend';
    
    /**
     * Theme frontend config key to load
     * @var string 
     */
    public static $THEME_BACKEND_CONFIG_KEY = 'backend';
    
    public static function LoadFrontendThemeSettings()
    {
        $theme = static::GetConfigValue(static::$THEME_FRONTEND_CONFIG_KEY);
        if ($theme)
        {
            $file = ICEBERG_DIR_THEMES . $theme['dirname'] . DIRECTORY_SEPARATOR . static::$SETTINGS_FILE;
            if (is_file($file) && is_readable($file)) {
                require_once $file;
            }
        }
    }
    
    public static function LoadBackendThemeSettings()
    {
        $theme = static::GetConfigValue(static::$THEME_BACKEND_CONFIG_KEY);
        if ($theme)
        {
            $file = ICEBERG_DIR_ADMIN_THEMES . $theme['dirname'] . DIRECTORY_SEPARATOR . static::$SETTINGS_FILE;
            if (is_file($file) && is_readable($file)) {
                require_once $file;
            }
        }
    }
}
