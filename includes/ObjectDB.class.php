<?php

interface ObjectDBInterface
{
    public static function DB_Select($fields, $where=array(), $orderby='', $limit='');
    public static function DB_Insert($args);
    public static function DB_Update($id, $args);
    public static function DB_UpdateWhere($args, $where=array());
    public static function DB_InsertUpdate($args, $where=array());
    public static function DB_Delete($where, $apply_table = '');
}

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
    
    protected static $DB_LIMIT_PAGE_DEFAULT = 0;
    protected static $DB_LIMIT_ITEMS_DEFAULT = 30;
    
    public static function DB_Select($fields, $where=array(), $orderby=array(), $limit=array())
    {
        $result = false;
        $tables = static::DB_GetRelatedTables();
        $t = static::DB_GetTableName() . ' AS ' . $tables['self']['alias'];
        $primary_field = static::DB_GetPrimaryField();
        $select = static::DB_GenereateSelect($fields, $tables);
        $where = static::DB_GenerateWhere($where, $tables);
        $orderby = static::DB_GenereateOrderBy($orderby, $tables);
        $limit = static::DB_GenerateLimit($limit);
        db_select($t, $select, $where, $orderby, $limit);
        if (db_numrows() > 0)
        {
            $result = array();
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
        $tables = static::DB_GetRelatedTables();
        $t = static::DB_GetTableName() . ' AS ' . $tables['self']['alias'];
        $fields = array_map('db_encode', static::DB_FilterFields($args));
        $where = static::DB_GenerateWhere(array(
            static::DB_GetPrimaryField() => $id
        ), $tables);
        return db_update($t, $fields, $where);
    }
    
    public static function DB_UpdateWhere($args, $where=array())
    {
        $tables = static::DB_GetRelatedTables();
        $t = static::DB_GetTableName() . ' AS ' . $tables['self']['alias'];
        $fields = array_map('db_encode', static::DB_FilterFields($args));
        $where = static::DB_GenerateWhere($where, $tables);
        return db_update($t, $fields, $where);
    }
    
    public static function DB_InsertUpdate($args, $where=array())
    {
        $result = static::DB_Select(array(static::DB_GetPrimaryField()), $where);
        if (count($result) > 0)
        {
            return static::DB_UpdateWhere($args, $where);
        }
        else
        {
            return static::DB_Insert($args);
        }
        return false;
    }
    
    public static function DB_Delete($where, $apply_table = '')
    {
        $where = static::DB_GenerateWhere($where);
        return db_delete(static::DB_GetTableName(), $where, $apply_table);
    }
    
    
    protected static function DB_GetRelatedTables()
    {
        $tables = array(
            'self' => array(
                'table' => static::DB_GetTableName(),
                'alias' => 't0'
            )
        );
        return $tables;
    }
    
    protected static function DB_GenereateSelect($fields, $tables)
    {
        $t = $tables['self']['alias'];
        $primary_field = static::DB_GetPrimaryField();
        if (!in_array($primary_field, $fields))
        {
            array_push($fields, $primary_field);
        }
        $fields = static::DB_FilterFieldsOnValues($fields);
        return ' ' . $t . '.' . implode(', ' . $t . '.', $fields) . ' ';
    }
    
    protected static function DB_GenerateWhere($where, $tables)
    {
        $t = $tables['self']['alias'];
        $sql = ' WHERE 1';
        $where = static::DB_FilterFields($where);
        foreach ($where AS $k => $v)
        {
            if (is_array($v))
            {
                if (count($v) == 0)
                {
                    $v = null;
                }
                else if (count($v) == 1)
                {
                    $v = current($v);
                }
            }
            if (is_string($v) || is_numeric($v))
            {
                $sql .= " AND " . $t . '.' . mysql_escape($k) . "='" . mysql_escape($v) . "'";
            }
            else if (is_null($v))
            {
                $sql .= " AND " . $t . '.' . mysql_escape($k) . " IS NULL";
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
                    $sql .= " AND " . $t . '.' . mysql_escape($k) . " IN (" . implode(",", $v) . ")";
                }
                else
                {
                    $sql .= " AND " . $t . '.' . mysql_escape($k) . " IN ('" . implode("','", $v) . "')";
                }
            }
        }
        $sql .= ' ';
        return $sql;
    }
    
    protected static function DB_GenereateOrderBy($fields, $tables)
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
        $t = $tables['self']['alias'];
        $orderFields = static::DB_FilterFieldsOnValues($orderFields);
        foreach ($fields AS $k => $v)
        {
            $v = explode(' ', $v);
            if (in_array($v[0], $orderFields))
            {
                $fields[$k] = $t . '.' . $v[0] . ' ' . (isset($v[1]) ? $v[1] : 'ASC');
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
