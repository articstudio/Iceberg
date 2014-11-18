<?php

interface ObjectDBInterface
{}

/**
 * Database object
 * 
 * Manage database objects
 *  
 * @package Iceberg
 * @subpackage Database
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0 
 */
abstract class ObjectDB extends ObjectDBBase implements ObjectDBInterface
{
    
    const FIELD_NOT_EMPTY = '_NOT_EMPTY_';
    
    protected static $DB_LIMIT_PAGE_DEFAULT = 0;
    protected static $DB_LIMIT_ITEMS_DEFAULT = 30;
    
    public static function DB_Select($fields, $where=array(), $orderby=array(), $limit=array())
    {
        $result = array();
        $t = static::DB_GetTableName();
        $primary_field = static::DB_GetPrimaryField();
        $select = static::DB_GenereateSelect($fields);
        $where = static::DB_GenerateWhere($where);
        $orderby = static::DB_GenereateOrderBy($orderby);
        $limit = static::DB_GenerateLimit($limit);
        db_select($t, $select, $where, $orderby, $limit);
        if (db_numrows() > 0)
        {
            while ($row = db_next())
            {
                $result[$row->$primary_field] = $row;
            }
        }
        return $result;
    }
    
    public static function DB_Insert($args)
    {
        $t = static::DB_GetTableName();
        $args = static::DB_FilterFields($args);
        $fields = array_keys($args);
        $values = array_map('db_encode', array_values($args));
        $done = db_insert($t, $fields, $values);
        return $done ? db_getInsertId() : false;
    }
    
    public static function DB_Update($id, $args)
    {
        $t = static::DB_GetTableName();
        $fields = array_map('db_encode', static::DB_FilterFields($args));
        $where = static::DB_GenerateWhere(array(
            static::DB_GetPrimaryField() => $id
        ));
        return db_update($t, $fields, $where);
    }
    
    public static function DB_UpdateWhere($args, $where=array())
    {
        $t = static::DB_GetTableName();
        $fields = array_map('db_encode', static::DB_FilterFields($args));
        $where = static::DB_GenerateWhere($where);
        return db_update($t, $fields, $where);
    }
    
    public static function DB_InsertUpdate($args, $where=array())
    {
        $result = static::DB_Select(array(static::DB_GetPrimaryField()), $where);
        if (is_array($result) && count($result) > 0)
        {
            return static::DB_UpdateWhere($args, $where);
        }
        else
        {
            $args = array_merge($where, $args);
            return static::DB_Insert($args);
        }
        return false;
    }
    
    public static function DB_Delete($where)
    {
        $t = static::DB_GetTableName();
        $where = static::DB_GenerateWhere($where);
        return db_delete($t, $where);
    }
    
    
    
    protected static function DB_GenereateSelect($fields)
    {
        $primary_field = static::DB_GetPrimaryField();
        if (!in_array($primary_field, $fields))
        {
            array_push($fields, $primary_field);
        }
        $fields = static::DB_FilterFieldsOnValues($fields);
        return ' ' . implode(', ', $fields) . ' ';
    }
    
    protected static function DB_GenerateWhere($where)
    {
        $sql = ' WHERE 1 ';
        $where = static::DB_FilterFields($where);
        foreach ($where AS $k => $v)
        {
            if (is_array($v))
            {
                $n_v = count($v);
                $v = ($n_v === 0) ? false : ( ($n_v === 1) ? reset($v) : $v );
            }
            
            if (is_string($v) || is_numeric($v))
            {
                $sql .= " AND " . mysql_escape($k) . "='" . mysql_escape($v) . "'";
            }
            else if (is_null($v))
            {
                $sql .= " AND " . mysql_escape($k) . " IS NULL";
            }
            else if (is_array($v))
            {
                $ints = true;
                foreach ($v AS $vk => $vv)
                {
                    $ints = is_int($vv) ? $ints : false;
                    $v[$vk] = mysql_escape($vv);
                }
                if ($ints)
                {
                    $sql .= " AND " . mysql_escape($k) . " IN (" . implode(",", $v) . ")";
                }
                else
                {
                    $sql .= " AND " . mysql_escape($k) . " IN ('" . implode("','", $v) . "')";
                }
            }
        }
        $sql .= ' ';
        return $sql;
    }
    
    protected static function DB_GenereateOrderBy($fields)
    {
        if (empty($fields))
        {
            return '';
        }
        $orderFields = array();
        foreach ($fields AS $k => $v)
        {
            if (empty($v))
            {
                $fields[$k] = null;
                unset($fields[$k]);
            }
            else
            {
                $v = explode(' ', $v);
                $orderFields[$k] = $v[0];
            }
            
        }
        $orderFields = static::DB_FilterFieldsOnValues($orderFields);
        foreach ($fields AS $k => $v)
        {
            $v = explode(' ', $v);
            if (in_array($v[0], $orderFields))
            {
                $fields[$k] = $v[0] . ' ' . (isset($v[1]) ? $v[1] : 'ASC');
            }
            else
            {
                $fields[$k] = null;
                unset($fields[$k]);
            }
        }
        return empty($fields) ? '' : ' ' . implode(', ', $fields) . ' ';
    }
    
    protected static function DB_GenerateLimit($limit)
    {
        if (empty($limit))
        {
            return '';
        }
        $page = isset($limit[0]) ? (int)$limit[0] : static::$DB_LIMIT_PAGE_DEFAULT;
        $items = isset($limit[1]) ? (int)$limit[1] : static::$DB_LIMIT_ITEMS_DEFAULT;
        return ' ' . $page . ', ' . $items . ' ';
    }
}
