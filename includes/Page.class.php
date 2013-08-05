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
        'Domains' => array(
            'name' => 'page-domain',
            'force' => true,
            'function' => 'get_domain_request_id',
            'language' => true
        ),
        'Group' => array(
            'name' => 'page-group',
            'force' => false,
            'function' => '',
            'language' => false
        ),
        'Page' => array(
            'name' => 'page-parent',
            'force' => false,
            'function' => '',
            'language' => false
        )
    );
    
    const TYPE_DEFAULT = 'page';
    
    const STATUS_ACTIVE = 1;
    const STATUS_UNACTIVE = 0;
}


class Page extends PageBase
{
    
    public function __construct($group=null, $parent=null, $taxonomy=null, $type=null, $metas=array(), $lang=null)
    {
        
    }
    
    public static function Insert($group=null, $parent=null, $taxonomy=null, $type=null, $metas=array(), $lang=null)
    {
        $args = array(
            'taxonomy' => is_null($taxonomy) ? '' : $taxonomy,
            'type' => is_null($type) ? static::TYPE_DEFAULT : $type,
            'status' => static::STATUS_ACTIVE
        );
        $relations = array(
            'Group' => $group,
            'Page' => $parent
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
    
    public static function GetList($args=array())
    {
        $fields = array(
            'taxonomy',
            'type',
            'status'
        );
        $where = array();
        if (isset($args['taxonomy']))
        {
            $where['taxonomy'] = $args['taxonomy'];
        }
        if (isset($args['type']))
        {
            $where['type'] = $args['type'];
        }
        if (isset($args['status']))
        {
            $where['status'] = $args['status'];
        }
        $orderby = array();
        $limit = array();
        $relations = array();
        /*@todo Relations */
        $lang = null;
        $pages = static::DB_Select($fields, $where, $orderby, $limit, $relations, $lang);
        /*@todo CACHE / METAS */
        return $pages;
    }
}
