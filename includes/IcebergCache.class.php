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
        'objects' => true,
        'memcached' => array(
            'environments' => array(
                'in_web' => false,
                'in_api' => false,
                'in_admin' => false
            ),
            'server' => 'localhost',
            'port' => 11211,
            'flag' => MEMCACHE_COMPRESSED,
            'expire' => 120
        )
    );
    
    public static function Objects()
    {
        return static::GetConfigValue('objects', false);
    }
    
    public static function Memcached()
    {
        $config = static::GetConfigValue('memcached');
        foreach ($config['environments'] AS $func_env => $actived)
        {
            if (is_callable($func_env))
            {
                $in_env = call_user_func($func_env);
                if ($in_env)
                {
                    return $actived;
                }
            }
        }
        return false;
    }
    
    public static function AddObject($id, $object, $rel=null)
    {
        global $__CACHE_OBJECTS;
        $done = false;
        if (static::Objects())
        {
            $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
            if (is_object($object) || is_array($object))
            {
                $rel = is_null($rel) ? get_class($object) : $rel;
                $__CACHE_OBJECTS[$rel] = (isset($__CACHE_OBJECTS[$rel]) && is_array($__CACHE_OBJECTS[$rel])) ? $__CACHE_OBJECTS[$rel] : array();
                $__CACHE_OBJECTS[$rel][$id] = $object;
                $done = true;
            }
        }
        action_event('iceberg_cache_action', 'ADD', $done, $rel, $id, $object);
        return $done;
    }
    
    public static function GetObject($id, $rel)
    {
        /*if (intval($id) == $id)
        {
            $abs = abs(intval($id));
        }*/
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
        action_event('iceberg_cache_action', 'GET', (bool)$found, $rel, $id, $found);
        return $found;
    }
    
    public static function RemoveObject($id, $rel)
    {
        global $__CACHE_OBJECTS;
        $done = false;
        if (static::Objects())
        {
            $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
            $__CACHE_OBJECTS[$rel] = (isset($__CACHE_OBJECTS[$rel]) && is_array($__CACHE_OBJECTS[$rel])) ? $__CACHE_OBJECTS[$rel] : array();
            if (isset($__CACHE_OBJECTS[$rel][$id]))
            {
                $__CACHE_OBJECTS[$rel][$id] = false;
                unset($__CACHE_OBJECTS[$rel][$id]);
            }
            $done = true;
        }
        action_event('iceberg_cache_action', 'GET', $done, $rel, $id);
        return $done;
    }
    
    public static function RemoveAllObjects($rel)
    {
        global $__CACHE_OBJECTS;
        $done = false;
        if (static::Objects())
        {
            $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
            $__CACHE_OBJECTS[$rel] = array();
            static::Log('CACHE RMV ALL', $rel);
            $done = true;
        }
        action_event('iceberg_cache_action', 'RMVALL', $done, $rel);
        return $done;
    }
    
    public static function GetRequestKey()
    {
        $url = Request::GetFullUrl();
        $request = json_encode($_REQUEST);
        $key_request = $url . ' :: ' . $request;
        $key = 'REQUEST_' . md5($key_request);
        return array($key, $key_request);
    }
    
    public static function LoadRequest()
    {
        global $__CACHE_OBJECTS;
        if (static::Memcached())
        {
            $config = static::GetConfigValue('memcached');
            $memcached = new Memcache();
            try
            {
                $done = $memcached->connect($config['server'], $config['port']);
                if ($done)
                {
                    action_event('iceberg_cache_action_memcached', 'CONNECTION', true);
                    action_event('iceberg_cache_action_memcached', 'VERSION', true, $memcached->getVersion());

                    if (empty($_POST))
                    {
                        $key = static::GetRequestKey();
                        $found = $memcached->get($key[0]);
                        action_event('iceberg_cache_action_memcached', 'REQUEST', (bool)$found, $key[1]);

                        $__CACHE_OBJECTS = is_array($__CACHE_OBJECTS) ? $__CACHE_OBJECTS : array();
                        $__CACHE_OBJECTS[$key[0]] = $found;
                    }
                    else
                    {
                        action_event('iceberg_cache_action_memcached', 'REQUEST NOT CACHEABLE', true);
                    }


                    $memcached->close();
                }
                else
                {
                    //static::SaveConfigValue('memcached', false, Config::REPLICATE_ALL_LANGUAGES);
                    action_event('iceberg_cache_action_memcached', 'CONNECTION', false);
                }
            }
            catch (Exception $e)
            {
                //static::SaveConfigValue('memcached', false, Config::REPLICATE_ALL_LANGUAGES);
                action_event('iceberg_cache_action_memcached', 'CONNECTION', false);
            }
            
        }
    }
    
    public static function EnvironmentContentCache_Filter($args)
    {
        global $__CACHE_OBJECTS;
        list($content) = $args;
        if (static::Memcached())
        {
            $key = static::GetRequestKey();
            if (is_array($__CACHE_OBJECTS) && isset($__CACHE_OBJECTS[$key[0]]) && is_string($__CACHE_OBJECTS[$key[0]]))
            {
                $content = $__CACHE_OBJECTS[$key[0]];
            }
        }
        return array($content);
    }
    
    public static function EnvironmentContentCache($args)
    {
        global $__CACHE_OBJECTS;
        list($content) = $args;
        if (static::Memcached())
        {
            $key = static::GetRequestKey();
            if (!is_array($__CACHE_OBJECTS) || !isset($__CACHE_OBJECTS[$key[0]]) || !is_string($__CACHE_OBJECTS[$key[0]]))
            {
                $config = static::GetConfigValue('memcached');
                $memcached = new Memcache();
                $done = $memcached->connect($config['server'], $config['port']);
                if ($done)
                {
                    $content = remove_html_comments($content);
                    $done = $memcached->set($key[0], $content, $config['flag'], $config['expire']);
                    $memcached->close();
                }
                action_event('iceberg_cache_action_memcached', 'SET', $done, $key[1]);
            }
        }
        return array($content);
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
    
}


function iceberg_load_request_cache($args)
{
    IcebergCache::LoadRequest();
    return $args;
}
add_action('iceberg_load_request', 'iceberg_load_request_cache', 10);

function filter_iceberg_environment_content_cache($args)
{
    return IcebergCache::EnvironmentContentCache_Filter($args);
}
add_action('filter_iceberg_environment_content', 'filter_iceberg_environment_content_cache', 10, 1);

function iceberg_environment_content_cache($args)
{
    return IcebergCache::EnvironmentContentCache($args);
}
add_action('iceberg_environment_content', 'iceberg_environment_content_cache', 10, 1);