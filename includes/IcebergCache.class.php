<?php

/** Include helpers request file */
require_once ICEBERG_DIR_HELPERS . 'cache.php';

abstract class IcebergCache extends ObjectConfig
{
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'cache_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'objects' => true
    );
    
    public static function Objects()
    {
        return static::GetConfigValue('objects', true);
    }
    
    public static function AddObject($id, $object, $rel=null)
    {
        global $__CACHE_OBJECTS;
        if (static::Objects())
        {
            $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
            if (is_object($object) || is_array($object))
            {
                $rel = is_null($rel) ? get_class($object) : $rel;
                $__CACHE_OBJECTS[$rel] = (isset($__CACHE_OBJECTS[$rel]) && is_array($__CACHE_OBJECTS[$rel])) ? $__CACHE_OBJECTS[$rel] : array();
                $__CACHE_OBJECTS[$rel][$id] = $object;
                static::Log('CACHE ADD', $rel, $id, $object);
                return true;
            }
        }
        static::Log('CACHE ADD FAIL', $rel, $id, $object);
        return false;
    }
    
    public static function GetObject($id, $rel)
    {
        global $__CACHE_OBJECTS;
        $found = false;
        if (static::Objects())
        {
            $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
            if (isset($__CACHE_OBJECTS[$rel]))
            {
                $__CACHE_OBJECTS[$rel] = is_array($__CACHE_OBJECTS[$rel]) ? $__CACHE_OBJECTS[$rel] : array();
                if (isset($__CACHE_OBJECTS[$rel][$id]))
                {
                    $found = $__CACHE_OBJECTS[$rel][$id];
                }
            }
        }
        if ($found === false && class_exists($rel))
        {
            $childs = static::GetSubClasses($rel);
            foreach ($childs AS $child)
            {
                $found = static::GetObject($id, $child);
                if ($found !== false)
                {
                    break;
                }
            }
        }
        static::Log('CACHE GET', $rel, $id, $found);
        return $found;
    }
    
    public static function RemoveObject($id, $rel)
    {
        global $__CACHE_OBJECTS;
        if (static::Objects())
        {
            $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
            $__CACHE_OBJECTS[$rel] = is_array($__CACHE_OBJECTS[$rel]) ? $__CACHE_OBJECTS[$rel] : array();
            if (isset($__CACHE_OBJECTS[$rel][$id]))
            {
                $__CACHE_OBJECTS[$rel][$id] = false;
                unset($__CACHE_OBJECTS[$rel][$id]);
            }
            static::Log('CACHE RMV', $rel, $id);
            return true;
        }
        static::Log('CACHE RMV FAIL', $rel, $id);
        return false;
    }
    
    public static function RemoveAllObjects($rel)
    {
        global $__CACHE_OBJECTS;
        if (static::Objects())
        {
            $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
            $__CACHE_OBJECTS[$rel] = array();
            static::Log('CACHE RMV ALL', $rel);
            return true;
        }
        static::Log('CACHE RMV ALL FAIL', $rel);
        return false;
    }
    
    
    protected static function GetSubClasses($parent)
    {
        $found = array();
        if (class_exists($parent))
        {
            $classes = get_declared_classes();
            foreach ($classes AS $class)
            {
                if (is_subclass_of($class, $parent))
                {
                    array_push($found, $class);
                }
            }
        }
        return $found;
    }
    
    public static function Log($sms, $rel, $id=-1, $value=null)
    {
        global $__CACHE_DEBUG;
        if (!is_array($__CACHE_DEBUG)) {$__CACHE_DEBUG = array();}
        $value = is_string($value) ? $value : gettype($value);
        return array_push($__CACHE_DEBUG, array($sms, $rel, $id, $value));
    }
    
    /**
     * Get MySQL log
     * @global Array $__MYSQL_QUERY_DEBUG
     * @return Array 
     */
    public static function GetLog()
    {
        global $__CACHE_DEBUG;
        if (!is_array($__CACHE_DEBUG)) {$__CACHE_DEBUG = array();}
        return $__CACHE_DEBUG;
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
        $output = "\n<!--\n\n Cache activity: " . $n . "\n" . $buffer . " -->";
        print $output;
    }
}
