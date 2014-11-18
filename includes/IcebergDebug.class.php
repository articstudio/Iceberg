<?php

class IcebergDebug extends IcebergSingleton
{
    const COLLECTION_MYSQL = 'MySQL';
    const COLLECTION_CACHE = 'CACHE';
    const COLLECTION_CACHE_MEMCACHED = 'CACHE_MEMCACHED';
    
    protected $isDebug = false;
    protected $initialized = false;
    protected $collections = array();
    
    public function isInitialized()
    {
        return $this->initialized;
    }
    
    public function isDebug()
    {
        return $this->isDebug;
    }
    
    public static function Initialize($debug=false)
    {
        $obj = static::getInstance();
        if (!$obj->isInitialized())
        {
            $obj->isDebug = $debug;
            if ($debug)
            {
                error_reporting(-1);
                ini_set('display_errors', 1);
                //set_time_limit(0);
            }
            else
            {
                error_reporting(0);
                ini_set('display_errors', 0);
            }
            
            add_action('mysql_query_send', 'mysql_query_send_debug', 5, 5);
            add_action('iceberg_cache_action', 'iceberg_cache_action_debug', 5, 5);
            add_action('iceberg_cache_action_memcached', 'iceberg_cache_action_memcached_debug', 5, 3);
            
            return true;
        }
        return false;
    }
    
    public static function Log($collection, $value)
    {
        $obj = static::getInstance();
        if ($obj->isDebug())
        {
            if (!isset($obj->collections[$collection]) || !is_array($obj->collections[$collection]))
            {
                $obj->collections[$collection] = array();
            }
            return array_push($obj->collections[$collection], $value);
        }
        return false;
    }
    
    public static function GetLog($collection)
    {
        $obj = static::getInstance();
        return (!isset($obj->collections[$collection]) || !is_array($obj->collections[$collection])) ? array() : $obj->collections[$collection];
    }
    
    public static function GetLogMySQL()
    {
        $log = static::GetLog(static::COLLECTION_MYSQL);
        $time_total = 0;
        $buffer = '';
        $n = count($log);
        foreach ($log AS $query)
        {
            $time_total += $query[4];
            $buffer .= 'Query time: ' . $query[4] . " seconds\n";
            $buffer .= 'Query results: ' . $query[1] . "\n";
            $buffer .= 'Query: ' . $query[3] . "\n\n";
        }
        $average = $n>0 ? $time_total / $n : 0;
        $buffer = "MySQL time: " . $time_total . " seconds\n MySQL time average: " . $average . " seconds\n MySQL queries: " . $n . "\n\n\n" . $buffer;
        return $buffer;
    }
    
    public static function GetLogCache()
    {
        $log = static::GetLog(static::COLLECTION_CACHE);
        $buffer = '';
        $n = count($log);
        $log_by_key = array();
        
        foreach ($log AS $activity) //(!$done ? ' FAIL' : '') . 
        {
            $key = $activity[2] . ' / ' . $activity[3];
            //$buffer .= $activity[0] . ': ' . $key . ' => ' . $activity[3] . "\n";
            $log_by_key[$key] = isset($log_by_key[$key]) ? $log_by_key[$key] : array();
            array_push($log_by_key[$key], array($activity[0] . (!$activity[1] ? ' FAIL' : ''), $activity[4]));
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
        $output = "Cache activity: " . $n . "\n" . $buffer;
        return $output;
    }
    
    public static function GetLogCacheMemcached()
    {
        $log = static::GetLog(static::COLLECTION_CACHE_MEMCACHED);
        $buffer = '';
        foreach ($log As $key => $activity)
        {
            $buffer .= $activity[0] . (!$activity[1] ? ' FAIL' : '') . (isset($activity[2]) ? ' => ' . $activity[2] : '') . "\n";
        }
        $output = "Memcached activity:\n\n" . $buffer;
        return $output;
    }
    
    public static function PrintLog($time=0)
    {
        $obj = static::getInstance();
        if ($obj->isDebug() && (Install::IsInstallationProcess() || User::HasCapability('debug') || true))
        {
            if ($time > 0)
            {
                $generated = 'Page generated in ' . $time . ' seconds' . "\n";
                static::PrintHTMLComment('TIME', $generated);
            }
            static::PrintLogMySQL();
            static::PrintLogCache();
            //static::PrintLogCacheMemcached();
            do_action('iceberg_debug_print_log');
        }
    }
    
    public static function PrintLogMySQL()
    {
        static::PrintHTMLComment('MySQL', static::GetLogMySQL());
    }
    
    public static function PrintLogCache()
    {
        static::PrintHTMLComment('CACHE', static::GetLogCache());
    }
    
    public static function PrintLogCacheMemcached()
    {
        static::PrintHTMLComment('MEMCACHED', static::GetLogCacheMemcached());
    }
    
    public static function PrintHTMLComment($name, $content)
    {
        $html = "\n<!--#LOG:" . '%1$s' . "#--\n\n " . '%2$s' . " \n/#LOG:" . '%1$s' . "#-->\n";
        printf($html, $name, $content);
    }
}

function mysql_query_send_debug($insertId, $numrows, $result, $query, $time)
{
    //var_dump($args);
    IcebergDebug::Log(IcebergDebug::COLLECTION_MYSQL, array($insertId, $numrows, $result, $query, $time));
}

function iceberg_cache_action_debug($action, $done, $rel, $id, $object)
{
    $object = is_string($object) ? $object : gettype($object);
    IcebergDebug::Log(IcebergDebug::COLLECTION_CACHE, array($action, $done, $rel, $id, $object));
}

function iceberg_cache_action_memcached_debug($action, $done, $value)
{
    IcebergDebug::Log(IcebergDebug::COLLECTION_CACHE_MEMCACHED, array($action, $done, $value));
}


