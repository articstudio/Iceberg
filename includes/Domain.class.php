<?php

/** Include domains request file */
require_once ICEBERG_DIR_HELPERS . 'domains.php';

class DomainBase extends ObjectDBRelations
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
            'object' => 'Domain'
        )
    );
    
    const RELATION_KEY_CANONICAL = 'domain-canonical';
    
    /**
     * Request key for Domain ID
     */
    const REQUEST_KEY_ID = 'domain-id';
    
    
    /**
     * Initialize domain
     */
    public static function Initialize()
    {
        $domain_name = Request::GetBaseUrl(false);
        $domain_id = static::GetRequestID();
        
        $domain = is_null($domain_id) ?  static::GetDomainByName($domain_name) : static::GetDomainByID($domain_id);
        
        $domain_id = $domain->GetCanonicalID();
        static::SetDomainID($domain_id);
        
        $domain_name = $domain->GetCanonicalDomain();
        static::SetDomainCanonical($domain_name);
        
        return $domain_id;
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
     * Set domain canonical
     * @global string $__DOMAIN_CANONICAL
     * @param string $canonical
     * @return boolean 
     */
    static public function SetDomainCanonical($canonical)
    {
        global $__DOMAIN_CANONICAL;
        $__DOMAIN_CANONICAL = $canonical;
        return true;
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
    
    public static function GetDomainByID($id)
    {
        $domains = self::DB_Select(
            array(
                'id',
                'name',
                static::RELATION_KEY_CANONICAL => array(
                    array(
                        DBRelation::GetParentField(),
                        'pid'
                    )
                )
            ),
            array(
                'id' => $id
            )
        );
        if (count($domains) > 0)
        {
            $row = current($domains);
            return new Domain($row->id, $row->name, $row->pid);
        }
        return false;
    }
    
    public static function GetDomainByName($name)
    {
        $domains = self::DB_Select(
            array(
                'id',
                'name',
                static::RELATION_KEY_CANONICAL => array(
                    array(
                        DBRelation::GetParentField(),
                        'pid'
                    )
                )
            ),
            array(
                'name' => $name
            )
        );
        if (count($domains) > 0)
        {
            $row = current($domains);
            return new Domain($row->id, $row->name, $row->pid);
        }
        return false;
    }
    
    
    
    
    
    /** @TODO */
    
    
    public static function GetCanonicals()
    {
        return static::DB_Select(array('name'), array(), array(), array(), array(static::RELATION_KEY_CANONICAL=>null));
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

/**
 * Domain
 * 
 * Domain management
 *  
 * @package Iceberg
 * @subpackage Routing
 * @author Marc Mascort Bou
 * @version 1.0
 */
class Domain extends DomainBase
{
    
    var $id;
    var $name;
    var $parent;
    
    public function __construct($id, $name, $parent=null) {
        $this->id = $id;
        $this->name = $name;
        $this->parent = is_null($parent) ? false : static::GetDomainByID($parent);
    }
    
    public function GetCanonicalID()
    {
        return $this->parent === false ? $this->id : $this->parent->GetCanonicalID();
    }
    
    public function GetCanonicalName()
    {
        return $this->parent === false ? $this->name : $this->parent->GetCanonicalDomain();
    }
    
    public function GetCanonicalDomain()
    {
        return Request::GetProtocol() . '://' . $this->GetCanonicalName();
    }
}
