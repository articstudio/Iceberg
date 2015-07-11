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
            'language' => false
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
        'page2page' => array(
            'object' => 'Page',
            'force' => false,
            'function' => '',
            'language' => false
        ),
        'page2dependence' => array(
            'object' => 'Page',
            'force' => false,
            'function' => '',
            'language' => false
        ),
        'user-create' => array(
            'object' => 'User',
            'force' => false,
            'function' => 'get_user_id',
            'language' => false
        ),
        'user-update' => array(
            'object' => 'User',
            'force' => false,
            'function' => '',
            'language' => false
        ),
        'user2page' => array(
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
        'page2meta' => array(
            'object' => 'PageMeta',
            'autodelete' => true
        ),
        'user-page' => array(
            'object' => 'User',
            'autodelete' => true
        ),
        'page2page' => array(
            'object' => 'Page',
            'autodelete' => false
        ),
        'page2dependence' => array(
            'object' => 'Page',
            'autodelete' => false
        ),
        'page-parent' => array(
            'object' => 'Page',
            'autodelete' => false
        )
    );
    
    const RELATION_KEY_DOMAIN = 'page-domain';
    const RELATION_KEY_TYPE = 'page-type';
    const RELATION_KEY_TAXONOMY = 'page-taxonomy';
    const RELATION_KEY_GROUP = 'page-group';
    const RELATION_KEY_PARENT = 'page-parent';
    const RELATION_KEY_PAGE = 'page2page';
    const RELATION_KEY_PAGE_DEPENDENCE = 'page2dependence';
    const RELATION_KEY_USER_CREATE = 'user-create';
    const RELATION_KEY_USER_UPDATE = 'user-update';
    const RELATION_KEY_META = 'page2meta';
    const RELATION_KEY_USER = 'user-page';
    const RELATION_KEY_USER_RELATED = 'user2page';
}


class Page extends PageBase
{
    const TYPE_DEFAULT = 'page';
    
