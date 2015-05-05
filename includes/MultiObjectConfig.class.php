<?php


class MultiObjectConfig
{
    
    public static $CONFIG_KEY = 'multiobject_config';
    
    protected $id;
    protected $name;
    
    
    public function __construct($args=array())
    {
        $this->SetID(isset($args['id']) ? $args['id'] : -1);
        $this->SetName(isset($args['name']) ? $args['name'] : '');
    }
    
    public function SetID($id)
    {
        return $this->id = (int)$id;
    }
    
    public function GetID()
    {
        return $this->id;
    }
    
    public function SetName($name)
    {
        return $this->name = $name;
    }
    
    public function GetName()
    {
        return $this->name;
    }
    
    
    public static function GetCacheListKey($class, $lang=null)
    {
        $class = is_object($class) ? get_class($class) : $class;
        $key = $class . 'List';
        return $key;
    }
    
    public static function GetCacheListID($args, $lang=null)
    {
        $key = md5(json_encode($args));
        return $key;
    }
    
    public static function GetCacheList($args, $lang=null)
    {
        $found = false;
        $list = array();
        $id = static::GetCacheListID($args, $lang);
        $class = get_called_class();
        $rel = static::GetCacheListKey($class, $lang);
        $ids = IcebergCache::GetObject($id, $rel);
        if (is_array($ids))
        {
            $found = true;
            foreach ($ids AS $id)
            {
                $cache = IcebergCache::GetObject($id, $class);
                if ($cache === false)
                {
                    $found = false;
                    break;
                }
                else
                {
                    $list[$id] = $cache;
                }
            }
        }
        return $found ? $list : false;
    }
    
    public static function AddCacheList($args, $list, $lang=null)
    {
        $done = true;
        $class = null;
        foreach ($list AS $id => $object)
        {
            $done = IcebergCache::AddObject($id, $object);
            $class = get_class($object);
            if (!$done) { break; }
        }
        if ($done)
        {
            $id = static::GetCacheListID($args, $lang);
            $rel = static::GetCacheListKey($class, $lang);
            $ids = array_keys($list);
            return IcebergCache::AddObject($id, $ids, $rel);
        }
        return false;
    }
    
    
    public static function GetList($args=array(), $lang=null)
    {
        $obj = static::GetCacheList($args, $lang);
        if ($obj !== false)
        {
            return $obj;
        }
        $fields = static::GetSelectFields();
        $where = static::GetWhereFields($args);
        $orderby = static::GetOrderFields($args);
        $limit = static::GetLimitFields($args);
        $relations = static::GetRelationsFields($args);
        $arr = ConfigAll::DB_Select(
            $fields, 
            $where, 
            $orderby, 
            $limit, 
            $relations, 
            $lang
        );
        foreach ($arr AS $k => $v)
        {
            $arr[$k] = ConfigAll::DB_DecodeFieldValue($v->value);
            $arr[$k]->SetID($k);
        }
        reset($arr);
        static::AddCacheList($args, $arr, $lang);
        return $arr;
    }
    
    public static function Get($id=null)
    {
        $class = get_called_class();
        if (!is_null($id))
        {
            $obj = IcebergCache::GetObject($id, $class);
            if ($obj !== false)
            {
                return $obj;
            }
            $fields = static::GetSelectFields();
            $where = static::GetWhereFields(array('id' => $id));
            $arr = ConfigAll::DB_Select($fields, $where);
            if (count($arr) > 0)
            {
                $row = current($arr);
                $obj = ConfigAll::DB_DecodeFieldValue($row->value);
                $obj->SetID($id);
                IcebergCache::AddObject($id, $obj);
                return $obj;
            }
        }
        return new $class();
    }
    
    public static function Insert($object, $relations=array())
    {
        if (is_subclass_of($object, get_class()))
        {
            $class = get_class($object);
            $args = array(
                'name' => $class::$CONFIG_KEY,
                'value' => $object
            );
            $id = ConfigAll::DB_Insert($args, $relations, null);
            if ($id !== false)
            {
                IcebergCache::AddObject($id, $object);
            }
            return $id;
        }
        return false;
    }
    
    public static function Update($id, $object)
    {
        if (is_subclass_of($object, get_class()))
        {
            $args = array(
                'value' => $object
            );
            $where = static::GetWhereFields(array(ConfigAll::DB_GetPrimaryField() => $id));
            $done = ConfigAll::DB_UpdateWhere($args, $where, array(), null);
            if ($done !== false)
            {
                IcebergCache::AddObject($id, $object);
            }
            return $done;
        }
        return false;
    }
    
    public static function Remove($id)
    {
        $where = static::GetWhereFields(array(ConfigAll::DB_GetPrimaryField() => $id));
        $done = ConfigAll::DB_Delete($where, array(), null);
        if ($done !== false)
        {
            IcebergCache::RemoveObject($id, get_called_class());
        }
        return $done;
    }
    
    public static function ReOrder($from, $to)
    {
        $arr = static::GetList();
        //var_dump($arr);
        $arr = reOrderArray($arr, $from, $to);
        //var_dump($arr);
        foreach ($arr AS $k => $v)
        {
            $arr[$k] = $v->GetID();
        }
        return ConfigAll::DB_ReOrder($arr, array(), null);
    }
    
    protected static function GetSelectFields()
    {
        return array(
            'value'
        );
    }
    
    protected static function GetWhereFields($args)
    {
        $arr = array();
        if (isset($args['id']))
        {
            $arr['id'] = $args['id'];
        }
        if (isset($args['name']))
        {
            $arr['name'] = $args['name'];
        }
        else if (get_called_class() !== get_class())
        {
            $class = get_called_class();
            $arr['name'] = $class::$CONFIG_KEY;
        }
        return $arr;
    }
    
    protected static function GetOrderFields($args)
    {
        return array(
            ConfigAll::RELATION_KEY_DOMAIN=>DBRelation::GetCountField(),
            ConfigAll::DB_GetPrimaryField()
        );
    }
    
    protected static function GetLimitFields($args)
    {
        $arr = array();
        return $arr;
    }
    
    protected static function GetRelationsFields($args)
    {
        $arr = array();
        if (isset($args['user']))
        {
            $arr[ConfigAll::RELATION_KEY_USER] = $args['user'];
        }
        return $arr;
    }
    
    
    
    public static function Register($args, $relations=array())
    {
        $obj = new static($args);
        $relations = static::GetRelationsFields($relations);
        return static::Insert($obj, $relations);
    }
    
}
