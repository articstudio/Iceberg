<?php

/** Include helpers request file */
require_once ICEBERG_DIR_HELPERS . 'pageconfig.php';

/**
 * Request
 * 
 * Request management
 *  
 * @package Page
 * @subpackage Config
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 */
class PageConfig extends ObjectConfig
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
    public static $CONFIG_KEY = 'page_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'pagetaxonomy' => 0,
        'pagegroup' => 0,
        'pagetype' => 0
    );
    
    public static function GetDefaultTaxonomy()
    {
        return static::GetConfigValue('pagetaxonomy', -1);
    }
    
    public static function GetDefaultGroup()
    {
        return static::GetConfigValue('pagegroup', -1);
    }
    
    public static function GetDefaultType()
    {
        return static::GetConfigValue('pagetype', -1);
    }
    
}
