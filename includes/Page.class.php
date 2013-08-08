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
        'taxonomy' => array(
            'name' => 'PAGE TAXONOMY',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'type' => array(
            'name' => 'PAGE TYPE',
            'type' => 'VARCHAR',
            'length' => '150',
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
            'object' => 'Domains',
            'force' => true,
            'function' => 'get_domain_request_id',
            'language' => true
        ),
        'page-group' => array(
            'object' => 'Group',
            'force' => false,
            'function' => '',
            'language' => false
        ),
        'page-parent' => array(
            'object' => 'Page',
            'force' => false,
            'function' => '',
            'language' => false
        )
    );
    
    const RELATION_KEY_DOMAIN = 'page-domain';
    const RELATION_KEY_GROUP = 'page-group';
    const RELATION_KEY_PARENT = 'page-parent';
}


class Page extends PageBase
{
    const TYPE_DEFAULT = 'page';
    
    const STATUS_ACTIVE = 1;
    const STATUS_UNACTIVE = 0;
    
    var $id;
    var $group;
    var $parent;
    var $taxonomy;
    var $type;
    var $status;
    var $order;
    var $metas;
    var $lang;
    
    const METAS_FORCE_LOAD = 'METAS_FORCE_LOAD';
    
    public function __construct($id, $type=null, $taxonomy=null, $group=null, $parent=null, $status=0, $order=0, $metas=array(), $lang=null)
    {
        $this->id = $id;
        $this->group = $group;
        $this->parent = $parent;
        $this->taxonomy = $taxonomy;
        $this->type = is_null($type) ? static::TYPE_DEFAULT : $type;
        $this->status = $status;
        $this->order = $order;
        $this->lang = is_null($lang) ? get_lang() : $lang;
        $this->metas = $metas;
        if ($this->metas === static::METAS_FORCE_LOAD)
        {
            $this->LoadMetas($this->lang);
        }
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
    
    
    public static function Insert($group=null, $parent=null, $taxonomy=null, $type=null, $metas=array(), $lang=null)
    {
        $args = array(
            'taxonomy' => is_null($taxonomy) ? '' : $taxonomy,
            'type' => is_null($type) ? static::TYPE_DEFAULT : $type,
            'status' => static::STATUS_ACTIVE
        );
        $relations = array(
            static::RELATION_KEY_GROUP => $group,
            static::RELATION_KEY_PARENT => $parent
        );
        $id = static::DB_Insert($args, $relations, $lang);
        if ($id)
        {
            $metas = PageMeta::Normalize($metas);
            foreach ($metas AS $key => $value)
            {
                $args = array(
                    'name' => $key,
                    'value' => $value
                );
                static::DB_InsertChild('PageMeta', $args, $id);
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
            $pages[$k] = new Page($page->id, $page->type, $page->taxonomy, $page->gid, $page->pid, $page->status, $page->count, Page::METAS_FORCE_LOAD, $lang);
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
            return new Page($page->id, $page->type, $page->taxonomy, $page->gid, $page->pid, $page->status, $page->count, Page::METAS_FORCE_LOAD, $lang);
            
        }
        return false;
    }
    
    
    protected static function GetSelectFields()
    {
        return array(
            'id',
            'taxonomy',
            'type',
            'status',
            static::RELATION_KEY_PARENT => array(
                array(DBRelation::GetParentField(), 'pid'),
                array(DBRelation::GetCountField(), 'count')
            ),
            static::RELATION_KEY_GROUP => array(
                array(DBRelation::GetParentField(), 'gid')
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
        if (isset($args['taxonomy']))
        {
            $arr['taxonomy'] = $args['taxonomy'];
        }
        if (isset($args['type']))
        {
            $arr['type'] = $args['type'];
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
            if ($args['order'] == 'name')
            {
                
            }
            else
            {
                $arr[static::RELATION_KEY_DOMAIN] = DBRelation::GetCountField();
                $arr[] = static::DB_GetPrimaryField();
            }
        }
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
