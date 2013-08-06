<?php

/**
 * Relational database object
 * 
 * Manage relational database objects
 *  
 * @package Iceberg
 * @subpackage Database
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0 
 */
abstract class ObjectDBRelations extends ObjectDB
{
    
    /**
     * Replicate content in all languages
     */
    const REPLICATE_CONFIG_ALL_LANGUAGES = '_ALL_LLANGUAGES_';
    
    const DB_ALL_LANGUAGE_FUNCTION = 'get_locales';
    
    const DB_LANGUAGE_FUNCTION = 'get_lang';
    
    /**
     * Parent relation default struct
     * @var type 
     */
    protected static $DB_PARENT_DEFAULT = array(
        'object' => 'ObjectName',
        'force' => false,
        'function' => '',
        'language' => false
    );
    
    /**
     * Parents relation
     * @var array 
     */
    protected static $DB_PARENTS = array();
    
    protected static function DB_NormalizeParent($parent)
    {
        return array_merge(static::$DB_PARENT_DEFAULT, (!is_array($parent) ? array() : $parent));
    }
    
    protected static function NormalizeParents($parents)
    {
        $parents = !is_array($parents) ? array() : $parents;
        foreach ($parents AS $k => $v)
        {
            $parents[$k] = static::DB_NormalizeParent($v);
        }
        return $parents;
    }
    
    public static function GetParents()
    {
        return static::NormalizeParents(static::$DB_PARENTS);
    }
    
    public static function IsParentObject($parentObj)
    {
        $parents = static::GetParents();
        $found = false;
        foreach ($parents AS $k => $parent)
        {
            $found = ($parent['object'] === $parentObj) ? $k : false;
            if ($found)
                break;
        }
        return $found;
    }
    
    public static function IsParentRelation($parentRel)
    {
        $parents = static::GetParents();
        return isset($parents[$parentRel]) ? $parents[$parentRel] : false;
    }
    
    public static function GetParentObjectsID($id, $parentRel)
    {
        $parent = static::IsParentRelation($parentRel);
        $result = array();
        if ($parent)
        {
            $t_relation_parent_field = DBRelation::GetParentField();
            $t_relation_child_field = DBRelation::GetChildField();
            $t_relation_name_field = DBRelation::GetNameField();
            $fields = array(
                $t_relation_parent_field
            );
            $where = array(
                $t_relation_child_field => $id,
                $t_relation_name_field => $parentRel
            );
            $buffer = DBRelation::DB_Select($fields, $where);
            if ($buffer)
            {
                foreach ($buffer AS $v)
                {
                    array_push($result, $v->$t_relation_parent_field);
                }
            }
        }
        $result = array_unique($result, SORT_NUMERIC);
        return empty($result) ? false : $result;
    }
    
    public static function DB_Select($fields, $where=array(), $orderby=array(), $limit=array(), $relations=array(), $lang=null)
    {
        $parents = static::GetParents();
        if (empty($parents))
        {
            return parent::DB_Select($fields, $where, $orderby, $limit);
        }
        else
        {
            $result = array();
            $relations = static::DB_FilterParentsRelations($relations, $fields);
            $tables = static::DB_GetRelatedTables($relations, $fields);
            
            $t = static::DB_GetTableName();
            $t_field = static::DB_GetPrimaryField();
            $sql = "SELECT " . static::DB_GenereateSelect($fields, $tables);
            $sql .= " FROM " . $tables['self']['table'] . ' AS ' . $tables['self']['alias'];
            $sql .= " " . static::DB_GenereateJoins($relations, $tables, $lang);
            $sql .= " " . static::DB_GenerateWhere($where, $relations, $tables);
            $sql .= ' GROUP BY ' . mysql_escape($tables['self']['alias']) . '.' . mysql_escape($t_field);
            $orderby = static::DB_GenereateOrderBy($orderby, $tables);
            $limit = static::DB_GenerateLimit($limit);
            $sql .= $orderby!=='' ? ' ORDER BY ' . mysql_escape($orderby) : '';
            $sql .= $limit!=='' ? ' LIMIT ' . mysql_escape($limit) : '';
            db_query($sql);
            if (db_numrows() > 0)
            {
                while ($row = db_next())
                {
                    $result[$row->id] = $row;
                }
            }
            return $result;
        }
        return false;
    }
    
