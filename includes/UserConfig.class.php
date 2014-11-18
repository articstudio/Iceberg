<?php

/** Include helpers request file */
require_once ICEBERG_DIR_HELPERS . 'userconfig.php';

/**
 * UserConfig
 * 
 * User configuration management
 *  
 * @package User
 * @subpackage Config
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 */
class UserConfig extends ObjectConfig
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
    public static $CONFIG_KEY = 'user_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'rootrole' => 0,
        'defaultrole' => 0,
    );
    
    public static function GetRootRole()
    {
        return static::GetConfigValue('rootrole', -1);
    }
    
    public static function GetDefaultRole()
    {
        return static::GetConfigValue('defaultrole', -1);
    }
    
    public static function SaveDefaultRole($default)
    {
        return static::SaveConfigValue('defaultrole', (int)$default);
    }
    
}
