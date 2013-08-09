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
    
    public static function AddObject($id, $object)
    {
        global $__CACHE_OBJECTS;
        if (static::Objects())
        {
            $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
            if (is_object($object))
            {
                $class = get_class($object);
                $__CACHE_OBJECTS[$class] = (isset($__CACHE_OBJECTS[$class]) && is_array($__CACHE_OBJECTS[$class])) ? $__CACHE_OBJECTS[$class] : array();
                $__CACHE_OBJECTS[$class][$id] = $object;
                return true;
            }
        }
        return false;
    }
    
    public static function GetObject($id, $class)
    {
        global $__CACHE_OBJECTS;
        $found = false;
        if (static::Objects())
        {
            $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
            if (class_exists($class) && isset($__CACHE_OBJECTS[$class]))
            {
                $__CACHE_OBJECTS[$class] = is_array($__CACHE_OBJECTS[$class]) ? $__CACHE_OBJECTS[$class] : array();
                if (isset($__CACHE_OBJECTS[$class][$id]))
                {
                    $found = $__CACHE_OBJECTS[$class][$id];
                }
            }
        }
        if ($found === false)
        {
            $childs = static::GetSubClasses($class);
            foreach ($childs AS $child)
            {
                $found = static::GetObject($id, $child);
                if ($found !== false)
                {
                    break;
                }
            }
        }
        return $found;
    }
    
    public static function RemoveObject($id, $class)
    {
        global $__CACHE_OBJECTS;
        if (static::Objects())
        {
            $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
            if (class_exists($class))
            {
                $__CACHE_OBJECTS[$class] = is_array($__CACHE_OBJECTS[$class]) ? $__CACHE_OBJECTS[$class] : array();
                if (isset($__CACHE_OBJECTS[$class][$id]))
                {
                    $__CACHE_OBJECTS[$class][$id] = false;
                    unset($__CACHE_OBJECTS[$class][$id]);
                }
                return true;
            }
        }
        return false;
    }
    
    public static function RemoveAllObjects($class)
    {
        global $__CACHE_OBJECTS;
        if (static::Objects())
        {
            $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
            if (class_exists($class))
            {
                $__CACHE_OBJECTS[$class] = array();
                return true;
            }
        }
        return false;
    }
    
    
    protected static function GetSubClasses($parent)
    {
        $found = array();
        $classes = get_declared_classes();
        foreach ($classes AS $class)
        {
            if (is_subclass_of($class, $parent))
            {
                array_push($found, $class);
            }
        }
        return $found;
    }
}
