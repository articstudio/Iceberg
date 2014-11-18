<?php

require_once ICEBERG_DIR_ADMIN_INCLUDES . 'TableBackend.class.php';

abstract class ThemeBackendAPI extends Theme
{
    
    
    public static function LoadFrontendThemeSettings()
    {
        $theme = static::GetFrontendTheme();
        if ($theme)
        {
            $file = ICEBERG_DIR_THEMES . $theme . DIRECTORY_SEPARATOR . static::$SETTINGS_FILE;
            if (is_file($file) && is_readable($file)) {
                require_once $file;
            }
        }
    }
    
    public static function LoadBackendThemeSettings()
    {
        $theme = static::GetBackendTheme();
        if ($theme)
        {
            $file = ICEBERG_DIR_ADMIN_THEMES . $theme . DIRECTORY_SEPARATOR . static::$SETTINGS_FILE;
            if (is_file($file) && is_readable($file)) {
                require_once $file;
            }
        }
    }
}