    public static function DB_Insert($args, $relations=array(), $lang=null)
    {
        $done = parent::DB_Insert($args);
        if ($done)
        {
            $relations = static::DB_FilterParentsRelations($relations);
            $parents = static::GetParents();
            $t_relation_parent_field = DBRelation::GetParentField();
            $t_relation_child_field = DBRelation::GetChildField();
            $t_relation_name_field = DBRelation::GetNameField();
            $t_relation_language_field = DBRelation::GetLanguageField();
            foreach ($relations AS $rel => $pid)
            {
                if ($pid !== null && $pid !== false)
                {
                    $pid = !is_array($pid) ? array($pid) : $pid;
                    foreach ($pid AS $id)
                    {
                        $r_args = array(
                            $t_relation_parent_field => $id,
                            $t_relation_child_field => $done,
                            $t_relation_name_field => $rel
                        );
                        if ($lang == static::REPLICATE_CONFIG_ALL_LANGUAGES)
                        {
                            $langs  = call_user_func(static::DB_ALL_LANGUAGE_FUNCTION);
                            foreach ($langs AS $locale)
                            {
                                $buffer = array_merge($r_args, array($t_relation_language_field=>$locale));
                                DBRelation::DB_Insert($buffer);
                            }
                        }
                        else
                        {
                            if (isset($parents[$rel]) && $parents[$rel]['language'])
                            {
                                $r_args[$t_relation_language_field] = is_null($lang) ? call_user_func(static::DB_LANGUAGE_FUNCTION) : $lang;
                            }
                            DBRelation::DB_Insert($r_args);
                        }
                    }
                }
            }
        }
        return $done;
    }
    
    public static function DB_UpdateWhere($args, $where=array(), $relations=array(), $lang=null)
    {
        $primary_field = static::DB_GetPrimaryField();
        $items = static::DB_Select(array($primary_field), $where, array(), array(), $relations, $lang);
        if (count($items) > 0)
        {
            $ids = array_keys($items);
            return static::DB_Update($ids, $args);
        }
        return true;
    }
    
    public static function DB_InsertUpdate($args, $where = array(), $relations=array(), $lang=null)
    {
        $primary_field = static::DB_GetPrimaryField();
        $items = static::DB_Select(array($primary_field), $where, array(), array(), $relations, $lang);
        if (count($items) > 0)
        {
            $ids = array_keys($items);
            return static::DB_Update($ids, $args);
        }
        else
        {
            $args = array_merge($where, $args);
            return static::DB_Insert($args, $relations, $lang);
        }
        return false;
    }
    
    public static function DB_Delete($where, $relations=array(), $lang=null)
    {
        $primary_field = static::DB_GetPrimaryField();
        $items = static::DB_Select(array($primary_field), $where, array(), array(), $relations, $lang);
        if (count($items) > 0)
        {
            $ids = array_keys($items);
            $done = parent::DB_Delete(array($primary_field=>$ids));
            if ($done)
            {
                $relations = static::DB_FilterParentsRelations($relations);
                $parents = static::GetParents();
                $t_relation_parent_field = DBRelation::GetParentField();
                $t_relation_child_field = DBRelation::GetChildField();
                $t_relation_name_field = DBRelation::GetNameField();
                $t_relation_language_field = DBRelation::GetLanguageField();
                foreach ($relations AS $rel => $pid)
                {
                    if ($pid !== null && $pid !== false)
                    {
                        $pid = !is_array($pid) ? array($pid) : $pid;
                        foreach ($pid AS $id)
                        {
                            $r_args = array(
                                $t_relation_parent_field => $id,
                                $t_relation_child_field => $ids,
                                $t_relation_name_field => $rel
                            );
                            if ($lang == static::REPLICATE_CONFIG_ALL_LANGUAGES)
                            {
                                $langs  = call_user_func(DBRelation::$DB_ALL_LANGUAGE_FUNCTION);
                                foreach ($langs AS $locale)
                                {
                                    $buffer = array_merge($r_args, array($t_relation_language_field=>$locale));
                                    DBRelation::DB_Delete($buffer);
                                }
                            }
                            else
                            {
                                if (isset($parents[$rel]) && $parents[$rel]['language'])
                                {
                                    $r_args[$t_relation_language_field] = is_null($lang) ? call_user_func(static::DB_LANGUAGE_FUNCTION) : $lang;
                                }
                                DBRelation::DB_Insert($r_args);
                            }
                        }
                    }
                }
            }
        }
        return $done;
    }
    
    public static function DB_InsertChild($childObj, $args, $id=null)
    {
        $parentObj = get_called_class();
        $rel = $childObj::IsParentObject($parentObj);
        if ($rel)
        {
            $relations = is_null($id) ? array() : array($rel => $id);
            return $childObj::DB_Insert($args, $relations);
        }
        return false;
    }
    
