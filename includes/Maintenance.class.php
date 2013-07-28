<?php

/** Include helpers files file */
require_once ICEBERG_DIR_HELPERS . 'maintenance.php';

/**
 * Maintenace
 * 
 * Manage Iceberg maintenance
 *  
 * @package Iceberg
 * @subpackage Maintenance
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0 
 */
class Maintenance extends ObjectConfig
{
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'maintenance_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'active' => false,
        'permanent' => true,
        'start' => 0,
        'stop' => 0,
        'allowed' => ''
    );
    
    public static function Save($config)
    {
        return static::SaveConfig($config);
    }
    
    
    public static function InMaintenance()
    {
        $in = static::GetConfigValue('active');
        if ($in)
        {
            $in = static::GetConfigValue('permanent');
            if ($in)
            {
                $now = time();
                if ($now<static::GetConfigValue('start') && $now>static::GetConfigValue('stop'))
                {
                    $in = false;
                }
            }
        }
        return $in;
    }
    
    public static function IsAllowed()
    {
        $ip = getIP();
        $ips = str_replace(',', ' ', static::GetConfigValue('allowed'));
        $ips = explode(' ', $ips);
        return in_array($ip, $ips);
    }
}




