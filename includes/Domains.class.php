<?php

/** Include domains request file */
require_once ICEBERG_DIR_HELPERS . 'domains.php';

/**
 * Domains
 * 
 * Domains management
 *  
 * @package Iceberg
 * @subpackage Routing
 * @author Marc Mascort Bou
 * @version 1.0
 */
class Domains extends ObjectDBRelations
{
    /**
     * DB table name
     * @var string
     */
    public static $DB_TABLE = 'ICEBERG_DB_DOMAINS';
    
    /**
     * List of fields
     * @var array
     */
    public static $DB_FIELDS = array(
        'name' => array(
            'name' => 'DOMAIN NAME',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        )
    );
    
    /**
     * Parents relation
     * @var array 
     */
    public static $DB_PARENTS = array(
        'domain-canonical' => array(
            'object' => 'Domains'
        )
    );
    
    const RELATION_KEY_CANONICAL = 'domain-canonical';
    
    /**
     * Request key for Domain ID
     */
    const REQUEST_KEY_ID = 'domain-id';
    
    /**
     * Initialize domain
     * 
     * @global int $__DOMAIN_ID Actual domain ID
     * @global int $__DOMAIN_CANONICAL Canonical domain ID
     */
    static public function Initialize()
    {
        global $__DOMAIN_ID, $__DOMAIN_CANONICAL;
        $domain = Request::GetBaseUrl(false);
        $__DOMAIN_ID = self::GetRequestID();
        $__DOMAIN_CANONICAL = Request::GetProtocol() . '://' . $domain;
        if (is_null($__DOMAIN_ID))
        {
            $domains = self::DB_Select(
                array(
                    'id',
                    array(
                        'relation' => static::RELATION_KEY_CANONICAL,
                        'fields' => array(
                            'id',
                            'name'
                        )
                    )
                ),
                array(
                    'name' => $domain
                )
            );
            if (count($domains) > 0)
            {
                $row = current($domains);
                $__DOMAIN_ID = $row->id;
            }
        }
        $parent = self::GetParentObjectsID($__DOMAIN_ID, 'domain-canonical');
        if ($parent)
        {
            $__DOMAIN_ID = current($parent);
            $domains = self::DB_Select(
                array('name'),
                array(
                    'id' => $__DOMAIN_ID
                )
            );
            if (count($domains) > 0)
            {
                $row = current($domains);
                $__DOMAIN_CANONICAL = Request::GetProtocol() . '://' . $row->name;
            }
        }
        return $__DOMAIN_ID;
        //self::SetRequestID($__DOMAIN_ID);
    }
    
    /**
     * Get request domain id
     * 
     * @return int|null 
     */
    static public function GetRequestID()
    {
        $id = get_request_sgp(self::REQUEST_KEY_ID, null);
        return is_null($id) ? self::GetID() : $id;
    }
    
    /**
     * Set session domain id
     * 
     * @param int $id
     * @return boolean
     */
    static public function SetRequestID($id)
    {
        return set_session_value(self::REQUEST_KEY_ID, $id);
    }
    
    /**
     * Set domain ID
     * @global int $__DOMAIN_ID
     * @param int $id
     * @return boolean 
     */
    static public function SetDomainID($id)
    {
        global $__DOMAIN_ID;
        $__DOMAIN_ID = $id;
        return self::SetRequestID($id);
    }
    
    /**
    * Returns domain ID
    * 
    * @global int $__DOMAIN_ID
    * @return int 
    */
    static public function GetID()
    {
        global $__DOMAIN_ID;
        return $__DOMAIN_ID;
    }
    
    public static function GetCanonical()
    {
        global $__DOMAIN_CANONICAL;
        return $__DOMAIN_CANONICAL;
    }
    
    public static function GetName()
    {
        $domain = static::GetCanonical();
        $pos = strpos($domain, '://');
        if ($pos)
        {
            $domain = substr($domain, $pos+3);
        }
        if (substr($domain, -1) == '/')
        {
            $domain = substr($domain, 0, -1);
        }
        return $domain;
    }
    
    public static function GetCanonicals()
    {
        return static::DB_Select(array('name'), array(), array(), array(), array('Domains'=>null));
    }
    
    public static function GetAlias($id=null)
    {
        $id = is_null($id) ? static::GetID() : $id;
        return static::DB_Select(array('name'), array(), array(), array(), array('Domains'=>$id));
    }
    
    public static function GetTree()
    {
        $items = static::GetCanonicals();
        foreach($items AS $k => $v)
        {
            $items[$k]->alias = static::GetAlias($k);
        }
        return $items;
    }
}