    public static function DB_SelectChild($childObj, $id=null, $fields=array(), $where=array(), $orderby=array(), $limit=array())
    {
        $parentObj = get_called_class();
        $rel = $childObj::IsParentObject($parentObj);
        if ($rel)
        {
            $relations = is_null($id) ? array() : array($rel => $id);
            return $childObj::DB_Select($fields, $where, $orderby, $limit, $relations, null);
        }
        return array();
    }
    
    public static function DB_InsertUpdateChild($childObj, $args, $where=array(), $id=null)
    {
        $parentObj = get_called_class();
        $rel = $childObj::IsParentObject($parentObj);
        if ($rel)
        {
            $relations = is_null($id) ? array() : array($rel => $id);
            return $childObj::DB_InsertUpdate($args, $where, $relations);
        }
        return false;
    }
    
    public static function DB_ReOrder($order, $relations=array(), $lang=null)
    {
        $done = false;
        $relations = static::DB_FilterParentsRelations($relations);
        $parents = static::GetParents();
        
        $t_relation_parent_field = DBRelation::GetParentField();
        $t_relation_child_field = DBRelation::GetChildField();
        $t_relation_name_field = DBRelation::GetNameField();
        $t_relation_language_field = DBRelation::GetLanguageField();
        $t_relation_count_field = DBRelation::GetCountField();
        foreach ($order AS $count => $cid)
        {
            foreach ($relations AS $rel => $pid)
            {
                if ($pid !== null && $pid !== false)
                {
                    $pid = !is_array($pid) ? array($pid) : $pid;
                    foreach ($pid AS $id)
                    {
                        $r_args = array(
                            $t_relation_parent_field => $id,
                            $t_relation_child_field => $cid,
                            $t_relation_name_field => $rel
                        );
                        if ($lang == static::REPLICATE_CONFIG_ALL_LANGUAGES)
                        {
                            $langs  = call_user_func(DBRelation::$DB_ALL_LANGUAGE_FUNCTION);
                            foreach ($langs AS $locale)
                            {
                                $buffer = array_merge($r_args, array($t_relation_language_field=>$locale));
                                $done = DBRelation::DB_UpdateWhere(array($t_relation_count_field => $count), $buffer);
                            }
                        }
                        else
                        {
                            if (isset($parents[$rel]) && $parents[$rel]['language'])
                            {
                                $r_args[$t_relation_language_field] = is_null($lang) ? call_user_func(static::DB_LANGUAGE_FUNCTION) : $lang;
                            }
                            $done = DBRelation::DB_UpdateWhere(array($t_relation_count_field => $count), $r_args);
                        }
                    }
                }
            }
        }
        return $done;
    }
    
    
    
    
    protected static function DB_FilterParentsRelations($relations, $fields=array())
    {
        $parents = static::GetParents();
        foreach ($relations AS $k => $v)
        {
            if (!isset($parents[$k]))
            {
                $relations[$k] = null;
                unset($relations[$k]);
            }
        }
        foreach ($parents AS $rel => $parent)
        {
            if (!isset($relations[$rel]) && $parent['force'] && is_string($parent['function']) && function_exists($parent['function']))
            {
                $relations[$rel] = call_user_func($parent['function']);
            }
        }
        foreach ($fields AS $field)
        {
            if (is_array($field))
            {
                if (isset($field['relation']) && !isset($relations[$field['relation']]))
                {
                    //$relations[$rel] = 
                }
            }
        }
        return $relations;
    }
    
    protected static function DB_GetRelatedTables($relations=array(), $fields=array())
    {
        $tables = array(
            'self' => array(
                'table' => static::DB_GetTableName(),
                'alias' => 't0'
            )
        );
        $n = 1;
        foreach ($relations AS $k => $v)
        {
            $tables[$k] = array(
                'table' => DBRelation::DB_GetTableName(),
                'alias' => 't' . $n
            );
            $n++;
        }
        foreach ($fields AS $field)
        {
            if (isset($field['relation']) && !isset($tables[$field['relation']]))
            {
                $tables[$field['relation']] = array(
                    'table' => DBRelation::DB_GetTableName(),
                    'alias' => 't' . $n
                );
                $n++;
            }
        }
        return $tables;
    }
    
    
    protected static function DB_GenereateSelect($fields, $tables=array())
    {
        $t_field = static::DB_GetPrimaryField();
        if (!in_array($t_field, $fields))
        {
            array_push($fields, $t_field);
        }
        $fields = static::DB_FilterFieldsOnValues($fields);
        foreach ($fields AS $k => $v)
        {
            $fields[$k] = $tables['self']['alias'] . '.' . $v;
        }
        return ' ' . implode(', ', $fields) . ' ';
    }
    
