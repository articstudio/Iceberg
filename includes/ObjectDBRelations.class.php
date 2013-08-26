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
    const REPLICATE_ALL_LANGUAGES = '_ALL_LLANGUAGES_';
    
    const DB_ALL_LANGUAGE_FUNCTION = 'get_active_locales';
    
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
     * Parent relation default struct
     * @var type 
     */
    protected static $DB_CHILD_DEFAULT = array(
        'object' => 'ObjectName',
        'nameField' => 'name',
        'valueField' => 'value'
    );
    
    /**
     * Parents relation
     * @var array 
     */
    protected static $DB_PARENTS = array();
    
    /**
     * Childs relation
     * @var array 
     */
    protected static $DB_CHILDS = array();
    
    
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
    
    public static function GetParent($key)
    {
        $parents = static::GetParents();
        return static::DB_NormalizeParent(isset($parents[$key]) ? $parents[$key] : array());
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
    
    
    protected static function DB_NormalizeChild($child)
    {
        return array_merge(static::$DB_CHILD_DEFAULT, (!is_array($child) ? array() : $child));
    }
    
    protected static function NormalizeChilds($childs)
    {
        $childs = !is_array($childs) ? array() : $childs;
        foreach ($childs AS $k => $v)
        {
            $childs[$k] = static::DB_NormalizeChild($v);
        }
        return $childs;
    }
    
    public static function GetChilds()
    {
        return static::NormalizeChilds(static::$DB_CHILDS);
    }
    
    public static function IsChildObject($childObj)
    {
        $childs = static::GetChilds();
        $found = false;
        foreach ($childs AS $k => $child)
        {
            $found = ($child['object'] === $childObj) ? $k : false;
            if ($found)
                break;
        }
        return $found;
    }
    
    public static function IsChildRelation($childRel)
    {
        $childs = static::GetChilds();
        return isset($childs[$childRel]) ? $childs[$childRel] : false;
    }
    
    /*public static function GetParentObjectsID($id, $parentRel)
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
    }*/
    
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
            
            $t = $tables['self']['table'] . ' AS ' . $tables['self']['alias'];
            $t_field = static::DB_GetPrimaryField();
            $sql = "SELECT " . static::DB_GenereateSelect($fields, $tables, $lang);
            $sql .= " FROM " . $t;
            $sql .= " " . static::DB_GenereateJoins($relations, $fields, $tables, $lang);
            $sql .= " " . static::DB_GenerateWhere($where, $tables, $relations);
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
                        if ($lang == static::REPLICATE_ALL_LANGUAGES)
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
        if ($lang === static::REPLICATE_ALL_LANGUAGES)
        {
            $langs  = call_user_func(static::DB_ALL_LANGUAGE_FUNCTION);
            foreach ($langs AS $lang)
            {
                static::DB_InsertUpdate($args, $where, $relations, $lang);
            }
        }
        else {
            $primary_field = static::DB_GetPrimaryField();
            $items = static::DB_Select(array($primary_field), $where, array(), array(), $relations, $lang);
            if (is_array($items) && count($items) > 0)
            {
                $ids = array_keys($items);
                return static::DB_Update($ids, $args);
            }
            else
            {
                $args = array_merge($where, $args);
                return static::DB_Insert($args, $relations, $lang);
            }
        }
        return false;
    }
    
    public static function DB_Delete($where, $relations=array(), $lang=null)
    {
        $done = false;
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
                            if ($lang == static::REPLICATE_ALL_LANGUAGES)
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
                                DBRelation::DB_Delete($r_args);
                            }
                        }
                    }
                }
            }
        }
        return $done;
    }
    
    public static function DB_InsertChild($childObj, $args, $id=null, $lang=null)
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
    
    public static function DB_SelectChild($childObj, $id=null, $fields=array(), $where=array(), $orderby=array(), $limit=array(), $lang=null)
    {
        $parentObj = get_called_class();
        $rel = $childObj::IsParentObject($parentObj);
        if ($rel)
        {
            $relations = is_null($id) ? array() : array($rel => $id);
            return $childObj::DB_Select($fields, $where, $orderby, $limit, $relations, $lang);
        }
        return array();
    }
    
    public static function DB_DeleteChild($childObj, $where=array(), $id=null, $lang=null)
    {
        $parentObj = get_called_class();
        $rel = $childObj::IsParentObject($parentObj);
        if ($rel)
        {
            $relations = is_null($id) ? array() : array($rel => $id);
            return $childObj::DB_Delete($where, $relations, $lang);
        }
        return false;
    }
    
    public static function DB_InsertUpdateChild($childObj, $args, $where=array(), $id=null, $lang=null)
    {
        $parentObj = get_called_class();
        $rel = $childObj::IsParentObject($parentObj);
        if ($rel)
        {
            $relations = is_null($id) ? array() : array($rel => $id);
            return $childObj::DB_InsertUpdate($args, $where, $relations, $lang);
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
                        if ($lang == static::REPLICATE_ALL_LANGUAGES)
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
        $childs = static::GetChilds();
        foreach ($relations AS $k => $v)
        {
            if (!isset($parents[$k]) && !isset($childs[$k]))
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
        $parents = static::GetParents();
        $childs = static::GetChilds();
        $tables = array(
            'self' => array(
                'table' => static::DB_GetTableName(),
                'alias' => 't0'
            )
        );
        $n = 1;
        foreach ($relations AS $k => $v)
        {
            if (isset($parents[$k]))
            {
                $tables[$k] = array(
                    'table' => DBRelation::DB_GetTableName(),
                    'alias' => 't' . $n
                );
                $n++;
            }
            else if (isset($childs[$k]))
            {
                $tables[$k] = array(
                    'table' => $childs[$k]['object']::DB_GetTableName(),
                    'alias' => 't' . $n
                );
                $n++;
                $tables['child-'.$k] = array(
                    'table' => DBRelation::DB_GetTableName(),
                    'alias' => 't' . $n
                );
                $n++;
                
                if (is_array($v))
                {
                    $tables[$k]['childs'] = array();
                    foreach ($v AS $rk => $r)
                    {
                        $tables[$k]['childs'][$rk] = array(
                            'table' => $childs[$k]['object']::DB_GetTableName(),
                            'alias' => 't' . $n
                        );
                        $n++;
                        $tables[$k]['childs']['child-'.$rk] = array(
                            'table' => DBRelation::DB_GetTableName(),
                            'alias' => 't' . $n
                        );
                        $n++;
                    }
                }
                
            }
        }
        $tables['fields'] = array(
            'table' => DBRelation::DB_GetTableName(),
            'alias' => 't' . $n
        );
        $n++;
        foreach ($fields AS $rel => $field)
        {
            if (is_array($field) && !isset($tables[$rel]))
            {
                $tables['fields'] = array(
                    'table' => DBRelation::DB_GetTableName(),
                    'alias' => 't' . $n
                );
                //$n++;
                break;
            }
        }
        return $tables;
    }
    
    
    protected static function DB_GenereateSelect($fields, $tables, $lang=null)
    {
        $t_field = static::DB_GetPrimaryField();
        if (!in_array($t_field, $fields))
        {
            array_push($fields, $t_field);
        }
        $t = $tables['self']['alias'];
        $fieldsTable = static::DB_FilterFieldsOnValues($fields);
        $sql_fields = array();
        $parents = static::GetParents();
        $lang = is_null($lang) ? call_user_func(static::DB_LANGUAGE_FUNCTION) : $lang;
        foreach ($fields AS $k => $v)
        {
            if (is_string($v) && in_array($v, $fieldsTable))
            {
                array_push($sql_fields, $t . '.' . $v);
            }
            else if (is_array($v) && isset($tables['fields']))
            {
                
                //max(case when t2.name='page-parent' AND t2.language='en_US'  then t2.pid end) AS pid
                
                $t_relation = $tables['fields']['alias'];
                foreach ($v AS $field)
                {
                    $useLang = isset($parents[$k]) && $parents[$k]['language'];
                    if (is_string($field))
                    {
                        if (DBRelation::DB_IsField($field))
                        {
                            $sql_field = "MAX(CASE WHEN " . $t_relation . "." . DBRelation::GetNameField() . "='" . mysql_escape($k) . "' " . ($useLang ? " AND " . $t_relation . "." . DBRelation::GetLanguageField() . "='" . mysql_escape($lang) . "'" : "") . " THEN " . $t_relation . "." . $field . " END) AS " . $field;
                            array_push($sql_fields, $sql_field);
                        }
                    }
                    else if (is_array($field) && count($field)>1)
                    {
                        if (DBRelation::DB_IsField($field[0]))
                        {
                            $sql_field = "MAX(CASE WHEN " . $t_relation . "." . DBRelation::GetNameField() . "='" . mysql_escape($k) . "' " . ($useLang ? " AND " . $t_relation . "." . DBRelation::GetLanguageField() . "='" . mysql_escape($lang) . "'" : "") . " THEN " . $t_relation . "." . $field[0] . " END) AS " . $field[1];
                            array_push($sql_fields, $sql_field);
                        }
                    }
                }
            }
        }
        return empty($sql_fields) ? '' : ' ' . implode(', ', $sql_fields) . ' ';
    }
    
    protected static function DB_GenereateJoins($relations, $fields, $tables, $lang=null)
    {
        $parents = static::GetParents();
        $childs = static::GetChilds();
        
        $t = $tables['self']['alias'];
        $t_field = static::DB_GetPrimaryField();
        
        $t_relation_parent_field = DBRelation::GetParentField();
        $t_relation_child_field = DBRelation::GetChildField();
        $t_relation_name_field = DBRelation::GetNameField();
        $t_relation_language_field = DBRelation::GetLanguageField();
        
        $sql = '';
        
        foreach ($tables AS $rel => $table)
        {
            if ($rel === 'fields')
            {
                $t_relation = $tables['fields']['alias'];
                $sql .= ' LEFT JOIN ' . $tables['fields']['table'] . ' AS ' . $t_relation . ' ON ' . $t_relation . "." . $t_relation_child_field . "=" . $t . "." . $t_field . ' ';
            }
            else if ($rel !== 'self')
            {
                if (isset($relations[$rel])) // $pid !== false && is_null($relations[$obj])
                {
                    $t_relation = $tables[$rel]['alias'];
                    $buffer = '';
                    
                    if (isset($parents[$rel]))
                    {
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
                            $relations[$rel] = empty($relations[$rel]) ? array(-1) : $relations[$rel];
                            $buffer .= ' INNER JOIN ' . $tables[$rel]['table'] . ' AS ' . $t_relation . ' ON ' . $t_relation . "." . $t_relation_name_field . "='" . $rel . "' AND " . $t_relation . "." . $t_relation_parent_field . " IN (" . implode(',', $relations[$rel]) . ") AND " . $t_relation . "." . $t_relation_child_field . "=" . $t . "." . $t_field . ' ';
                        }

                        if ($lang == static::REPLICATE_ALL_LANGUAGES)
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
                    }
                    else if (isset($childs[$rel]) && is_array($relations[$rel]) && isset($tables['child-'.$rel]))
                    {
                        foreach ($relations[$rel] AS $key => $value)
                        {
                            $t_relation = $tables[$rel]['childs'][$key]['alias'];
                            $t_child_primary = $childs[$rel]['object']::DB_GetPrimaryField();
                            $t_relation_child = $tables[$rel]['childs']['child-'.$key]['alias'];
                            if ($value === static::FIELD_NOT_EMPTY)
                            {
                                $buffer .= ' INNER JOIN ' . $tables[$rel]['table'] . ' AS ' . $t_relation . ' ON ' . $t_relation . "." . $childs[$rel]['nameField'] . "='" . $key . "' AND " . $t_relation . "." . $childs[$rel]['valueField'] . "<>'' ";
                            }
                            else {
                                $buffer .= ' INNER JOIN ' . $tables[$rel]['table'] . ' AS ' . $t_relation . ' ON ' . $t_relation . "." . $childs[$rel]['nameField'] . "='" . $key . "' AND " . $t_relation . "." . $childs[$rel]['valueField'] . "='" . $value . "' ";
                            }
                            $buffer .= ' INNER JOIN ' . $tables['child-'.$rel]['table'] . ' AS ' . $t_relation_child . ' ON ' . $t_relation_child . "." . $t_relation_name_field . "='" . $rel . "' AND " . $t_relation_child . "." . $t_relation_child_field . "=" . $t_relation . '.' . $t_child_primary . " AND " . $t_relation_child . "." . $t_relation_parent_field . "=" . $t . "." . $t_field . ' ';
                        }
                    }
                    $sql .= $buffer;
                }
                /*else if (isset($fields[$rel]))
                {
                    $t_relation = $tables[$rel]['alias'];
                    $sql .= ' LEFT JOIN ' . $tables[$rel]['table'] . ' AS ' . $t_relation . ' ON ' . $t_relation . "." . $t_relation_name_field . "='" . $rel . "' AND " . $t_relation . "." . $t_relation_child_field . "=" . $t . "." . $t_field . ' ';
                    if (isset($parents[$rel]) && $parents[$rel]['language'])
                    {
                        $lang = is_null($lang) ? call_user_func(static::DB_LANGUAGE_FUNCTION) : $lang;
                        $sql .= ' AND ' . $t_relation . "." . $t_relation_language_field . "='" . $lang . "' ";
                    }
                }*/
            }
        }
        return $sql;
    }
    
    protected static function DB_GenerateWhere($where, $tables, $relations=array())
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
        $orderFieldsTable = static::DB_FilterFieldsOnValues($orderFields);
        $orderFieldsRelation = DBRelation::DB_FilterFieldsOnValues($orderFields);
        foreach ($fields AS $k => $v)
        {
            $v = explode(' ', $v);
            if (in_array($v[0], $orderFieldsTable))
            {
                $fields[$k] = $t . '.' . $v[0] . ' ' . (isset($v[1]) ? $v[1] : 'ASC');
            }
            else if (in_array($v[0], $orderFieldsRelation) && isset($tables[$k]))
            {
                $t_relation = $tables[$k]['alias'];
                $fields[$k] = $t_relation . '.' . $v[0] . ' ' . (isset($v[1]) ? $v[1] : 'ASC');
            }
            else
            {
                $fields[$k] = null;
                unset($fields[$k]);
            }
        }
        return empty($fields) ? '' : ' ' . implode(', ', $fields) . ' ';
    }
    
}


