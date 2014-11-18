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
    
    const DB_RELATION_NOT_NULL = 'NOT_NULL';
    
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
        'autodelete' => false
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
    
    protected static function DB_NormalizeParents($parents)
    {
        $parents = !is_array($parents) ? array() : $parents;
        foreach ($parents AS $k => $v)
        {
            $parents[$k] = static::DB_NormalizeParent($v);
        }
        return $parents;
    }
    
    public static function DB_GetParents()
    {
        return static::DB_NormalizeParents(static::$DB_PARENTS);
    }
    
    public static function DB_GetParent($key)
    {
        $parents = static::DB_GetParents();
        return static::DB_NormalizeParent(isset($parents[$key]) ? $parents[$key] : array());
    }
    
    public static function DB_IsParentObject($parentObj)
    {
        $parents = static::DB_GetParents();
        foreach ($parents AS $k => $parent)
        {
            if ($parent['object'] === $parentObj)
            {
                return $k;
            }
        }
        return false;
    }
    
    public static function DB_IsParentRelation($parentRel)
    {
        $parents = static::DB_GetParents();
        return isset($parents[$parentRel]) ? $parents[$parentRel] : false;
    }
    
    
    protected static function DB_NormalizeChild($child)
    {
        return array_merge(static::$DB_CHILD_DEFAULT, (!is_array($child) ? array() : $child));
    }
    
    protected static function DB_NormalizeChilds($childs)
    {
        $childs = !is_array($childs) ? array() : $childs;
        foreach ($childs AS $k => $v)
        {
            $childs[$k] = static::DB_NormalizeChild($v);
        }
        return $childs;
    }
    
    public static function DB_GetChilds()
    {
        return static::DB_NormalizeChilds(static::$DB_CHILDS);
    }
    
    public static function DB_GetChild($key)
    {
        $childs = static::DB_GetChilds();
        return static::DB_NormalizeChild(isset($childs[$key]) ? $childs[$key] : array());
    }
    
    public static function DB_IsChildObject($childObj)
    {
        $childs = static::DB_GetChilds();
        foreach ($childs AS $k => $child)
        {
            if ($child['object'] === $childObj)
            {
                return $k;
            }
        }
        return false;
    }
    
    public static function DB_IsChildRelation($childRel)
    {
        $childs = static::DB_GetChilds();
        return isset($childs[$childRel]) ? $childs[$childRel] : false;
    }
    
    
    
    public static function DB_Select($fields, $where=array(), $orderby=array(), $limit=array(), $relations=array(), $lang=null)
    {
        $parents = static::DB_GetParents();
        if (empty($parents))
        {
            return parent::DB_Select($fields, $where, $orderby, $limit);
        }
        else
        {
            $result = array();
            $relations = static::DB_FilterParentsRelation($relations, $fields);
            $tables = static::DB_GetRelatedTables($relations, $fields);
            
            $sql = "SELECT " . static::DB_GenereateRelationsSelect($fields, $tables, $lang);
            $sql .= " FROM " . $tables['self']['table'] . ' AS ' . $tables['self']['alias'];
            $sql .= " " . static::DB_GenereateRelationsJoins($relations, $fields, $tables, $lang);
            $sql .= " " . static::DB_GenerateRelationsWhere($where, $tables, $relations, $fields);
            $sql .= " " . static::DB_GenerateRelationsGroupBy($tables, $relations);
            $orderby = static::DB_GenereateRelationsOrderBy($orderby, $tables);
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
        $insertID = parent::DB_Insert($args);
        if ($insertID)
        {
            $lang = is_null($lang) ? call_user_func(static::DB_LANGUAGE_FUNCTION) : $lang;
            $langs  = call_user_func(static::DB_ALL_LANGUAGE_FUNCTION);
            $relations = static::DB_FilterParentsRelation($relations);
            $parents = static::DB_GetParents();
            $table_relation_parent_field = DBRelation::GetParentField();
            $table_relation_child_field = DBRelation::GetChildField();
            $table_relation_name_field = DBRelation::GetNameField();
            $table_relation_attribute_field = DBRelation::GetAttributeField();
            $table_relation_language_field = DBRelation::GetLanguageField();
            foreach ($relations AS $relation_name => $relation_value)
            {
                $parent = static::DB_IsParentRelation($relation_name);
                if ($parent && $relation_value !== null && $relation_value !== false)
                {
                    $relation_attribute = false;
                    if (is_array($relation_value) && isset($relation_value['attribute']) && isset($relation_value['value']))
                    {
                        $relation_attribute = $relation_value['attribute'];
                        $relation_value = $relation_value['value'];
                    }
                    $relation_value = !is_array($relation_value) ? array($relation_value) : $relation_value;
                    foreach ($relation_value AS $parent_id)
                    {
                        $r_args = array(
                            $table_relation_parent_field => $parent_id,
                            $table_relation_child_field => $insertID,
                            $table_relation_name_field => $relation_name,
                            $table_relation_attribute_field => $relation_attribute,
                            $table_relation_language_field => false
                        );
                        if ($parent['language'])
                        {
                            if ($lang === static::REPLICATE_ALL_LANGUAGES)
                            {
                                foreach ($langs AS $locale)
                                {
                                    $r_args[$table_relation_language_field] = $locale;
                                    DBRelation::DB_Insert($r_args);
                                }
                            }
                            else
                            {
                                $r_args[$table_relation_language_field] = $lang;
                                DBRelation::DB_Insert($r_args);
                            }
                        }
                        else
                        {
                            DBRelation::DB_Insert($r_args);
                        }
                    }
                }
            }
        }
        return $insertID;
    }
    
    public static function DB_Update($id, $args)
    {
        $tables = static::DB_GetRelatedTables();
        $table_self = static::DB_GetTableName() . ' AS ' . $tables['self']['alias'];
        $fields = array_map('db_encode', static::DB_FilterFields($args));
        $where = static::DB_GenerateRelationsWhere(array(
            static::DB_GetPrimaryField() => $id
        ), $tables);
        return db_update($table_self, $fields, $where);
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
        $primary_field = static::DB_GetPrimaryField();
        $items = static::DB_Select(array($primary_field), $where, array(), array(), $relations, $lang);
        if (count($items) > 0)
        {
            $ids = array_keys($items);
            if (parent::DB_Delete(array($primary_field=>$ids)))
            {
                static::DB_DeleteAllParentRelations($ids);
                static::DB_DeleteAllChilds($ids);
                return true;
            }
        }
        return false;
    }
    
    public static function DB_ReOrder($order, $relations=array(), $lang=null)
    {
        $done = false;
        $relations = static::DB_FilterParentsRelation($relations);
        $parents = static::DB_GetParents();
        
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
    
    
    
    
    
    public static function DB_InsertParentRelation($child_id, $relation_name, $relation_attribute, $parent_id, $lang=null, $count=null)
    {
        $parent = static::DB_IsParentRelation($relation_name);
        if ($parent)
        {
            $args = array(
                DBRelation::GetChildField() => $child_id,
                DBRelation::GetParentField() => $parent_id,
                DBRelation::GetNameField() => $relation_name,
                DBRelation::GetAttributeField() => is_null($relation_attribute) ? false : $relation_attribute,
                DBRelation::GetLanguageField() => false,
                DBRelation::GetCountField() => is_null($count) ? false : $count
            );
            if ($parent['language'])
            {
                $args[DBRelation::GetLanguageField()] = is_null($lang) ? I18N::GetLanguage() : $lang;
            }
            return DBRelation::DB_Insert($args);
        }
        return false;
    }
    
    public static function DB_InsertUpdateParentRelation($child_id, $relation_name, $relation_attribute, $parent_id, $lang=null, $count=null)
    {
        $parent = static::DB_IsParentRelation($relation_name);
        if ($parent)
        {
            $args_rel = array(
                DBRelation::GetParentField() => $parent_id,
                DBRelation::GetCountField() => is_null($count) ? false : $count
            );
            $where_rel = array(
                DBRelation::GetChildField() => $child_id,
                DBRelation::GetNameField() => $relation_name,
                DBRelation::GetAttributeField() => is_null($relation_attribute) ? false : $relation_attribute,
                DBRelation::GetLanguageField() => false
            );
            if ($parent['language'])
            {
                $lang = is_null($lang) ? I18N::GetLanguage() : $lang;
                $where_rel[DBRelation::GetLanguageField()] = $lang;
            }
            return DBRelation::DB_InsertUpdate($args_rel, $where_rel);
        }
        return false;
    }
    
    public static function DB_SelectParentRelation($child_id, $relation_name, $relation_attribute=null, $lang=null, $count=null)
    {
        $parent = static::DB_IsParentRelation($relation_name);
        if ($parent)
        {
            $fields = array(
                DBRelation::GetParentField(),
                DBRelation::GetAttributeField(),
                DBRelation::GetCountField()
            );
            $where = array(
                DBRelation::GetChildField() => $child_id,
                DBRelation::GetNameField() => $relation_name,
                DBRelation::GetAttributeField() => is_null($relation_attribute) ? false : $relation_attribute,
                DBRelation::GetLanguageField() => false,
                DBRelation::GetCountField() => is_null($count) ? false : $count
            );
            if ($parent['language'])
            {
                $lang = ($lang === static::REPLICATE_ALL_LANGUAGES) ? null : (is_null($lang) ? I18N::GetLanguage() : $lang);
                $where[DBRelation::GetLanguageField()] = is_null($lang) ? false : $lang;
            }
            return DBRelation::DB_Select($fields, $where);
        }
        return array();
    }
    
    public static function DB_DeleteParentRelation($child_id, $relation_name, $relation_attribute=null, $lang=null, $count=null)
    {
        $parent = static::DB_IsParentRelation($relation_name);
        if ($parent)
        {
            $where = array(
                DBRelation::GetNameField() => $relation_name,
                DBRelation::GetChildField() => $child_id,
                DBRelation::GetAttributeField() => is_null($relation_attribute) ? false : $relation_attribute,
                DBRelation::GetCountField() => is_null($count) ? false : $count,
                DBRelation::GetLanguageField() => false
            );
            if ($parent['language'])
            {
                $lang = ($lang === static::REPLICATE_ALL_LANGUAGES) ? null : (is_null($lang) ? I18N::GetLanguage() : $lang);
                $where[DBRelation::GetLanguageField()] = is_null($lang) ? false : $lang;
            }
            return DBRelation::DB_Delete($where);
        }
        return false;
    }
    
    public static function DB_DeleteAllParentRelations($child_id, $lang=null)
    {
        $parents = static::DB_GetParents();
        foreach ($parents AS $relation_name => $parent)
        {
            static::DB_DeleteParentRelation($child_id, $relation_name, null, $lang, null);
        }
        return true;
    }
    
    
    
    
    public static function DB_InsertChild($parent_id, $relation_name, $relation_attribute, $args, $lang=null)
    {
        $child = static::DB_IsChildRelation($relation_name);
        if ($child)
        {
            $childObj = $child['object'];
            $relations = array(
                $relation_name => is_null($relation_attribute) ? $parent_id : array('attribute'=>$relation_attribute, 'value'=>$parent_id)
            );
            return $childObj::DB_Insert($args, $relations);
        }
        return false;
    }
    
    public static function DB_SelectChilds($parent_id, $relation_name, $relation_attribute, $fields=array(), $where=array(), $orderby=array(), $limit=array(), $relations=array(), $lang=null)
    {
        $child = static::DB_IsChildRelation($relation_name);
        if ($child)
        {
            $childObj = $child['object'];
            $relations[$relation_name] = is_null($relation_attribute) ? $parent_id : array('attribute'=>$relation_attribute, 'value'=>$parent_id);
            return $childObj::DB_Select($fields, $where, $orderby, $limit, $relations, $lang);
        }
        return array();
    }
    
    public static function DB_SelectChildRelation($parent_id, $relation_name, $relation_attribute=null, $lang=null, $count=null)
    {
        $child = static::DB_IsChildRelation($relation_name);
        if ($child)
        {
            $fields = array(
                DBRelation::GetChildField(),
                DBRelation::GetCountField()
            );
            $where = array(
                DBRelation::GetParentField() => $parent_id,
                DBRelation::GetNameField() => $relation_name,
                DBRelation::GetAttributeField() => is_null($relation_attribute) ? false : $relation_attribute,
                DBRelation::GetLanguageField() => false,
                DBRelation::GetCountField() => is_null($count) ? false : $count
            );
            $childObj = $child['object'];
            $child_parent = $childObj::DB_IsParentRelation($relation_name);
            if ($child_parent)
            {
                if ($child_parent['language'])
                {
                    $lang = ($lang === static::REPLICATE_ALL_LANGUAGES) ? null : (is_null($lang) ? I18N::GetLanguage() : $lang);
                    $where[DBRelation::GetLanguageField()] = is_null($lang) ? false : $lang;
                }
            }
            
            return DBRelation::DB_Select($fields, $where);
        }
        return array();
    }
    
    public static function DB_InsertUpdateChilds($parent_id, $relation_name, $relation_attribute, $args, $where=array(), $relations=array(), $lang=null)
    {
        $child = static::DB_IsChildRelation($relation_name);
        if ($child)
        {
            $childObj = $child['object'];
            $relations[$relation_name] = is_null($relation_attribute) ? $parent_id : array('attribute'=>$relation_attribute, 'value'=>$parent_id);
            return $childObj::DB_InsertUpdate($args, $where, $relations, $lang);
        }
        else {
            echo $relation_name . '<br>';
        }
        return false;
    }
    
    public static function DB_DeleteRelationChilds($parent_id, $relation_name, $relation_attribute=null, $lang=null)
    {
        $child = static::DB_IsChildRelation($relation_name);
        if ($child)
        {
            $childObj = $child['object'];
            $where = array();
            $relations = array(
                $relation_name => is_null($relation_attribute) ? $parent_id : array('attribute'=>$relation_attribute, 'value'=>$parent_id)
            );
            return $childObj::DB_Delete($where, $relations, $lang);
        }
        return false;
    }
    
    public static function DB_DeleteAllChilds($parent_id, $lang=null)
    {
        $childs = static::DB_GetChilds();
        foreach ($childs AS $relation_name => $child)
        {
            if ($child['autodelete'])
            {
                static::DB_DeleteRelationChilds($parent_id, $relation_name, null, $lang);
            }
        }
    }
    
    
    
    
    protected static function DB_FilterParentsRelation($relations, $fields=array())
    {
        $correct_relations = array();
        $parents = static::DB_GetParents();
        foreach ($parents AS $relation_name => $parent)
        {
            if (isset($relations[$relation_name]))
            {
                $correct_relations[$relation_name] = $relations[$relation_name];
            }
            else if ($parent['force'] && is_string($parent['function']) && function_exists($parent['function']))
            {
                $correct_relations[$relation_name] = call_user_func($parent['function']);
            }
            
            if (isset($relations[$relation_name]) && is_array($correct_relations[$relation_name]))
            {
                if (isset($correct_relations[$relation_name]['attribute']) && isset($correct_relations[$relation_name]['value']))
                {}
                else
                {
                    if (!isset($correct_relations[$relation_name]['operator']))
                    {
                        $correct_relations[$relation_name]['operator'] = 'IN';
                    }
                }
            }
        }
        return $correct_relations;
    }
    
    protected static function DB_GetRelatedTables($relations=array(), $fields=array())
    {
        $tables = array(
            'self' => array('table' => static::DB_GetTableName(), 'alias' => 't0')
        );
        $n = 1;
        foreach ($relations AS $relation_name => $relation)
        {
            if (static::DB_IsParentRelation($relation_name))
            {
                $tables[$relation_name] = array('table' => DBRelation::DB_GetTableName(), 'alias' => 't' . $n);
                ++$n;
            }
        }
        $tables['fields'] = array('table' => DBRelation::DB_GetTableName(), 'alias' => 't' . $n);
        ++$n;
        foreach ($fields AS $relation_name => $field)
        {
            if (is_array($field) && !isset($tables[$relation_name]))
            {
                $tables['fields'] = array('table' => DBRelation::DB_GetTableName(), 'alias' => 't' . $n);
                break;
            }
        }
        return $tables;
    }
    
    
    protected static function DB_GenerateSelectTableField($field, $table_self)
    {
        $field_name = $field;
        $field_alias = null;
        if (is_array($field) && count($field)===2)
        {
            $field_name = reset($field);
            $field_alias = next($field);
        }
        if (is_string($field_name) && static::DB_IsField($field_name))
        {
            return $table_self . '.' . $field_name . (is_string($field_alias) ? ' AS ' . $field_alias : '');
        }
        return '';
    }
    protected static function DB_GenerateSelectRelationField($field, $relation_name, $lang, $table_relation)
    {
        $fields = array();
        if (is_string($field) && DBRelation::DB_IsField($field))
        {
            $fields[] = "MAX(CASE WHEN " . $table_relation . "." . DBRelation::GetNameField() . "='" . mysql_escape($relation_name) . "' " . ($lang ? " AND " . $table_relation . "." . DBRelation::GetLanguageField() . "='" . mysql_escape($lang) . "'" : "") . " THEN " . $table_relation . "." . $field . " END) AS " . $field;
        }
        else if (is_array($field))
        {
            foreach ($field AS $field_key)
            {
                if (is_string($field_key) && DBRelation::DB_IsField($field_key))
                {
                    $fields[] = "MAX(CASE WHEN " . $table_relation . "." . DBRelation::GetNameField() . "='" . mysql_escape($relation_name) . "' " . ($lang ? " AND " . $table_relation . "." . DBRelation::GetLanguageField() . "='" . mysql_escape($lang) . "'" : "") . " THEN " . $table_relation . "." . $field_key . " END) AS " . $field_key;
                }
                else if (is_array($field_key) && count($field_key)===2)
                {
                    $field_name = reset($field_key);
                    $field_alias = next($field_key);
                    if (DBRelation::DB_IsField($field_name))
                    {
                        $fields[] = "MAX(CASE WHEN " . $table_relation . "." . DBRelation::GetNameField() . "='" . mysql_escape($relation_name) . "' " . ($lang ? " AND " . $table_relation . "." . DBRelation::GetLanguageField() . "='" . mysql_escape($lang) . "'" : "") . " THEN " . $table_relation . "." . $field_name . " END) AS " . $field_alias;
                    }
                }
            }
        }
        return $fields;
    }
    protected static function DB_GenereateRelationsSelect($fields, $tables, $lang=null)
    {
        $sql_fields = array();
        $lang = is_null($lang) ? call_user_func(static::DB_LANGUAGE_FUNCTION) : $lang;
        $table_self_field = static::DB_GetPrimaryField();
        if (!in_array($table_self_field, $fields))
        {
           $fields[] = $table_self_field;
        }
        foreach ($fields AS $relation_name => $field)
        {
            $parent = static::DB_IsParentRelation($relation_name);
            if ($parent && isset($tables['fields']))
            {
                $parent_fields = static::DB_GenerateSelectRelationField($field, $relation_name, ($parent['language'] ? $lang : false), $tables['fields']['alias']);
                $sql_fields = array_merge($sql_fields, $parent_fields);
            }
            else if (!$parent)
            {
                $sql_fields[] = static::DB_GenerateSelectTableField($field, $tables['self']['alias']);
            }
        }
        $sql_fields = array_filter($sql_fields);
        return empty($sql_fields) ? '' : ' ' . implode(', ', $sql_fields) . ' ';
    }
    
    protected static function DB_GenereateRelationsJoins($relations, $fields, $tables, $lang=null)
    {
        $sql = '';
        $lang = is_null($lang) ? call_user_func(static::DB_LANGUAGE_FUNCTION) : $lang;
        $langs  = call_user_func(static::DB_ALL_LANGUAGE_FUNCTION);
        $table_self = $tables['self']['alias'];
        $table_self_field = static::DB_GetPrimaryField();
        $table_relation_parent_field = DBRelation::GetParentField();
        $table_relation_child_field = DBRelation::GetChildField();
        $table_relation_name_field = DBRelation::GetNameField();
        $table_relation_attribute_field = DBRelation::GetAttributeField();
        $table_relation_language_field = DBRelation::GetLanguageField();
        
        foreach ($tables AS $relation_name => $table)
        {
            if ($relation_name === 'self')
            {}
            else if ($relation_name === 'fields')
            {
                $sql .= ' LEFT JOIN ' . $table['table'] . ' AS ' . $table['alias'] . ' ON ' . $table['alias'] . "." . $table_relation_child_field . "=" . $table_self . "." . $table_self_field . ' ';
            }
            else if ($relations[$relation_name])
            {
                $parent = static::DB_IsParentRelation($relation_name);
                if ($parent)
                {
                    $buffer = '';
                    if (is_null($relations[$relation_name]) || $relations[$relation_name] === static::DB_RELATION_NOT_NULL)
                    {
                        $buffer .= ' LEFT OUTER JOIN ' . $table['table'] . ' AS ' . $table['alias'] . ' ON ' . $table['alias'] . "." . $table_relation_name_field . "='" . $relation_name . "' AND " . $table['alias'] . "." . $table_relation_child_field . "=" . $table_self . "." . $table_self_field . ' ';
                    }
                    else if (is_string($relations[$relation_name]) || is_numeric($relations[$relation_name]))
                    {
                        $buffer .= ' INNER JOIN ' . $table['table'] . ' AS ' . $table['alias'] . ' ON ' . $table['alias'] . "." . $table_relation_name_field . "='" . $relation_name . "' AND " . $table['alias'] . "." . $table_relation_parent_field . "=" . (int)$relations[$relation_name] . " AND " . $table['alias'] . "." . $table_relation_child_field . "=" . $table_self . "." . $table_self_field . ' ';
                    }
                    else if (is_array($relations[$relation_name]))
                    {
                        if (isset($relations[$relation_name]['attribute']) && isset($relations[$relation_name]['value']))
                        {
                            $relation_attribute = $relations[$relation_name]['attribute'];
                            $relation_value = $relations[$relation_name]['value'];
                            if (is_null($relation_value) || $relation_value === static::DB_RELATION_NOT_NULL)
                            {
                                $buffer .= ' LEFT OUTER JOIN ' . $table['table'] . ' AS ' . $table['alias'] . ' ON ' . $table['alias'] . "." . $table_relation_name_field . "='" . $relation_name . "' AND " . $table['alias'] . "." . $table_relation_attribute_field . "='" . $relation_attribute . "' AND " . $table['alias'] . "." . $table_relation_child_field . "=" . $table_self . "." . $table_self_field . ' ';
                            }
                            else if (is_string($relation_value) || is_numeric($relation_value))
                            {
                                $buffer .= ' INNER JOIN ' . $table['table'] . ' AS ' . $table['alias'] . ' ON ' . $table['alias'] . "." . $table_relation_name_field . "='" . $relation_name . "' AND " . $table['alias'] . "." . $table_relation_attribute_field . "='" . $relation_attribute . "' AND " . $table['alias'] . "." . $table_relation_parent_field . "=" . (int)$relation_value . " AND " . $table['alias'] . "." . $table_relation_child_field . "=" . $table_self . "." . $table_self_field . ' ';
                            }
                            else if (is_array($relation_value))
                            {
                                $relation_value = empty($relation_value) ? array(-1) : $relation_value;
                                $buffer .= ' INNER JOIN ' . $table['table'] . ' AS ' . $table['alias'] . ' ON ' . $table['alias'] . "." . $table_relation_name_field . "='" . $relation_name . "' AND " . $table['alias'] . "." . $table_relation_attribute_field . "='" . $relation_attribute . "' AND " . $table['alias'] . "." . $table_relation_parent_field . " IN (" . implode(',', $relation_value) . ") AND " . $table['alias'] . "." . $table_relation_child_field . "=" . $table_self . "." . $table_self_field . ' ';
                            }
                        }
                        else
                        {
                            $operator = 'IN';
                            if (isset($relations[$relation_name]['operator']))
                            {
                                $operator = $relations[$relation_name]['operator'];
                                unset($relations[$relation_name]['operator']);
                            }
                            $relations[$relation_name] = empty($relations[$relation_name]) ? array(-1) : $relations[$relation_name];
                            if ($operator === 'IN' || $operator === 'OR' || $operator === 'AND')
                            {
                                $buffer .= ' INNER JOIN ' . $table['table'] . ' AS ' . $table['alias'] . ' ON ' . $table['alias'] . "." . $table_relation_name_field . "='" . $relation_name . "' AND " . $table['alias'] . "." . $table_relation_parent_field . " IN (" . implode(',', $relations[$relation_name]) . ") AND " . $table['alias'] . "." . $table_relation_child_field . "=" . $table_self . "." . $table_self_field . ' ';
                            }
                        }
                    }
                    
                    if ($parent['language'] && !empty($buffer))
                    {
                        if ($lang === static::REPLICATE_ALL_LANGUAGES)
                        {
                            if (is_array($langs) && !empty($langs))
                            {
                                $buffer .= ' AND ' . $table['alias'] . "." . $table_relation_language_field . " IN ('" . implode("','", $langs) . "') ";
                            }
                        }
                        else
                        {
                            $buffer .= ' AND ' . $table['alias'] . "." . $table_relation_language_field . "='" . $lang . "' ";
                        }
                    }

                    $sql .= $buffer;
                }
            }
        }
        return $sql;
    }
    
    protected static function DB_GenerateRelationsWhere($where, $tables, $relations=array(), $fields=array())
    {
        $table_self = $tables['self']['alias'];
        $sql = ' WHERE 1 ';
        $where = static::DB_FilterFields($where);
        foreach ($where AS $field_name => $field_value)
        {
            if (is_array($field_value))
            {
                if (count($field_value) == 0)
                {
                    $field_value = null;
                }
                else if (count($field_value) == 1)
                {
                    $field_value = reset($field_value);
                }
            }
            if (is_string($field_value) || is_numeric($field_value))
            {
                $sql .= " AND " . $table_self . '.' . mysql_escape($field_name) . "='" . mysql_escape($field_value) . "'";
            }
            else if (is_null($field_value))
            {
                $sql .= " AND " . $table_self . '.' . mysql_escape($field_name) . " IS NULL";
            }
            else if (is_array($field_value))
            {
                $ints = true;
                foreach ($field_value AS $vk => $vv)
                {
                    $ints = is_int($vv) ? $ints : false;
                    $field_value[$vk] = mysql_escape($vv);
                }
                if ($ints)
                {
                    $sql .= " AND " . $table_self . '.' . mysql_escape($field_name) . " IN (" . implode(",", $field_value) . ")";
                }
                else
                {
                    $sql .= " AND " . $table_self . '.' . mysql_escape($field_name) . " IN ('" . implode("','", $field_value) . "')";
                }
            }
        }
        
        $table_relation_child_field = DBRelation::GetChildField();
        foreach ($relations AS $relation_name => $relation)
        {
            if (isset($tables[$relation_name])) // && !isset($fields[$rel])
            {
                $table_relation = $tables[$relation_name]['alias'];
                if (is_null($relation))
                {
                    $sql .= " AND " . $table_relation . '.' . mysql_escape($table_relation_child_field) . " IS NULL";
                }
            }
        }
        $sql .= ' ';
        return $sql;
    }
    
    protected static function DB_GenerateRelationsGroupBy($tables, $relations=array())
    {
        $sql = ' GROUP BY ' . mysql_escape($tables['self']['alias']) . '.' . static::DB_GetPrimaryField() . ' ';
        $table_relation_parent_field = DBRelation::GetParentField();
        foreach ($tables AS $relation_name => $table)
        {
            if ($relation_name === 'self')
            {}
            else if ($relation_name === 'fields')
            {}
            else if ($relations[$relation_name])
            {
                $parent = static::DB_IsParentRelation($relation_name);
                if ($parent)
                {
                    if (is_array($relations[$relation_name]))
                    {
                        if (isset($relations[$relation_name]['attribute']) && isset($relations[$relation_name]['value']))
                        {}
                        else
                        {
                            $operator = 'IN';
                            if (isset($relations[$relation_name]['operator']))
                            {
                                $operator = $relations[$relation_name]['operator'];
                                unset($relations[$relation_name]['operator']);
                            }
                            if ($operator === 'AND')
                            {
                                $sql .= " HAVING COUNT(DISTINCT " . $table['alias'] . "." . $table_relation_parent_field  . ") = " . count($relations[$relation_name]) . " ";
                            }
                        }
                    }
                }
            }
        }
        return $sql;
    }
    
    protected static function DB_GenereateRelationsOrderBy($fields, $tables)
    {
        if (empty($fields))
        {
            return '';
        }
        else if (in_array('RAND()', $fields))
        {
            return ' RAND() ';
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
