<?php

/** Include helpers taxonomy file */
require_once ICEBERG_DIR_HELPERS . 'taxonomy.php';

/**
 * Taxonomy
 * 
 * Taxonomy management
 *  
 * @package Iceberg
 * @author Marc Mascort Bou
 * @version 1.0
 */

abstract class Taxonomy extends ObjectDBRelations
{
    /**
     * DB table name
     * @var string
     */
    public static $DB_TABLE = 'ICEBERG_DB_TAXONOMY';
    
    /**
     * List of fields
     * @var array
     */
    public static $DB_FIELDS = array(
        'name' => array(
            'name' => 'TAXONOMY NAME',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'value' => array(
            'name' => 'TAXONOMY VALUE',
            'type' => 'LONGTEXT',
            'length' => null,
            'flags' => array(
                'NOT NULL'
            ),
            'index' => false
        )
    );
    
    /**
     * Parents relation
     * @var array 
     */
    public static $DB_PARENTS = array(
        'taxonomy-domain' => array(
            'object' => 'Domain',
            'force' => true,
            'function' => 'get_domain_request_id',
            'language' => false
        )
    );
    
    const RELATION_KEY_DOMAIN = 'taxonomy-domain';
    
    
    
    
}