/*
 * 
SELECT  t0.id, t0.created, t0.updated, t0.status, t1.count AS count, t2.pid AS type, t3.pid AS taxonomy, t4.pid AS gid, t5.pid AS pid, t6.pid AS user_create, t7.pid AS user_update  

FROM iceberg_pages AS t0  

INNER JOIN iceberg_relations AS t1 ON t1.name='page-domain' AND t1.pid=1 AND t1.cid=t0.id  AND t1.language='en_US'  

LEFT JOIN iceberg_relations AS t2 ON t2.name='page-type' AND t2.cid=t0.id  

LEFT JOIN iceberg_relations AS t3 ON t3.name='page-taxonomy' AND t3.cid=t0.id  

LEFT JOIN iceberg_relations AS t4 ON t4.name='page-group' AND t4.cid=t0.id  

LEFT JOIN iceberg_relations AS t5 ON t5.name='page-parent' AND t5.cid=t0.id  

LEFT JOIN iceberg_relations AS t6 ON t6.name='user-create' AND t6.cid=t0.id  

LEFT JOIN iceberg_relations AS t7 ON t7.name='user-update' AND t7.cid=t0.id   

WHERE 1  AND t0.id='24'  

GROUP BY t0.id LIMIT  0, 1 

**********************************************************************

SELECT
  t0.id,
  t0.created,
  t0.updated,
  t0.status,
  t1.count AS count,

  max(case when t2.name='page-type' then t2.pid end) AS type,
  max(case when t2.name='page-taxonomy' then t2.pid end) AS taxonomy,
  max(case when t2.name='page-group' then t2.pid end) AS gid,
  max(case when t2.name='page-parent' AND t2.language='en_US'  then t2.pid end) AS pid,
  max(case when t2.name='user-create' then t2.pid end) AS user_create,
  max(case when t2.name='user-update' then t2.pid end) AS user_update

FROM iceberg_pages AS t0  

INNER JOIN iceberg_relations AS t1 ON t1.name='page-domain' AND t1.pid=1 AND t1.cid=t0.id  AND t1.language='en_US' 

LEFT JOIN iceberg_relations AS t2 ON t2.cid=t0.id  

WHERE 1  AND t0.id='24'  

GROUP BY t0.id LIMIT  0, 1 
 * 
 */