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
            'language' => false
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
    
}


class Page extends PageBase
{
    
    public function __construct($group=null, $parent=null, $taxonomy=null, $type=null, $metas=array(), $lang=null)
    {
        
    }
}
