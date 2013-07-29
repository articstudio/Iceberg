<?php

abstract class ObjectTaxonomy
{
    
    /**
     * Configuration key
     * @var string
     */
    public static $TAXONOMY_KEY = 'object_taxonomy';
    
    protected $id = -1;
    
    public function GetID()
    {
        return $this->id;
    }
    
    public function SetID($id)
    {
        return $this->id = $id;
    }
    
    
    public static function Get($id=null)
    {
        $arr = Taxonomy::DB_Select(array('value'), array('name'=>static::$TAXONOMY_KEY, 'id'=>$id));
        if (count($arr) > 0)
        {
            $row = current($arr);
            $obj = $row->value;
            $obj->SetID($id);
            return $obj;
        }
        $class = get_called_class();
        return new $class();
    }
    
    public static function Insert($object)
    {
        if (is_a($object, get_called_class()))
        {
            $args = array(
                'name' => static::$TAXONOMY_KEY,
                'value' => serialize($object)
            );
            return Taxonomy::DB_Insert($args, array(), null);
            //return Taxonomy::DB_Insert($args, array(), Taxonomy::REPLICATE_CONFIG_ALL_LANGUAGES);
        }
        return false;
    }
    
    public static function Update($id, $object)
    {
        if (is_a($object, get_called_class()))
        {
            $args = array(
                'value' => serialize($object)
            );
            $where = array(
                'id' => $id
            );
            return Taxonomy::DB_UpdateWhere($args, $where, array(), null);
            //return Taxonomy::DB_UpdateWhere($args, $where, array(), Taxonomy::REPLICATE_CONFIG_ALL_LANGUAGES);
        }
        return false;
    }
    
    public static function GetList($relations=array(), $lang=null)
    {
        $arr = Taxonomy::DB_Select(array('value'), array('name'=>static::$TAXONOMY_KEY), array(DBRelation::GetCountField()), array(), $relations, $lang);
        foreach ($arr AS $k => $v)
        {
            $arr[$k] = Taxonomy::DB_DecodeFieldValue($v->value);
            $arr[$k]->SetID($k);
        }
        return $arr;
    }
    
    public static function Remove($id)
    {
        $args = array(
            'id' => $id,
            'name' => static::$TAXONOMY_KEY
        );
        return Taxonomy::DB_Delete($args, array(), null);
        //return Taxonomy::DB_Delete($args, array(), Taxonomy::REPLICATE_CONFIG_ALL_LANGUAGES);
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
        return Taxonomy::DB_ReOrder($arr, array(), null);
        //return Taxonomy::DB_ReOrder($arr, array(), Taxonomy::REPLICATE_CONFIG_ALL_LANGUAGES);
    }
    
}
