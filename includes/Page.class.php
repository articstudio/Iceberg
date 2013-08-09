<?php

/** Include helpers config file */
require_once ICEBERG_DIR_HELPERS . 'page.php';


abstract class PageBase extends ObjectDBRelations
{
    /**
     * DB table name
     * @var string
     */
    public static $DB_TABLE = 'ICEBERG_DB_PAGES';
    
    /**
     * List of fields
     * @var array
     */
    public static $DB_FIELDS = array(
        'created' => array(
            'name' => 'CREATED',
            'type' => 'TIMESTAMP',
            'length' => null,
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'updated' => array(
            'name' => 'CREATED',
            'type' => 'TIMESTAMP',
            'length' => null,
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'status' => array(
            'name' => 'PAGE STATUS',
            'type' => 'INT',
            'length' => '3',
            'flags' => array(
                'NOT NULL',
                'DEFAULT \'1\''
            ),
            'index' => true
        )
    );
    
    /**
     * Parents relation
     * @var array 
     */
    public static $DB_PARENTS = array(
        'page-domain' => array(
            'object' => 'Domain',
            'force' => true,
            'function' => 'get_domain_request_id',
            'language' => true
        ),
        'page-type' => array(
            'object' => 'PageType',
            'force' => true,
            'function' => 'get_page_pagetype',
            'language' => false
        ),
        'page-taxonomy' => array(
            'object' => 'PageTaxonomy',
            'force' => true,
            'function' => 'get_default_pagetaxnomy',
            'language' => false
        ),
        'page-group' => array(
            'object' => 'PageGroup',
            'force' => true,
            'function' => 'get_default_pagegroup',
            'language' => false
        ),
        'page-parent' => array(
            'object' => 'Page',
            'force' => false,
            'function' => '',
            'language' => false
        ),
        'user-create' => array(
            'object' => 'User',
            'force' => false,
            'function' => '',
            'language' => false
        ),
        'user-update' => array(
            'object' => 'User',
            'force' => false,
            'function' => '',
            'language' => false
        )
    );
    
    const RELATION_KEY_DOMAIN = 'page-domain';
    const RELATION_KEY_TYPE = 'page-type';
    const RELATION_KEY_TAXONOMY = 'page-taxonomy';
    const RELATION_KEY_GROUP = 'page-group';
    const RELATION_KEY_PARENT = 'page-parent';
    const RELATION_KEY_USER_CREATE = 'user-create';
    const RELATION_KEY_USER_UPDATE = 'user-update';
}


class Page extends PageBase
{
    const TYPE_DEFAULT = 'page';
    
    const STATUS_ACTIVE = 1;
    const STATUS_UNACTIVE = 0;
    
    var $id;
    var $type;
    var $taxonomy;
    var $group;
    var $parent;
    var $status;
    var $order;
    var $metas;
    var $lang;
    var $created;
    var $updated;
    var $created_uid;
    var $updated_uid;
    
    const METAS_FORCE_LOAD = 'METAS_FORCE_LOAD';
    
    public function __construct($args=array(), $lang=null)
    {
        $this->id = isset($args['id']) ? $args['id'] : -1;
        $this->type = isset($args['type']) ? $args['type'] : get_page_pagetype();
        $this->taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : get_default_pagetaxnomy();
        $this->group = isset($args['group']) ? $args['group'] : get_default_pagegroup();
        $this->parent = isset($args['parent']) ? $args['parent'] : null;
        $this->status = isset($args['status']) ? $args['status'] : static::STATUS_ACTIVE;
        $this->order = isset($args['order']) ? $args['order'] : 0;
        $this->lang = is_null($lang) ? get_lang() : $lang;
        $this->metas = isset($args['metas']) ? $args['metas'] : static::METAS_FORCE_LOAD;
        if ($this->metas === static::METAS_FORCE_LOAD)
        {
            $this->LoadMetas($this->lang);
        }
        $this->created = isset($args['created']) ? $args['created'] : time();
        $this->updated = isset($args['updated']) ? $args['updated'] : null;
        $this->created_uid = isset($args['created_uid']) ? $args['created_uid'] : get_user_id();
        $this->updated_uid = isset($args['updated_uid']) ? $args['updated_uid'] : null;
    }
    
    public function LoadMetas($lang=null)
    {
        $metas = static::DB_SelectChild(
            'PageMeta', 
            $this->id, 
            array(
                'name', 
                'value', 
                PageMeta::RELATION_KEY_PAGE => array(
                    array(DBRelation::GetLanguageField(), 'lang')
                )
            ), 
            array(), 
            array(), 
            array(), 
            $lang
        );
        foreach ($metas AS $meta)
        {
            $this->SetMeta($meta->name, static::DB_DecodeFieldValue($meta->value), $meta->lang);
        }
    }
    
    public function SetMeta($key, $value, $lang=null)
    {
        $lang = is_null($lang) ? get_lang() : $lang;
        $this->metas = !is_array($this->metas) ? array() : $this->metas;
        $this->metas[$lang] = (!isset($this->metas[$lang]) || !is_array($this->metas[$lang])) ? array() : $this->metas[$lang];
        $this->metas[$lang][$key] = $value;
    }
    
    public function GetMeta($key, $default=false, $lang=null)
    {
        $lang = is_null($lang) ? get_lang() : $lang;
        if (is_array($this->metas) && isset($this->metas[$lang]))
        {
            if (is_array($this->metas[$lang]) && isset($this->metas[$lang][$key]))
            {
                return $this->metas[$lang][$key];
            }
        }
        return $default;
    }
    
    public function GetTitle($lang=null)
    {
        return $this->GetMeta(PageMeta::META_TITLE, '', $lang);
    }
    
    public function GetPermalink($lang=null)
    {
        return $this->GetMeta(PageMeta::META_PERMALINK, '', $lang);
    }
    
    public function GetText($lang=null)
    {
        return $this->GetMeta(PageMeta::META_TEXT, '', $lang);
    }
    
    public function GetImage($lang=null)
    {
        return $this->GetMeta(PageMeta::META_IMAGE, '', $lang);
    }
    
    
    public static function Insert($args=array(), $lang=null)
    {
        $args = array(
            'created' => time(),
            'status' => isset($args['status']) ? $args['status'] : static::STATUS_ACTIVE
        );
        $relations = array(
            static::RELATION_KEY_TYPE => isset($args['type']) ? $args['type'] : get_page_pagetype(),
            static::RELATION_KEY_TAXONOMY => isset($args['taxonomy']) ? $args['taxonomy'] : get_default_pagetaxnomy(),
            static::RELATION_KEY_GROUP => isset($args['group']) ? $args['group'] : get_default_pagegroup(),
            static::RELATION_KEY_PARENT => isset($args['parent']) ? $args['parent'] : null,
            static::RELATION_KEY_USER_CREATE => isset($args['created_uid']) ? $args['created_uid'] : get_user_id()
        );
        $id = static::DB_Insert($args, $relations, $lang);
        if ($id)
        {
            $metas = PageMeta::Normalize(isset($args['metas']) ? $args['metas'] : array());
            foreach ($metas AS $key => $value)
            {
                $args_meta = array(
                    'name' => $key,
                    'value' => $value
                );
                static::DB_InsertChild('PageMeta', $args_meta, $id);
            }
        }
        return $id;
    }
    
    public static function GetList($args=array(), $lang=null)
    {
        $fields = static::GetSelectFields();
        $where = static::GetWhereFields($args);
        $orderby = static::GetOrderFields($args);
        $limit = static::GetLimitFields($args);
        $relations = static::GetRelationsFields($args);
        $pages = static::DB_Select($fields, $where, $orderby, $limit, $relations, $lang);
        foreach ($pages AS $k => $page)
        {
            $pages[$k] = static::GetPageFromObject($page, $lang);
        }
        /** @todo CACHE */
        return $pages;
    }
    
    public static function GetPage($id, $lang=null)
    {
        $fields = static::GetSelectFields();
        $where = static::GetWhereFields(array('id' => $id));
        $pages = static::DB_Select($fields, $where, array(), array(0, 1), array(), $lang);
        if (is_array($pages) && !empty($pages))
        {
            $page = current($pages);
            return static::GetPageFromObject($page, $lang);
            
        }
        return false;
    }
    
    
    protected static function GetPageFromObject($obj, $lang=null)
    {
        $args = array(
            'id' => $obj->id,
            'type' => $obj->type,
            'taxonomy' => $obj->taxonomy,
            'group' => $obj->gid,
            'parent' => $obj->pid,
            'status' => $obj->status,
            'order' => $obj->count,
            'created' => $obj->created,
            'updated' => $obj->updated,
            'created_uid' => $obj->user_create,
            'updated_uid' => $obj->user_update,
            'metas' => static::METAS_FORCE_LOAD
        );
        return new Page($args, $lang);
    }
    
    protected static function GetSelectFields()
    {
        return array(
            'id',
            'created',
            'updated',
            'status',
            static::RELATION_KEY_DOMAIN => array(
                array(DBRelation::GetCountField(), 'count')
            ),
            static::RELATION_KEY_TYPE => array(
                array(DBRelation::GetParentField(), 'type')
            ),
            static::RELATION_KEY_TAXONOMY => array(
                array(DBRelation::GetParentField(), 'taxonomy')
            ),
            static::RELATION_KEY_GROUP => array(
                array(DBRelation::GetParentField(), 'gid')
            ),
            static::RELATION_KEY_PARENT => array(
                array(DBRelation::GetParentField(), 'pid')
            ),
            static::RELATION_KEY_USER_CREATE => array(
                array(DBRelation::GetParentField(), 'user_create')
            ),
            static::RELATION_KEY_USER_UPDATE => array(
                array(DBRelation::GetParentField(), 'user_update')
            )
        );
    }
    
    protected static function GetWhereFields($args)
    {
        $arr = array();
        if (isset($args['id']))
        {
            $arr['id'] = $args['id'];
        }
        if (isset($args['status']))
        {
            $arr['status'] = $args['status'];
        }
        return $arr;
    }
    
    protected static function GetOrderFields($args)
    {
        $arr = array();
        if (isset($args['order']))
        {
            if ($args['order'] == 'time')
            {
                $arr[] = 'created';
            }
            else
            {
                $arr[static::RELATION_KEY_DOMAIN] = DBRelation::GetCountField();
                
            }
        }
        $arr[] = static::DB_GetPrimaryField();
        return $arr;
    }
    
    protected static function GetLimitFields($args)
    {
        $arr = array();
        return $arr;
    }
    
    protected static function GetRelationsFields($args)
    {
        $arr = array();
        if (isset($args['type']))
        {
            $arr[static::RELATION_KEY_TYPE] = $args['type'];
        }
        if (isset($args['taxonomy']))
        {
            $arr[static::RELATION_KEY_TAXONOMY] = $args['taxonomy'];
        }
        if (isset($args['group']))
        {
            $arr[static::RELATION_KEY_GROUP] = $args['group'];
        }
        if (isset($args['parent']))
        {
            $arr[static::RELATION_KEY_PARENT] = $args['parent'];
        }
        return $arr;
    }
}
