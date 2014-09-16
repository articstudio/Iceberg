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
        
        //var_dump($domain);
        //var_dump($domain_name);
        //var_dump($domain_id);
        
        if ($domain)
        {
            $domain_id = $domain->GetCanonicalID();
            $domain_name = $domain->GetCanonicalDomain();
        }
        else
        {
            //$domain_id = -1;
            $domain_name = Request::GetProtocol() . '://' . $domain_name;
        }
        static::SetDomainID($domain_id);
        static::SetDomainCanonical($domain_name);
        //die();
        
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
    
    
    /* CACHE */
    public static function GetCacheKey($id)
    {
        $key = 'Domain';
        return $key;
    }
    
    public static function GetCache($id)
    {
        $rel = static::GetCacheKey($id);
        return IcebergCache::GetObject($id, $rel);
        
    }
    
    public static function AddCache($id, $object)
    {
        $rel = static::GetCacheKey($id);
        return IcebergCache::AddObject($id, $object, $rel) ? IcebergCache::AddObject($object->name, $object, $rel) : false;
    }
    
    public static function GetCacheListKey($args)
    {
        $key = 'DomainList';
        return $key;
    }
    
    public static function GetCacheListID($args)
    {
        $key = md5(json_encode($args));
        return $key;
    }
    
    public static function GetCacheList($args)
    {
        $found = false;
        $list = array();
        $id = static::GetCacheListID($args);
        $rel = static::GetCacheListKey($args);
        $ids = IcebergCache::GetObject($id, $rel);
        if (is_array($ids))
        {
            $found = true;
            foreach ($ids AS $id)
            {
                $cache = static::GetCache($id);
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
        return $found ? $list : false;
    }
    
    public static function AddCacheList($args, $list)
    {
        $done = true;
        foreach ($list AS $id => $object)
        {
            $done = static::AddCache($id, $object);
            if (!$done) { break; }
        }
        if ($done)
        {
            $id = static::GetCacheListID($args);
            $rel = static::GetCacheListKey($args);
            $ids = array_keys($list);
            return IcebergCache::AddObject($id, $ids, $rel);
        }
        return false;
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
    var $childs;
    
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
        return $this->parent === false ? $this->name : $this->parent->GetCanonicalName();
    }
    
    public function GetCanonicalDomain()
    {
        return Request::GetProtocol() . '://' . $this->GetCanonicalName();
    }
    
    public static function GetDomain($id=null, $cache=true)
    {
        return static::GetDomainByID($id, $cache);
    }
    
    public static function GetDomainByID($id=null, $cache=true)
    {
        $id = is_null($id) ? static::GetID() : $id;
        if ($cache)
        {
            $obj = static::GetCache($id);
            if ($obj !== false)
            {
                return $obj;
            }
        }
        $fields = static::GetSelectFields();
        $where = array(static::$DB_PRIMARY_FIELD=>$id);
        $domains = self::DB_Select($fields, $where);
        if (count($domains) > 0)
        {
            $row = current($domains);
            $domain = new Domain($row->id, $row->name, $row->pid);
            static::AddCache($row->id, $domain);
            return $domain;
        }
        return false;
    }
    
    public static function GetDomainByName($name, $cache=true)
    {
        $name = is_null($name) ? static::GetName() : $name;
        if ($cache)
        {
            $obj = static::GetCache($name);
            if ($obj !== false)
            {
                return $obj;
            }
        }
        $fields = static::GetSelectFields();
        $where = array('name'=>$name);
        $domains = self::DB_Select($fields, $where);
        if (count($domains) > 0)
        {
            $row = current($domains);
            $domain = new Domain($row->id, $row->name, $row->pid);
            static::AddCache($row->id, $domain);
            return $domain;
        }
        return false;
    }
    
    public static function GetDomains($cache=true)
    {
        $fields = static::GetSelectFields();
        $where = array();
        $orderby = array('name');
        if ($cache)
        {
            $obj = static::GetCacheList($where);
            if ($obj !== false)
            {
                return $obj;
            }
        }
        $domains = self::DB_Select($fields, $where, $orderby);
        if (count($domains) > 0)
        {
            foreach ($domains AS $key => $domain)
            {
                $domains[$key] = new Domain($key, $domain->name, $domain->pid);
            }
        }
        reset($domains);
        static::AddCacheList($where, $domains);
        return $domains;
    }
    
    public static function GetDomainsByParent($id, $cache=true)
    {
        $fields = static::GetSelectFields();
        $where = array();
        $orderby = array('name');
        $limit = array();
        $relations = array(static::RELATION_KEY_CANONICAL => $id);
        if ($cache)
        {
            $obj = static::GetCacheList($relations);
            if ($obj !== false)
            {
                return $obj;
            }
        }
        $domains = self::DB_Select($fields, $where, $orderby, $limit, $relations);
        if (count($domains) > 0)
        {
            foreach ($domains AS $key => $domain)
            {
                $domains[$key] = new Domain($key, $domain->name, $domain->pid);
            }
        }
        reset($domains);
        static::AddCacheList($relations, $domains);
        return $domains;
    }
    
    public static function GetCanonicals($cache=true)
    {
        return static::GetDomainsByParent(null, $cache);
    }
    
    
    
    
    
    protected static function GetSelectFields()
    {
        return array(
            'id',
            'name',
            static::RELATION_KEY_CANONICAL => array(
                array(
                    DBRelation::GetParentField(),
                    'pid'
                )
            )
        );
    }
}
