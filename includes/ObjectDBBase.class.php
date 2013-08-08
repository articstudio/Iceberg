<?php

/** Include helpers request file */
require_once ICEBERG_DIR_HELPERS . 'objectDBBase.php';

interface ObjectDBBaseInterface
{
    public static function DB_GetTableName();
    public static function DB_GetPrimaryField();
    public static function DB_GetFields();
    public static function DB_GetIndexFields();
    public static function DB_EncodeFieldValue($value);
    public static function DB_DecodeFieldValue($value=null);
    public static function DB_CreateTable();
    /*
    public static function DB_Select($fields, $where=array(), $orderby='', $limit='');
    public static function DB_Insert($args);
    public static function DB_Update($id, $args);
    public static function DB_UpdateWhere($args, $where=array());
    public static function DB_InsertUpdate($args, $where=array());
    */
}

/**
 * Database object base
 * 
 * Manage database objects
 *  
 * @package Iceberg
 * @subpackage Database
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0 
 */
abstract class ObjectDBBase implements ObjectDBBaseInterface
{
    /**
     * DB field default struct
     * @var array 
     */
    protected static $DB_FIELD_DEFAULT = array(
        'name' => '',
        'type' => 'INT',
        'length' => '0',
        'flags' => array(),
        'index' => false
    );
    
    /**
     * DB basic fields
     * @var array 
     */
    protected static $DB_BASIC_FIELDS = array(
        'id' => array(
            'name' => 'ID',
            'type' => 'BIGINT',
            'length' => '20',
            'flags' => array(
                'NOT NULL',
                'AUTO_INCREMENT',
                'PRIMARY KEY'
            ),
            'index' => false
        )
    );
    
    /**
     * DB primary field
     * @var string 
     */
    protected static $DB_PRIMARY_FIELD = 'id';
    
    /**
     * DB table name
     * @var string
     */
    protected static $DB_TABLE = 'ICEBERG_DB_TABLE';
    
    /**
     * List of fields
     * @var array
     */
    protected static $DB_FIELDS = array();
    
    
    public static function DB_GetTableName()
    {
        $table = '';
        if (defined('ICEBERG_INSTALL') && !defined('ICEBERG_REINSTALL'))
        {
            if (defined('ICEBERG_DB_PREFIX'))
            {
                $table .= constant('ICEBERG_DB_PREFIX');
            }
        }
        $table .= defined(static::$DB_TABLE) ? constant(static::$DB_TABLE) : static::$DB_TABLE;
        return $table;
    }
    
    public static function DB_GetIndexFields()
    {
        $fields = static::DB_GetFields();
        foreach ($fields AS $k => $v)
        {
            if (!$v['index'])
            {
                $fields[$k] = null;
                unset($fields[$k]);
            }
        }
        return $fields;
    }
    
    public static function DB_GetPrimaryField()
    {
        return static::$DB_PRIMARY_FIELD;
    }
    
    public static function DB_GetFields()
    {
        $fields = array_merge(static::$DB_BASIC_FIELDS, static::$DB_FIELDS);
        $fields = static::DB_NormalizeFields($fields);
        return $fields;
    }
    
    public static function DB_EncodeFieldValue($value)
    {
        if (is_object($value))
        {
            return serialize($value);
        }
        else if (is_array($value))
        {
            return json_encode($value);
        }
        return $value;
    }
    
    public static function DB_DecodeFieldValue($value=null)
    {
        $data = @unserialize($value);
        if($data !== false || $value === 'b:0;')
        {
            return $data;
        }
        $data = @json_decode($value, true);
        if (json_last_error() == JSON_ERROR_NONE)
        {
            return $data;
        }
        return $value;
    }
    
    public static function DB_CreateTable()
    {
        $table = static::DB_GetTableName();
        $fields = static::DB_GetFields();
        $index = array();
        foreach ($fields AS $k => $v)
        {
            $field = '`' . $k . '` ' . $v['type'] . (is_null($v['length']) ? ' ' : '( ' . $v['length'] . ' ) ') . implode(' ', $v['flags']);
            $fields[$k] = $field;
            if ($v['index'])
            {
                array_push($index, $k);
            }
        }
        return db_create_table(static::DB_GetTableName(), $fields, $index);
    }
    
    public static function DB_DropTable()
    {
        return db_drop_table(static::DB_GetTableName());
    }
    
    protected static function DB_NormalizeField($field)
    {
        $field = !is_array($field) ? array() : $field;
        $field = array_merge(static::$DB_FIELD_DEFAULT, $field);
        return $field;
    }
    
    protected static function DB_NormalizeFields($fields)
    {
        $fields = !is_array($fields) ? array() : $fields;
        foreach ($fields AS $k => $v)
        {
            $fields[$k] = static::DB_NormalizeField($v);
        }
        return $fields;
    }
    
    protected static function DB_FilterFields($fields)
    {
        $o_fields = static::DB_GetFields();
        foreach ($fields AS $k => $v)
        {
            if (!isset($o_fields[$k]))
            {
                $fields[$k] = null;
                unset($fields[$k]);
            }
        }
        return $fields;
    }
    
    protected static function DB_FilterFieldsOnValues($fields)
    {
        $o_fields = static::DB_GetFields();
        foreach ($fields AS $k => $v)
        {
            if ((!is_string($v) && !is_numeric($v)) || !isset($o_fields[$v]))
            {
                $fields[$k] = null;
                unset($fields[$k]);
            }
        }
        return $fields;
    }
    
    protected static function DB_IsField($field)
    {
        $o_fields = static::DB_GetFields();
        return isset($o_fields[$field]);
    }
}