    protected static function DB_GenereateJoins($relations, $tables, $lang=null)
    {
        $t = $tables['self']['alias'];
        $t_field = static::DB_GetPrimaryField();
        
        $t_relation_parent_field = DBRelation::GetParentField();
        $t_relation_child_field = DBRelation::GetChildField();
        $t_relation_name_field = DBRelation::GetNameField();
        $t_relation_language_field = DBRelation::GetLanguageField();
        
        $parents = static::GetParents();
        
        $sql = '';
        foreach ($relations AS $rel => $pid)
        {
            if ($pid !== false && isset($tables[$rel])) // || is_null($relations[$obj])
            {
                $t_relation = $tables[$rel]['alias'];
                $buffer = '';
                if (is_null($relations[$rel]))
                {
                    $buffer .= ' LEFT OUTER JOIN ' . $tables[$rel]['table'] . ' AS ' . $t_relation . ' ON ' . $t_relation . "." . $t_relation_name_field . "='" . $rel . "' AND " . $t_relation . "." . $t_relation_child_field . "=" . $t . "." . $t_field . ' ';
                }
                else if (is_string($relations[$rel]) || is_numeric($relations[$rel]))
                {
                    $buffer .= ' INNER JOIN ' . $tables[$rel]['table'] . ' AS ' . $t_relation . ' ON ' . $t_relation . "." . $t_relation_name_field . "='" . $rel . "' AND " . $t_relation . "." . $t_relation_parent_field . "=" . (int)$relations[$rel] . " AND " . $t_relation . "." . $t_relation_child_field . "=" . $t . "." . $t_field . ' ';
                }
                else if (is_array($relations[$rel]))
                {
                    $buffer .= ' INNER JOIN ' . $tables[$rel]['table'] . ' AS ' . $t_relation . ' ON ' . $t_relation . "." . $t_relation_name_field . "='" . $rel . "' AND " . $t_relation . "." . $t_relation_parent_field . " IN (" . implode(',', $relations[$rel]) . ") AND " . $t_relation . "." . $t_relation_child_field . "=" . $t . "." . $t_field . ' ';
                }
                
                if ($lang == static::REPLICATE_CONFIG_ALL_LANGUAGES)
                {
                    $langs  = call_user_func(static::DB_ALL_LANGUAGE_FUNCTION);
                    if (is_array($langs) && !empty($langs))
                    {
                        $buffer .= ' AND ' . $t_relation . "." . $t_relation_language_field . " IN ('" . implode("','", $langs) . "') ";
                    }
                }
                else if (isset($parents[$rel]) && $parents[$rel]['language'] && !empty($buffer))
                {
                    $lang = is_null($lang) ? call_user_func(static::DB_LANGUAGE_FUNCTION) : $lang;
                    $buffer .= ' AND ' . $t_relation . "." . $t_relation_language_field . "='" . $lang . "' ";
                }
                $sql .= $buffer;
            }
        }
        return $sql;
    }
    
    protected static function DB_GenerateWhere($where, $relations=array(), $tables=array())
    {
        $t = $tables['self']['alias'];
        $sql = ' WHERE 1 ';
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
        
        $t_relation_child_field = DBRelation::GetChildField();
        foreach ($relations AS $rel => $pid)
        {
            if (isset($tables[$rel]))
            {
                $t_relation = $tables[$rel]['alias'];
                if (is_null($pid))
                {
                    $sql .= " AND " . $t_relation . '.' . mysql_escape($t_relation_child_field) . " IS NULL";
                }
            }
        }
        $sql .= ' ';
        return $sql;
    }
    
    protected static function DB_GenereateOrderBy($fields, $tables=array())
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
        $orderFieldsTable = static::DB_FilterFieldsOnValues($orderFields);
        
        //$orderFieldsRelation = DBRelation::DB_FilterFieldsOnValues($orderFields);
        foreach ($fields AS $k => $v)
        {
            $v = explode(' ', $v);
            if (isset($orderFieldsTable[$k]))
            {
                $fields[$k] = $t . '.' . $v[0] . ' ' . (isset($v[1]) ? $v[1] : 'ASC');
            }
            /*else if (isset($orderFieldsRelation[$k]))
            {
                $t_relation = DBRelation::DB_GetTableName();
                $fields[$k] = $t_relation . '.' . $v[0] . ' ' . (isset($v[1]) ? $v[1] : 'ASC');
            }*/
            else
            {
                $fields[$k] = null;
                unset($fields[$k]);
            }
        }
        
        return empty($fields) ? '' : ' ' . implode(', ', $fields) . ' ';
    }
    
}