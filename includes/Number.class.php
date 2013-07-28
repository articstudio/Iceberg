<?php

/** Include helpers request file */
require_once ICEBERG_DIR_HELPERS . 'numbers.php';

/**
 * Time Zones
 * 
 * Time Zones management
 *  
 * @package Iceberg
 * @author Marc Mascort Bou
 * @version 1.0
 * @todo money_format
 */
class Number extends ObjectConfig
{
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'numbers_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'decimals' => 2,
        'decimals_point' => '.',
        'thousands_point' => ','
    );
    
    
    public static function Save($config)
    {
        return static::SaveConfig($config);
    }
    
}





