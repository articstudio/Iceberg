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
            'force' => false,
            'function' => 'get_page_pagetype',
            'language' => false
        ),
        'page-taxonomy' => array(
            'object' => 'PageTaxonomy',
            'force' => false,
            'function' => 'get_default_pagetaxnomy',
            'language' => false
        ),
        'page-group' => array(
            'object' => 'PageGroup',
            'force' => false,
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
    
    /**
     * Childs relation
     * @var array 
     */
    public static $DB_CHILDS = array(
        'page-meta' => array(
            'object' => 'PageMeta'
        )
    );
    
    const RELATION_KEY_DOMAIN = 'page-domain';
    const RELATION_KEY_TYPE = 'page-type';
    const RELATION_KEY_TAXONOMY = 'page-taxonomy';
    const RELATION_KEY_GROUP = 'page-group';
    const RELATION_KEY_PARENT = 'page-parent';
    const RELATION_KEY_USER_CREATE = 'user-create';
    const RELATION_KEY_USER_UPDATE = 'user-update';
    const RELATION_KEY_META = 'page-meta';
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
    var $translations;
    
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
        $this->lang = is_null($lang) ? I18N::GetLanguage() : $lang;
        $this->metas = isset($args['metas']) ? $args['metas'] : static::METAS_FORCE_LOAD;
        if ($this->metas === static::METAS_FORCE_LOAD)
        {
            $this->LoadMetas($this->lang);
        }
        $this->created = isset($args['created']) ? $args['created'] : time();
        $this->updated = isset($args['updated']) ? $args['updated'] : null;
        $this->created_uid = isset($args['created_uid']) ? $args['created_uid'] : get_user_id();
        $this->updated_uid = isset($args['updated_uid']) ? $args['updated_uid'] : null;
        $this->translations = $this->GetTranslations();
    }
    
    /* METAS */
    public function LoadMetas($lang=null)
    {
        $this->metas = !is_array($this->metas) ? array() : $this->metas;
        $metas = static::GetMetas($this->id, $lang);
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
    
    public function SaveMeta($key, $value, $lang=null)
    {
        $done = $this->SetMeta($key, $value, $lang);
        if ($done)
        {
            return static::IsertUpdateMeta($this->id, $key, $value, $lang);
        }
        return $done;
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
    
    public function HasMeta($key, $lang=null)
    {
        $lang = is_null($lang) ? get_lang() : $lang;
        if (is_array($this->metas) && isset($this->metas[$lang]))
        {
            if (is_array($this->metas[$lang]) && isset($this->metas[$lang][$key]))
            {
                return true;
            }
        }
        return false;
    }
    
    public function GetTitle($lang=null)
    {
        return $this->GetMeta(PageMeta::META_TITLE, '', $lang);
    }
    
    public function GetTemplate($lang=null)
    {
        return $this->GetMeta(PageMeta::META_TEMPLATE, '', $lang);
    }
    
    public function GetPermalink($lang=null)
    {
        return $this->GetMeta(PageMeta::META_PERMALINK, '', $lang);
    }
    
    public function GetLink($args=array(), $lang=null)
    {
        $buffer = array(
            'page' => $this->id,
            'lang' => is_null($lang) ? I18N::GetLanguage() : $lang
        );
        $args = array_merge($args, $buffer);
        $routing = Routing::GetRouting();
        return $routing->GenerateURL($args, get_base_url());
    }
    
    public function GetText($lang=null)
    {
        return $this->GetMeta(PageMeta::META_TEXT, '', $lang);
    }
    
    public function GetImage($lang=null)
    {
        return $this->GetMeta(PageMeta::META_IMAGE, '', $lang);
    }
    
    /* RELATIONS */
    public function GetTaxonomyID()
    {
        return $this->taxonomy;
    }
    
    public function GetTaxonomy()
    {
        return ObjectTaxonomy::Get($this->taxonomy);
    }
    
    public function GetTypeID()
    {
        return $this->type;
    }
    
    public function GetType()
    {
        return ObjectTaxonomy::Get($this->type);
    }
    
    public function GetGroupID()
    {
        return $this->group;
    }
    
    public function GetGroup()
    {
        return ObjectTaxonomy::Get($this->group);
    }
    
    /* TRANSLATIONS */
    public function IsTranslated($lang)
    {
        if (!is_array($lang) && is_array($this->translations) && !empty($this->translations))
        {
            return isset($this->translations[$lang]) ? $this->translations[$lang] : false;
        }
        $lang_field = DBRelation::GetLanguageField();
        $fields = array(
            $lang_field
        );
        $where = array(
            DBRelation::GetChildField() => $this->id,
            DBRelation::GetLanguageField() => $lang,
            DBRelation::GetNameField() => static::RELATION_KEY_DOMAIN
        );
        $translations = DBRelation::DB_Select($fields, $where);
        if (is_array($lang) && is_array($translations))
        {
            $translated = array();
            foreach ($translations AS $translation)
            {
                $translated[$translation->$lang_field] = true;
            }
            foreach ($lang AS $l)
            {
                $translated[$l] = isset($translated[$l]);
            }
            return $translated;
        }
        return is_array($translations) ? count($translations) > 0 : false;
    }
    
    public function GetTranslations()
    {
        if (is_array($this->translations) && !empty($this->translations))
        {
            return $this->translations;
        }
        return $this->IsTranslated(I18N::GetActiveLocales());
    }
    
    
    /* ACTIONS */
    public static function Unactive($id)
    {
        $args = array(
            'status' => static::STATUS_UNACTIVE
        );
        return static::DB_Update($id, $args);
    }
    
    public static function Active($id)
    {
        $args = array(
            'status' => static::STATUS_ACTIVE
        );
        return static::DB_Update($id, $args);
    }
    
    public static function IsertUpdateMeta($id, $key, $value, $lang=null)
    {
        $args_meta = array(
            'name' => $key,
            'value' => $value
        );
        $where = array(
            'name' => $key
        );
        return static::DB_InsertUpdateChild('PageMeta', $args_meta, $where, $id);
    }
    
    public static function InsertUpdateRelation($id, $rel, $parent)
    {
        $args_rel = array(
            DBRelation::GetParentField() => $parent
        );
        $where_rel = array(
            DBRelation::GetNameField() => $rel,
            DBRelation::GetChildField() => $id
        );
        return DBRelation::DB_InsertUpdate($args_rel, $where_rel);
    }
    
    public static function Insert($args=array(), $lang=null)
    {
        $taxonomy = (isset($args['taxonomy']) && !is_null($args['taxonomy'])) ? $args['taxonomy'] : get_default_pagetaxnomy();
        $args_insert = array(
            'created' => date('Y-m-d H:i:s'),
            'status' => isset($args['status']) ? $args['status'] : static::STATUS_ACTIVE
        );
        $relations = array(
            static::RELATION_KEY_TYPE => (isset($args['type']) && !is_null($args['type'])) ? $args['type'] : get_page_pagetype(),
            static::RELATION_KEY_TAXONOMY => $taxonomy,
            static::RELATION_KEY_GROUP => (isset($args['group']) && !is_null($args['group'])) ? $args['group'] : get_default_pagegroup(),
            static::RELATION_KEY_PARENT => isset($args['parent']) ? $args['parent'] : null,
            static::RELATION_KEY_USER_CREATE => isset($args['created_uid']) ? $args['created_uid'] : get_user_id()
        );
        $id = static::DB_Insert($args_insert, $relations, $lang);
        if ($id)
        {
            $metas = PageMeta::Normalize(isset($args['metas']) ? $args['metas'] : array());
            foreach ($metas AS $key => $value)
            {
                static::IsertUpdateMeta($id, $key, $value, $lang);
            }
            
            $taxonomy = PageTaxonomy::Get($taxonomy);
            $elements = $taxonomy->GetElements();
            foreach ($elements AS $element)
            {
                static::IsertUpdateMeta($id, $element->GetAttrName(), $element->GetFormEdit(), $lang);
            }
        }
        return $id;
    }
    
    public static function Update($id, $args=array(), $lang=null)
    {
        $args_update = array(
            'updated' => date('Y-m-d H:i:s')
        );
        $where_update = array(
            'id' => $id
        );
        $relations = array();
        $done = static::DB_UpdateWhere($args_update, $where_update, $relations, $lang);
        if ($done)
        {
            $taxonomy = (isset($args['taxonomy']) && !is_null($args['taxonomy'])) ? $args['taxonomy'] : get_default_pagetaxnomy();
            $relations = array(
                static::RELATION_KEY_TYPE => (isset($args['type']) && !is_null($args['type'])) ? $args['type'] : get_page_pagetype(),
                static::RELATION_KEY_TAXONOMY => $taxonomy,
                static::RELATION_KEY_GROUP => (isset($args['group']) && !is_null($args['group'])) ? $args['group'] : get_default_pagegroup(),
                static::RELATION_KEY_PARENT => isset($args['parent']) ? $args['parent'] : null,
                static::RELATION_KEY_USER_UPDATE => isset($args['updated_uid']) ? $args['updated_uid'] : get_user_id()
            );
            foreach ($relations AS $rel => $parent)
            {
                static::InsertUpdateRelation($id, $rel, $parent);
            }
            
            $metas = PageMeta::Normalize(isset($args['metas']) ? $args['metas'] : array());
            foreach ($metas AS $key => $value)
            {
                static::IsertUpdateMeta($id, $key, $value, $lang);
            }
            
            $taxonomy = PageTaxonomy::Get($taxonomy);
            $elements = $taxonomy->GetElements();
            foreach ($elements AS $element)
            {
                static::IsertUpdateMeta($id, $element->GetAttrName(), $element->GetFormEdit(), $lang);
            }
        }
        return $done;
    }
    
    public static function UpdateParent($id, $parent)
    {
        return static::InsertUpdateRelation($id, static::RELATION_KEY_PARENT, $parent);
    }
    
    public static function UpdateOrder($id, $order, $lang=null)
    {
        $args_rel = array(
            DBRelation::GetCountField() => $order
        );
        $where_rel = array(
            DBRelation::GetNameField() => static::RELATION_KEY_DOMAIN,
            DBRelation::GetParentField() => Domain::GetRequestID(),
            DBRelation::GetChildField() => $id,
            DBRelation::GetLanguageField() => is_null($lang) ? I18N::GetLanguage() : $lang
        );
        return DBRelation::DB_InsertUpdate($args_rel, $where_rel);
    }
    
    public static function Remove($id, $lang=null)
    {
        $where = static::GetWhereFields(array(static::DB_GetPrimaryField() => $id));
        $done = static::DB_Delete($where, array(), null);
        if ($done)
        {
            static::DB_DeleteChild('PageMeta', array(), $id, $lang);
        }
        return $done;
    }
    
    /* GET */
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
        
        if (isset($args['order']))
        {
            if ($args['order'] === 'name')
            {
                uasort($pages , 'page_sort_by_name');
            }
        }
        reset($pages);
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
        return new Page();
    }
    
    public static function GetMetas($id, $lang=null)
    {
        return static::DB_SelectChild(
            'PageMeta', 
            $id, 
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
            'created' => strtotime($obj->created),
            'updated' => strtotime($obj->updated),
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
            if ($args['order'] == 'tree')
            {
                $arr[static::RELATION_KEY_DOMAIN] = DBRelation::GetCountField();
            }
            else
            {
                $arr[static::RELATION_KEY_DOMAIN] = DBRelation::GetCountField();
                
            }
        }
        $arr[static::RELATION_KEY_DOMAIN] = DBRelation::GetCountField();
        $arr[] = static::DB_GetPrimaryField();
        return $arr;
    }
    
    protected static function GetLimitFields($args)
    {
        $arr = array();
        if (isset($args['items']))
        {
            $items = (int)$args['items'];
            $arr = array(
                0,
                $items
            );
        }
        else if (isset($args['page']))
        {
            $page = (int)$args['page'];
            $page_items = isset($args['page_items']) ? (int)$args['page_items'] : 10;
            $arr = array(
                $page * $page_items,
                $page_items
            );
        }
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
        if (isset($args['metas']) && is_array($args['metas']))
        {
            $arr[static::RELATION_KEY_META] = array();
            foreach ($args['metas'] AS $k => $v)
            {
                $arr[static::RELATION_KEY_META][$k] = $v;
            }
            
        }
        return $arr;
    }
}
