<?php

$__MEMCACHED_DEBUG = array();
$__MEMCACHED_STATUS = 0;
$__MEMCACHED = null;

require MEMCACHED_DIR . 'helpers.php';

class IcebergMemcached extends ObjectConfig
{
    
    const STATUS_ACTIVE = 1;
    const STATUS_UNACTIVE = 0;
    const STATUS_ERROR = -1;
    
    /**
    * Configuration key
    * @var string
    */
    public static $CONFIG_KEY = 'memcached_config';

    /**
    * Configuration defaults
    * @var array
    */
    public static $CONFIG_DEFAULTS = array(
        'active' => true,
        'host' => 'localhost',
        'port' => 11211,
        'homepage' => -1,
        'configs' => array()
    );
    
    
    
    public static function Save($args=array())
    {
        $config = static::GetConfig();
        $config = array_merge($config, $args);
        $config = static::NormalizeConfig($config);
        return static::SaveConfig($config, Config::REPLICATE_ALL_LANGUAGES);
    }
    
    public static function Initialize()
    {
        global $__MEMCACHED, $__MEMCACHED_STATUS;
        $memcache = new Memcache;
        $active = static::GetConfigValue('active', false);
        $__MEMCACHED_STATUS = static::STATUS_UNACTIVE;
        if ($active)
        {
            $__MEMCACHED_STATUS = static::STATUS_ERROR;
            $host = static::GetConfigValue('host', 'localhost');
            $port = static::GetConfigValue('port', 11211);
            $done = $memcache->connect($host, $port);
            if ($done)
            {
                static::Log('Conection', 'SYSTEM', -1, 'ACTIVE');
                $__MEMCACHED_STATUS = static::STATUS_ACTIVE;
                $version = $memcache->getVersion();
                static::Log('Version', 'SYSTEM', -1, $version);
                $status = $memcache->getServerStatus ($host, $port);
                static::Log('Status', 'SYSTEM', -1, $status > 0 ? 'OK' : 'ERROR');
                $__MEMCACHED = $memcache;
                static::Active();
            }
            else
            {
                static::Log('Conection', 'SYSTEM', -1, 'ERROR');
            }
        }
        else
        {
            static::Log('Conection', 'SYSTEM', -1, 'UNACTIVE');
        }
    }
    
    
    
    public static function Active()
    {
        add_action('iceberg_loaded', 'iceberg_loaded_memcached', 10, 0);
    }
    
    
    public static function Log($sms, $rel, $id=-1, $value=null)
    {
        global $__MEMCACHED_DEBUG;
        if (!is_array($__MEMCACHED_DEBUG)) {$__MEMCACHED_DEBUG = array();}
        $value = is_string($value) ? $value : gettype($value);
        return array_push($__MEMCACHED_DEBUG, array($sms, $rel, $id, $value));
    }
    
    /**
     * Get MySQL log
     * @global Array $__MYSQL_QUERY_DEBUG
     * @return Array 
     */
    public static function GetLog()
    {
        global $__MEMCACHED_DEBUG;
        if (!is_array($__MEMCACHED_DEBUG)) {$__MEMCACHED_DEBUG = array();}
        return $__MEMCACHED_DEBUG;
    }
    
    /**
     * Print MySQL log 
     */
    public static function PrintLog()
    {
        $log = static::GetLog();
        $buffer = '';
        $n = count($log);
        $log_by_key = array();
        foreach ($log AS $activity)
        {
            $key = $activity[1] . ' / ' . $activity[2];
            //$buffer .= $activity[0] . ': ' . $key . ' => ' . $activity[3] . "\n";
            $log_by_key[$key] = isset($log_by_key[$key]) ? $log_by_key[$key] : array();
            array_push($log_by_key[$key], array($activity[0], $activity[3]));
        }
        //$buffer = '';
        foreach ($log_by_key As $key => $activities)
        {
            $buffer .= "\n" . '>> ' . $key . ':' . "\n";
            foreach ($activities AS $activity)
            {
                $buffer .= '    ' . $activity[0] . ' => ' . $activity[1] . "\n";
            }
        }
        $output = "\n<!--\n\n Memcached activity: " . $n . "\n" . $buffer . " -->";
        print $output;
    }
}
