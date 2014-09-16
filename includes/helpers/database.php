<?php

function get_mysql_collates()
{
    return MySQL::GetCollates();
}

/**
 * Returns escaped string to use in DB query
 * 
 * @param string $string String to escape
 * @return string Escaped string
 */
function mysql_escape($string)
{
    return is_string($string) ? mysql_real_escape_string($string) : $string;
}

function db_get_query()
{
    global $__MYSQL_QUERY;
    return ($__MYSQL_QUERY !== null) ? $__MYSQL_QUERY : db_query();
    return $__MYSQL_QUERY;
}

function db_query($query=null, $id=null)
{
    global $__MYSQL_QUERY;
    $__MYSQL_QUERY = new Query($query, $id);
    return $__MYSQL_QUERY;
}

/**
 * 
 * @param type $table
 * @param type $select
 * @param type $where
 * @param type $orderby
 * @param type $limit
 * @param type $id
 * @return type
 */
function db_select($table, $select='*', $where='', $orderby='', $limit='', $id=null)
{
    $query = db_get_query();
    return $query->select($table, $select, $where, $orderby, $limit, $id);
}

function db_update($table, $update, $where='', $orderby='', $limit='')
{
    $query = db_get_query();
    return $query->update($table, $update, $where, $orderby, $limit);
}

function db_create_table($table, $fields, $index=array())
{
    $query = db_get_query();
    return $query->create_table($table, $fields, $index);
}

function db_drop_table($table)
{
    $query = db_get_query();
    return $query->drop_table($table);
}

function db_insert($table, $fields, $values)
{
    $query = db_get_query();
    return $query->insert($table, $fields, $values);
}

function db_delete($table, $where, $apply_table = '')
{
    $query = db_get_query();
    return $query->delete($table, $where, $apply_table);
}

function db_getInsertId($id=null)
{
    $query = db_get_query();
    return $query->getInsertId($id);
}

function db_numrows($id=null)
{
    $query = db_get_query();
    return $query->numrows($id);
}

function db_done($id=null)
{
    $query = db_get_query();
    return $query->done($id);
}

function db_next($method=null,$id=null)
{
    $query = db_get_query();
    return $query->next($method, $id);
}

function db_free()
{
    $query = db_get_query();
    return $query->free();
}

function db_reset()
{
    $query = db_get_query();
    return $query->reset();
}








