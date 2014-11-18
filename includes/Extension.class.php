<?php

/** Include helpers request file */
require_once ICEBERG_DIR_HELPERS . 'extensions.php';

/**
 * Extension
 * 
 * Extensions management
 *  
 * @package Iceberg
 * @subpackage Extension
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 */
class Extension extends ObjectConfig
{
    
    /**
     * Configuration use language
     * @var boolean
     */
    public static $CONFIG_USE_LANGUAGE = false;
    
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'extensions_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array();
    
    /**
     * Extension defult data
     * @var array 
     */
    private static $EXTENSION_DEFAULTS = array(
        'dirname' => '',
        'name' => '',
        'description' => '',
        'url' => '',
        'author' => '',
        'version' => '',
        'active' => false
    );
    
    private static $SETTINGS_FILE = 'settings.php';
    private static $INFO_FILE = 'extension.php';
    private static $INSTALL_FILE = 'install.php';
    private static $UNINSTALL_FILE = 'uninstall.php';
    
    /**
     * Get actived extensions
     * @return array 
     */
    public static function GetExtensions()
    {
        return static::GetConfig();
    }
    
    /**
     * Load active extensions 
     */
    public static function LoadActives()
    {
        $extensions = self::GetExtensions();
        if (is_array($extensions) && !empty($extensions))
        {
            foreach ($extensions AS $dirname => $extension)
            {
                $settings_file = self::GetExtensionSettingsFile($dirname);
                if (is_file($settings_file) && is_readable($settings_file))
                {
                    require $settings_file;
                }
            }
        }
    }
    
    /**
     * Get extensions list
     * 
     * @return array 
     */
    public static function GetExtensionsList()
    {
        $active = self::GetExtensions();
        $extensions = array();
        $dir = ICEBERG_DIR_EXTENSIONS;
        $dh = opendir($dir);
        if ($dh !== false)
        {
            while (($file = readdir($dh)) !== false)
            {
                $path = $dir . $file;
                if (is_dir($path))
                {
                    $path_file = self::GetExtensionInfoFile($file);
                    if (is_file($path_file))
                    {
                        $extension = array();
                        require $path_file;
                        if (is_array($extension) && !empty($extension))
                        {
                            $extensions[$file] = self::NormalizeExtensionInfo($extension);
                            $extensions[$file]['dirname'] = $file;
                            $extensions[$file]['active'] = array_key_exists($file, $active);
                        }
                    }
                }
            }
            closedir($dh);
        }
        return $extensions;
    }
    
    /**
     * Active extension
     * 
     * @param string $dirname
     * @return boolean 
     */
    public static function Active($dirname)
    {
        $done = false;
        $dir = self::GetExtensionDir($dirname);
        if (is_dir($dir))
        {
            $info_file = self::GetExtensionInfoFile($dirname);
            if (is_file($info_file))
            {
                $extension = array();
                require $info_file;
                if (is_array($extension) && !empty($extension))
                {
                    $extensions = self::GetExtensions();
                    $extensions[$dirname] = self::NormalizeExtensionInfo($extension);
                    $settings_file = self::GetExtensionSettingsFile($dirname);
                    if (is_file($settings_file) && is_readable($settings_file)) {
                        require_once $settings_file;
                    }
                    $install_file = self::GetExtensionInstallFile($dirname);
                    if (is_file($install_file) && is_readable($install_file)) {
                        require_once $install_file;
                    }
                    $done = static::SaveConfig($extensions);
                }
            }
        }
        return $done;
    }
    
    /**
     * Unactive extension
     * 
     * @param string $dirname
     * @return boolean 
     */
    public static function Unactive($dirname)
    {
        $done = false;
        $extensions = self::GetExtensions();
        if (isset($extensions[$dirname]))
        {
            $uninstall_file = self::GetExtensionUninstallFile($dirname);
            if (is_file($uninstall_file) && is_readable($uninstall_file)) {
                require_once $uninstall_file;
            }
            $extensions[$dirname] = null;
            unset($extensions[$dirname]);
            $done = static::SaveConfig($extensions);
        }
        return $done;
    }
    
    /**
     * Get Extension settings file
     * 
     * @param string $dirname
     * @return string 
     */
    private static function GetExtensionSettingsFile($dirname)
    {
        return self::GetExtensionDir($dirname) . self::$SETTINGS_FILE;
    }
    
    /**
     * Get Extension info file
     * 
     * @param string $dirname
     * @return string 
     */
    private static function GetExtensionInfoFile($dirname)
    {
        return self::GetExtensionDir($dirname) . self::$INFO_FILE;
    }
    
    /**
     * Get Extension install file
     * 
     * @param string $dirname
     * @return string 
     */
    private static function GetExtensionInstallFile($dirname)
    {
        return self::GetExtensionDir($dirname) . self::$INSTALL_FILE;
    }
    
    /**
     * Get Extension uninstall file
     * 
     * @param string $dirname
     * @return string 
     */
    private static function GetExtensionUninstallFile($dirname)
    {
        return self::GetExtensionDir($dirname) . self::$UNINSTALL_FILE;
    }
    
    /**
     * Get Extension directory
     * 
     * @param string $dirname
     * @return string 
     */
    private static function GetExtensionDir($dirname)
    {
        return ICEBERG_DIR_EXTENSIONS . $dirname . DIRECTORY_SEPARATOR;
    }
    
    /**
     * Normalize extension info
     * 
     * @param array $info
     * @return array 
     */
    private static function NormalizeExtensionInfo($info)
    {
        return array_merge(self::$EXTENSION_DEFAULTS, $info);
    }
}
