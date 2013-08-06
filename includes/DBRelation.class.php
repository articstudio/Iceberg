<?php

/**
 * Database Relation
 * 
 * Manage relation table of database
 *  
 * @package Iceberg
 * @subpackage Database
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0 
 */
class DBRelation extends ObjectDB
{
    /**
     * DB table name
     * @var string
     */
    public static $DB_TABLE = 'ICEBERG_DB_RELATIONS';
    
    /**
     * List of fields
     * @var array
     */
    public static $DB_FIELDS = array(
        'pid' => array(
            'name' => 'PARENT ID 1',
            'type' => 'BIGINT',
            'length' => '20',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'cid' => array(
            'name' => 'PARENT ID 2',
            'type' => 'BIGINT',
            'length' => '20',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'name' => array(
            'name' => 'RELATION NAME',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'language' => array(
            'name' => 'LANGUAGE',
            'type' => 'VARCHAR',
            'length' => '10',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'count' => array(
            'name' => 'ORDER',
            'type' => 'BIGINT',
            'length' => '20',
            'flags' => array(
                'NOT NULL',
                'DEFAULT \'1\''
            ),
            'index' => true
        ),
    );
    
    public static $DB_PARENT_FIELD = 'pid';
    
    public static $DB_CHILD_FIELD = 'cid';
    
    public static $DB_NAME_FIELD = 'name';
    
    public static $DB_LANGUAGE_FIELD = 'language';
    
    public static $DB_COUNT_FIELD = 'count';
    
    public static function GetParentField()
    {
        return static::$DB_PARENT_FIELD;
    }
    
    public static function GetChildField()
    {
        return static::$DB_CHILD_FIELD;
    }
    
    public static function GetNameField()
    {
        return static::$DB_NAME_FIELD;
    }
    
    public static function GetLanguageField()
    {
        return static::$DB_LANGUAGE_FIELD;
    }
    
    public static function GetCountField()
    {
        return static::$DB_COUNT_FIELD;
    }
    
}
