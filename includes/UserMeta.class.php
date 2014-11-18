<?php

/**
 * User Metas
 * 
 * User metas management
 *  
 * @package Iceberg
 * @subpackage User
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 */
class UserMeta extends ObjectDBRelations
{
    /**
     * DB table name
     * @var string
     */
    public static $DB_TABLE = 'ICEBERG_DB_USERS_METAS';
    
    /**
     * List of fields
     * @var array
     */
    public static $DB_FIELDS = array(
        'name' => array(
            'name' => 'META NAME',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => array(),
            'index' => true
        ),
        'value' => array(
            'name' => 'META VALUE',
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
        'user2meta' => array(
            'object' => 'User',
            'force' => true,
            'function' => 'get_user_id',
            'language' => true
        )
    );
    
    const RELATION_KEY_USER = 'user-meta';
    
}