    const STATUS_ACTIVE = 1;
    const STATUS_UNACTIVE = 0;
    const STATUS_PENDING = -1;
    
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
        $this->id = isset($args['id']) ? intval($args['id']) : -1;
        $this->type = isset($args['type']) ? intval($args['type']) : get_default_pagetype();
        $this->taxonomy = isset($args['taxonomy']) ? intval($args['taxonomy']) : get_default_pagetaxnomy();
        $this->group = isset($args['group']) ? intval($args['group']) : get_default_pagegroup();
        $this->parent = isset($args['parent']) ? intval($args['parent']) : null;
        $this->status = isset($args['status']) ? intval($args['status']) : static::STATUS_ACTIVE;
        $this->order = isset($args['order']) ? $args['order'] : 0;
        $this->lang = is_null($lang) ? I18N::GetLanguage() : $lang;
        $this->metas = isset($args['metas']) ? $args['metas'] : static::METAS_FORCE_LOAD;
        if ($this->metas === static::METAS_FORCE_LOAD)
        {
            $this->metas = array();
            if ($this->id>0)
            {
                $this->LoadMetas($this->lang, false);
            }
        }
        $this->created = isset($args['created']) ? $args['created'] : time();
        $this->updated = isset($args['updated']) ? $args['updated'] : null;
        $this->created_uid = isset($args['created_uid']) ? $args['created_uid'] : get_user_id();
        $this->updated_uid = isset($args['updated_uid']) ? $args['updated_uid'] : null;
        $this->translations = $this->id>0 ? $this->GetTranslations() : array();
        //list($obj, $args, $lang) = do_action('page_contruct', $this, $args, $lang);
        //$this->Merge($obj);
    }
    
    public function Merge($obj)
    {
        if (is_object($obj))
        {
            $obj = (array)$obj;
        }
        if (is_array($obj))
        {
            foreach ($obj AS $key => $value)
            {
                $this->$key = $value;
            }
            return true;
        }
        return false;
    }
    
    /* METAS */
    public function LoadMetas($lang=null, $cache=true)
    {
        $this->metas = !is_array($this->metas) ? array() : $this->metas;
        $metas = static::GetMetas($this->id, $lang);
        if (!empty($metas))
        {
            foreach ($metas AS $meta)
            {
                $this->SetMeta($meta->name, static::DB_DecodeFieldValue($meta->value), $meta->lang, false);
            }
            if ($cache)
            {
                static::AddCache($this->id, $this, $lang);
            }
        }
    }
    
    public function SetMeta($key, $value, $lang=null, $cache=true)
    {
        $lang = is_null($lang) ? $this->lang : $lang;
        $this->metas = !is_array($this->metas) ? array() : $this->metas;
        $this->metas[$lang] = (!isset($this->metas[$lang]) || !is_array($this->metas[$lang])) ? array() : $this->metas[$lang];
        $this->metas[$lang][$key] = $value;
        return $cache ? static::AddCache($this->id, $this, $lang) : true;
    }
    
    public function SaveMeta($key, $value, $lang=null, $cache=true)
    {
        $lang = is_null($lang) ? $this->lang : $lang;
        $done = $this->SetMeta($key, $value, $lang);
        return ($this->SetMeta($key, $value, $lang) && static::InsertUpdateMeta($this->id, $key, $value, $lang) && (!$cache || static::AddCache($this->id, $this, $lang)));
    }
    
    public function GetMetaList($lang=null)
    {
        $lang = is_null($lang) ? $this->lang : $lang;
        if (!is_array($this->metas) || !isset($this->metas[$lang]))
        {
            $this->LoadMetas($lang);
        }
        if (is_array($this->metas) && isset($this->metas[$lang]))
        {
            if (is_array($this->metas[$lang]))
            {
                return $this->metas[$lang];
            }
        }
        return array();
    }
    
    public function GetMeta($key, $default=false, $lang=null)
    {
        $metas = $this->GetMetaList($lang);
        return isset($metas[$key]) ? $metas[$key] : $default;
    }
    
    public function HasMeta($key, $lang=null)
    {
        $lang = is_null($lang) ? get_lang() : $lang;
        $metas = $this->GetMetaList($lang);
        return isset($metas[$key]);
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
        $args = is_array($args) ? $args : array();
        $buffer = array(
            'page' => $this->id,
            'lang' => is_null($lang) ? I18N::GetLanguage() : $lang
        );
        $args = array_merge($buffer, $args);
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
    
    /* USER */
    public function GetUser()
    {
        $args = array(
            'page' => $this->id
        );
        $users = User::GetList($args);
        return count($users)>0 ? reset($users) : new User();
    }
    
    /* RELATIONS */
    public function GetTaxonomyID()
    {
        return $this->taxonomy;
    }
    
    public function GetTaxonomy()
    {
        return PageTaxonomy::Get($this->taxonomy);
    }
    
    public function GetTypeID()
    {
        return $this->type;
    }
    
    public function GetType()
    {
        return PageType::Get($this->type);
    }
    
    public function GetGroupID()
    {
        return $this->group;
    }
    
    public function GetGroup()
    {
        return PageGroup::Get($this->group);
    }
    
    /* TRANSLATIONS */
    public function IsTranslated($lang)
    {
        if ((!is_array($lang) && !is_string($lang)) || empty($lang))
        {
            return false;
        }
        if (is_array($lang) && count($lang)===1)
        {
            $lang = current($lang);
        }
        if ($lang === $this->lang)
        {
            return true;
        }
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
    
    public static function Pending($id)
    {
        $args = array(
            'status' => static::STATUS_PENDING
        );
        return static::DB_Update($id, $args);
    }
    
    public static function InsertUpdateMeta($id, $key, $value, $lang=null)
    {
        $args_meta = array(
            'name' => $key,
            'value' => $value
        );
        $where = array(
            'name' => $key
        );
        return static::DB_InsertUpdateChilds($id, static::RELATION_KEY_META, null, $args_meta, $where, array(), $lang);
    }
    
    public static function Insert($args=array(), $lang=null)
    {
        $taxonomy = (isset($args['taxonomy']) && !is_null($args['taxonomy'])) ? $args['taxonomy'] : get_default_pagetaxnomy();
        $args_insert = array(
            'created' => date('Y-m-d H:i:s'),
            'status' => isset($args['status']) ? $args['status'] : static::STATUS_ACTIVE
        );
        $relations = array(
            static::RELATION_KEY_TYPE => (isset($args['type']) && !is_null($args['type'])) ? $args['type'] : get_default_pagetype(),
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
                static::InsertUpdateMeta($id, $key, $value, $lang);
            }
            
            $args['elements'] = (isset($args['elements']) && is_array($args['elements'])) ? $args['elements'] : array();
            $taxonomy = PageTaxonomy::Get($taxonomy);
            $elements = $taxonomy->GetElements();
            foreach ($elements AS $name => $element)
            {
                $element->SaveFormEdit($id, $args['elements'], $lang);
            }
        }
        return $id;
    }
    
    public static function Update($id, $args=array(), $lang=null)
    {
        $args_update = array(
            'updated' => date('Y-m-d H:i:s')
        );
        if (isset($args['status']))
        {
            $args_update['status'] = $args['status'];
        }
        $where_update = array(
            'id' => $id
        );
        $relations = array();
        $done = static::DB_UpdateWhere($args_update, $where_update, $relations, $lang);
        if ($done)
        {
            $taxonomy = (isset($args['taxonomy']) && !is_null($args['taxonomy'])) ? $args['taxonomy'] : get_default_pagetaxnomy();
            $relations = array(
                static::RELATION_KEY_TYPE => (isset($args['type']) && !is_null($args['type'])) ? $args['type'] : get_default_pagetype(),
                static::RELATION_KEY_TAXONOMY => $taxonomy,
                static::RELATION_KEY_GROUP => (isset($args['group']) && !is_null($args['group'])) ? $args['group'] : get_default_pagegroup(),
                static::RELATION_KEY_PARENT => isset($args['parent']) ? $args['parent'] : null,
                static::RELATION_KEY_USER_UPDATE => isset($args['updated_uid']) ? $args['updated_uid'] : get_user_id()
            );
            foreach ($relations AS $rel => $parent)
            {
                static::DB_InsertUpdateParentRelation($id, $rel, null, $parent, $lang, null);
            }
            
            $metas = PageMeta::Normalize(isset($args['metas']) ? $args['metas'] : array());
            foreach ($metas AS $key => $value)
            {
                static::InsertUpdateMeta($id, $key, $value, $lang);
            }
            
            $args['elements'] = (isset($args['elements']) && is_array($args['elements'])) ? $args['elements'] : array();
            $taxonomy = PageTaxonomy::Get($taxonomy);
            $elements = $taxonomy->GetElements();
            foreach ($elements AS $element)
            {
                $element->SaveFormEdit($id, $args['elements'], $lang);
            }
        }
        return $done;
    }
    
    public static function UpdateParent($id, $parent)
    {
        return static::DB_InsertUpdateParentRelation($id, static::RELATION_KEY_PARENT, null, $parent, null, null);
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
        return static::DB_Delete($where, array(), null);
    }
    
    public static function Translate($id, $lang, $args=array())
    {
        $done = static::DB_InsertUpdateParentRelation($id, static::RELATION_KEY_DOMAIN, null, get_domain_request_id(), $lang, null);
        if ($done)
        {
            $done = static::Update($id, $args, $lang);
        }
        return $done;
    }
    
    public static function Duplicate($id, $fromLang, $toLang)
    {
        $page = static::GetPage($id, $fromLang);
        $done = static::DB_InsertUpdateParentRelation($id, static::RELATION_KEY_DOMAIN, null, get_domain_request_id(), $toLang, $page->order);
        if ($done)
        {
            $args_update = array(
                'updated' => date('Y-m-d H:i:s')
            );
            $where_update = array(
                'id' => $id
            );
            $relations = array();
            $done = static::DB_UpdateWhere($args_update, $where_update, $relations, $toLang);
            if ($done)
            {
                $relations = array(
                    static::RELATION_KEY_TYPE => $page->type,
                    static::RELATION_KEY_TAXONOMY => $page->taxonomy,
                    static::RELATION_KEY_GROUP => $page->group,
                    static::RELATION_KEY_PARENT => $page->parent,
                    static::RELATION_KEY_USER_UPDATE => get_user_id()
                );
                foreach ($relations AS $rel => $parent)
                {
                    static::DB_InsertUpdateParentRelation($id, $rel, null, $parent, $toLang, null);
                }
                $metas = array(
                    PageMeta::META_TITLE => $page->GetTitle(),
                    PageMeta::META_PERMALINK => $page->GetPermalink(),
                    PageMeta::META_TEXT => $page->GetText(),
                    PageMeta::META_IMAGE => $page->GetImage(),
                    PageMeta::META_TEMPLATE => $page->GetTemplate()
                );
                $metas = PageMeta::Normalize($metas);
                foreach ($metas AS $key => $value)
                {
                    static::InsertUpdateMeta($id, $key, $value, $toLang);
                }

                $taxonomy = PageTaxonomy::Get($page->taxonomy);
                $elements = $taxonomy->GetElements();
                foreach ($elements AS $e_name => $element)
                {
                    static::InsertUpdateMeta($id, $e_name, $page->GetMeta($e_name, ''), $toLang);
                }
            }
        }
        return $done;
    }
    
    /* CACHE */
    public static function GetCacheKey($id, $lang=null)
    {
        $lang = is_null($lang) ? I18N::GetLanguage() : $lang;
        $key = 'Page_' . $lang;
        return $key;
    }
    
    public static function GetCache($id, $lang=null)
    {
        $rel = static::GetCacheKey($id, $lang);
        return IcebergCache::GetObject($id, $rel);
        
    }
    
    public static function AddCache($id, $object, $lang=null)
    {
        $rel = static::GetCacheKey($id, $lang);
        return IcebergCache::AddObject($id, $object, $rel);
    }
    
    public static function GetCacheListKey($args, $lang=null)
    {
        $lang = is_null($lang) ? I18N::GetLanguage() : $lang;
        $key = 'PageList_' . $lang;
        return $key;
    }
    
    public static function GetCacheListID($args, $lang=null)
    {
        $key = md5(json_encode($args));
        return $key;
    }
    
    public static function GetCacheList($args, $lang=null)
    {
        $found = false;
        $list = array();
        $id = static::GetCacheListID($args, $lang);
        $rel = static::GetCacheListKey($args, $lang);
        $ids = IcebergCache::GetObject($id, $rel);
        if (is_array($ids))
        {
            $found = true;
            foreach ($ids AS $id)
            {
                if (abs(intval($id))>0)
                {
                    $cache = static::GetCache($id, $lang);
                    if ($cache === false)
                    {
                        $found = false;
                        break;
                    }
                    else
                    {
                        $list[$id] = $cache;
                    }
                }
            }
        }
        return $found ? $list : false;
    }
    
    public static function AddCacheList($args, $list, $lang=null)
    {
        $done = true;
        foreach ($list AS $id => $object)
        {
            $done = static::AddCache($id, $object, $lang);
            if (!$done) { break; }
        }
        if ($done)
        {
            $id = static::GetCacheListID($args, $lang);
            $rel = static::GetCacheListKey($args, $lang);
            $ids = array_keys($list);
            return IcebergCache::AddObject($id, $ids, $rel);
        }
        return false;
    }
    
    public static function GetCacheListIDsKey($args, $lang=null)
    {
        $lang = is_null($lang) ? I18N::GetLanguage() : $lang;
        $key = 'PageListIDs_' . $lang;
        return $key;
    }
    
    public static function GetCacheListIDsID($args, $lang=null)
    {
        $key = md5(json_encode($args));
        return $key;
    }
    
    public static function GetCacheListIDs($args, $lang=null)
    {
        $id = static::GetCacheListIDsID($args, $lang);
        $rel = static::GetCacheListIDsKey($args, $lang);
        return IcebergCache::GetObject($id, $rel);
    }
    
    public static function AddCacheListIDs($args, $list, $lang=null)
    {
        $id = static::GetCacheListIDsID($args, $lang);
        $rel = static::GetCacheListIDsKey($args, $lang);
        return IcebergCache::AddObject($id, $list, $rel);
    }
    
    /* GET */
    public static function GetChildsDependences($id, $recursive=true, $done=array(), $cache=true)
    {
        $childs = static::DB_SelectChildRelation($id, static::RELATION_KEY_PAGE_DEPENDENCE, null, null);
        foreach ($childs AS $child)
        { 
            if (!in_array((int)$child->cid, $done))
            {
                $done[] = (int)$child->cid;
                if ($recursive)
                {
                    $done = array_merge($done, static::GetChildsDependences((int)$child->cid, $recursive, $done));
                }
            }
        }
        $done = array_unique($done);
        return $done;
    }
    public static function GetChildsRelations($id, $recursive=true, $done=array(), $cache=true)
    {
        $childs = static::DB_SelectChildRelation($id, static::RELATION_KEY_PAGE, null, null);
        foreach ($childs AS $child)
        { 
            if (!in_array((int)$child->cid, $done))
            {
                $done[] = (int)$child->cid;
                if ($recursive)
                {
                    $done = array_merge($done, static::GetChildsDependences((int)$child->cid, $recursive, $done));
                }
            }
        }
        $done = array_unique($done);
        return $done;
    }
    
    public static function GetListIDs($args=array(), $lang=null, $cache=true)
    {
        if ($cache)
        {
            $obj = static::GetCacheListIDs($args, $lang);
            if ($obj !== false)
            {
                return $obj;
            }
        }
        $return = array();
        $fields = array('id');
        $where = static::GetWhereFields($args);
        $orderby = static::GetOrderFields($args);
        $limit = static::GetLimitFields($args);
        $relations = static::GetRelationsFields($args);
        $pages = static::DB_Select($fields, $where, $orderby, $limit, $relations, $lang);
        
        foreach ($pages AS $page)
        {
            $return[(int)$page->id] = (int)$page->id;
        }
        
        static::AddCacheListIDs($args, $return, $lang);
        
        return $return;
    }
    
    
    public static function GetCountList($args=array(), $lang=null, $cache=true)
    {
        unset($args['items']);
        unset($args['page']);
        unset($args['page_items']);
        $pages = static::GetListIDs($args, $lang, $cache);
        return count($pages);
    }
    
    public static function GetList($args=array(), $lang=null, $cache=true, $metas=true)
    {
        if ($cache)
        {
            $obj = static::GetCacheList($args, $lang);
            if ($obj !== false)
            {
                return $obj;
            }
        }
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
        
        if ($metas)
        {
            static::LoadListMetas($pages, $lang, false, $metas);
        }
        
        if (isset($args['order']))
        {
            if ($args['order'] === 'name')
            {
                uasort($pages , 'page_sort_by_name');
            }
        }
        reset($pages);
        
        static::AddCacheList($args, $pages, $lang);
        
        return $pages;
    }
    
    public static function LoadListMetas($pages, $lang=null, $cache=true, $metakeys=array())
    {
        if (!is_array($pages) || empty($pages))
        {
            return false;
        }
        $metakeys = is_array($metakeys) ? $metakeys : array();
        $ids = array_keys($pages);
        $metas = static::GetListMetas($ids, $lang, $metakeys);
        if (!empty($metas))
        {
            foreach ($metas AS $meta)
            {
                if (isset($pages[$meta->pid]))
                {
                    $pages[$meta->pid]->SetMeta($meta->name, static::DB_DecodeFieldValue($meta->value), $meta->lang, false);
                }
            }
        }
        
        if ($cache)
        {
            foreach ($pages AS $id => $page)
            {
                static::AddCache($id, $page, $lang);
            }
        }
        
        //var_dump($ids);
        //var_dump($metas);
        //die();
        /*$this->metas = !is_array($this->metas) ? array() : $this->metas;
        $metas = static::GetMetas($this->id, $lang);
        if (!empty($metas))
        {
            foreach ($metas AS $meta)
            {
                $this->SetMeta($meta->name, static::DB_DecodeFieldValue($meta->value), $meta->lang, false);
            }
            static::AddCache($this->id, $this, $lang);
        }*/
    }
    
    public static function GetPageID()
    {
        $id = get_request_page();
        return $id;
    }
    
    public static function GetPage($id=null, $lang=null, $cache=true)
    {
        if (is_null($id))
        {
            $id =  static::GetPageID();
            
        }
        if (is_null($id) || intval($id)<1)
        {
            return new Page();
        }
        if ($cache)
        {
            $obj = static::GetCache($id, $lang);
            if ($obj !== false)
            {
                return $obj;
            }
        }
        $fields = static::GetSelectFields();
        $where = static::GetWhereFields(array('id' => $id));
        $pages = static::DB_Select($fields, $where, array(), array(0, 1), array(), $lang);
        if (is_array($pages) && !empty($pages))
        {
            $page = current($pages);
            $page = static::GetPageFromObject($page, $lang);
            static::AddCache($id, $page, $lang);
            return $page;
            
        }
        return new Page();
    }
    
    public static function GetPageByPermalink($permalink, $lang=null, $cache=true)
    {
        $pid = PageMeta::GetParentID(PageMeta::META_PERMALINK, $permalink, $lang);
        return static::GetPage($pid, $lang, $cache);
    }
    
    public static function MakePermalink($string, $separator='-')
    {
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array( '&' => 'and');
        $string = mb_strtolower( trim( $string ), 'UTF-8' );
        $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
        $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);
        return $string;
    }
    
    public static function GetMetas($id, $lang=null)
    {
        return static::DB_SelectChilds(
            $id,
            static::RELATION_KEY_META,
            null,
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
            array(), 
            $lang
        );
    }
    
    public static function GetListMetas($ids, $lang=null, $metakeys=array())
    {
        $where = (is_array($metakeys) && !empty($metakeys)) ? array('name'=>$metakeys) : array();
        return static::DB_SelectChilds(
            $ids,
            static::RELATION_KEY_META,
            null,
            array(
                'name', 
                'value', 
                PageMeta::RELATION_KEY_PAGE => array(
                    array(DBRelation::GetLanguageField(), 'lang'),
                    array(DBRelation::GetParentField(), 'pid')
                )
            ), 
            $where, 
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
            //'metas' => static::METAS_FORCE_LOAD
            'metas' => false
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
            else if ($args['order'] == 'random')
            {
                $arr[] = 'RAND()';
            }
            else if ($args['order'] == 'tree')
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
            $items_start = isset($args['items_start']) ? (int)$args['items_start'] : 0;
            $items = (int)$args['items'];
            $arr = array(
                $items_start,
                $items
            );
        }
        else if (isset($args['page']))
        {
            $page = (int)$args['page'];
            $page_items = isset($args['page_items']) ? (int)$args['page_items'] : 20;
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
        if (isset($args['dependences']) && is_array($args['dependences']))
        {
            $arr[static::RELATION_KEY_PAGE_DEPENDENCE] = $args['dependences'];
        }
        if (isset($args['relateds']) && is_array($args['relateds']))
        {
            $arr[static::RELATION_KEY_PAGE] = $args['relateds'];
        }
        if (isset($args['created']))
        {
            $arr[static::RELATION_KEY_USER_CREATE] = $args['created'];
        }
        if (isset($args['metas']) && is_array($args['metas']))
        {
            $arr[static::RELATION_KEY_META] = array();
            foreach ($args['metas'] AS $k => $v)
            {
                $arr[static::RELATION_KEY_META][$k] = $v;
            }
            
        }
        if (isset($args['search']) && !empty($args['search']))
        {
            if (!isset($arr[static::RELATION_KEY_META]))
            {
                $arr[static::RELATION_KEY_META] = array();
            }
            $arr[static::RELATION_KEY_META][PageMeta::META_TITLE . '#' . PageMeta::META_TEXT] = $args['search'];
        }
        return $arr;
    }
}
