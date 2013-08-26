<?php

/** Include helpers request file */
require_once ICEBERG_DIR_HELPERS . 'user.php';

class UserBase extends ObjectDBRelations
{
    /**
     * DB table name
     * @var string
     */
    public static $DB_TABLE = 'ICEBERG_DB_USERS';
    
    /**
     * List of fields
     * @var array
     */
    public static $DB_FIELDS = array(
        'email' => array(
            'name' => 'E-MAIL',
            'type' => 'VARCHAR',
            'length' => '250',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'username' => array(
            'name' => 'USERNAME',
            'type' => 'VARCHAR',
            'length' => '50',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'password' => array(
            'name' => 'PASSWORD',
            'type' => 'VARCHAR',
            'length' => '40',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => false
        ),
        'status' => array(
            'name' => 'STATUS',
            'type' => 'INT',
            'length' => '3',
            'flags' => array(
                'NOT NULL',
                'DEFAULT \'1\''
            ),
            'index' => true
        ),
        'level' => array(
            'name' => 'LEVEL',
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
        'user2domain' => array(
            'object' => 'Domain',
            'force' => true,
            'function' => 'get_domain_request_id'
        ),
        'user-user' => array(
            'object' => 'User'
        )
    );
    
    const RELATION_KEY_DOMAIN = 'user2domain';
    const RELATION_KEY_USER = 'user-user';
    
    const SESSION_USER = 'user';
    const SESSION_PASSWORD = 'password';
    const GP_SESSION_ID = 'session';
    
    const STATUS_ACTIVE = 1;
    const STATUS_UNACTIVE = 0;

    /**
     * Returns encrypted password
     * 
     * @uses action_event() for 'user_enctryptpass'
     * @param string $pass
     * @return string|null 
     */
    public static function EnctryptPassword($pass)
    {
        if (is_string($pass)) {
            $pass_encrypted = md5($pass);
            list($pass_encrypted) = action_event('user_enctryptpass', $pass_encrypted, $pass);
            return $pass_encrypted;
        }
        else { return null; }
    }
    
    
    public static function IsLogged()
    {
        global $__USER_LOGGED;
        return $__USER_LOGGED;
    }
    
    public static function IsLogin()
    {
        global $__USER_LOGIN;
        return $__USER_LOGIN;
    }
    
}

/**
 * User
 * 
 * User management
 *  
 * @package Iceberg
 * @subpackage User
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 */
class User extends UserBase
{
    var $id;
    var $email;
    var $username;
    var $password;
    var $status;
    var $level;
    var $metas;
    
    const METAS_FORCE_LOAD = 'METAS_FORCE_LOAD';
    
    public function __construct($args=array(), $lang=null)
    {
        $this->id = isset($args['id']) ? $args['id'] : -1;
        $this->email = isset($args['email']) ? $args['email'] : '';
        $this->username = isset($args['username']) ? $args['username'] : '';
        $this->password = isset($args['password']) ? $args['password'] : '';
        $this->status = isset($args['status']) ? $args['status'] : static::STATUS_ACTIVE;
        $this->level = isset($args['level']) ? $args['level'] : 0;
        $this->lang = is_null($lang) ? I18N::GetLanguage() : $lang;
        $this->metas = isset($args['metas']) ? $args['metas'] : static::METAS_FORCE_LOAD;
        if ($this->metas === static::METAS_FORCE_LOAD)
        {
            $this->LoadMetas($this->lang);
        }
    }
    
    public function GetLevelName()
    {
        $foundLevel = 0;
        $foundName = '';
        $levels = Session::GetLevels();
        foreach ($levels AS $level => $name)
        {
            if ($this->level > $level && $foundLevel < $level)
            {
                $foundLevel = $level;
                $foundName = $name;
            }
        }
        return $foundName;
    }
    
    public function LoadMetas($lang=null)
    {
        $this->metas = !is_array($this->metas) ? array() : $this->metas;
        $metas = static::GetMetas($this->id, $lang);
        foreach ($metas AS $meta)
        {
            $this->SetMeta($meta->name, static::DB_DecodeFieldValue($meta->value), $meta->lang);
        }
    }
    
    public function SetMeta($key, $value, $lang=null)
    {
        $lang = is_null($lang) ? $this->lang : $lang;
        $this->metas = !is_array($this->metas) ? array() : $this->metas;
        $this->metas[$lang] = (!isset($this->metas[$lang]) || !is_array($this->metas[$lang])) ? array() : $this->metas[$lang];
        $this->metas[$lang][$key] = $value;
    }
    
    public function SaveMeta($key, $value, $lang=null)
    {
        $lang = is_null($lang) ? $this->lang : $lang;
        $done = $this->SetMeta($key, $value, $lang);
        if ($done)
        {
            return static::InsertUpdateMeta($this->id, $key, $value, $lang);
        }
        return $done;
    }
    
    public function GetMeta($key, $default=false, $lang=null)
    {
        $lang = is_null($lang) ? $this->lang : $lang;
        if (is_array($this->metas) && isset($this->metas[$lang]))
        {
            if (is_array($this->metas[$lang]) && isset($this->metas[$lang][$key]))
            {
                return $this->metas[$lang][$key];
            }
        }
        return $default;
    }
    
    public function HasMeta($key, $lang=null)
    {
        $lang = is_null($lang) ? get_lang() : $lang;
        if (is_array($this->metas) && isset($this->metas[$lang]))
        {
            if (is_array($this->metas[$lang]) && isset($this->metas[$lang][$key]))
            {
                return true;
            }
        }
        return false;
    }
    
    public static function GetList($args=array(), $lang=null)
    {
        $obj = static::GetCacheList($args, $lang);
        if ($obj !== false)
        {
            return $obj;
        }
        $fields = static::GetSelectFields();
        $where = static::GetWhereFields($args);
        $orderby = static::GetOrderFields($args);
        $limit = static::GetLimitFields($args);
        $relations = static::GetRelationsFields($args);
        $users = static::DB_Select($fields, $where, $orderby, $limit, $relations, $lang);
        
        foreach ($users AS $k => $user)
        {
            $users[$k] = static::GetUserFromObject($user, $lang);
        }
        reset($users);
        
        static::AddCacheList($args, $users, $lang);
        
        return $users;
    }
    
    public static function GetUser($id=null, $lang=null)
    {
        if (is_null($id))
        {
            global $__USER;
            return $__USER;
        }
        $obj = static::GetCache($id, $lang);
        if ($obj !== false)
        {
            return $obj;
        }
        $fields = static::GetSelectFields();
        $where = static::GetWhereFields(array('id' => $id));
        $users = static::DB_Select($fields, $where, array(), array(0, 1), array(), $lang);
        if (is_array($users) && !empty($users))
        {
            $user = current($users);
            $user = static::GetUserFromObject($user, $lang);
            static::AddCache($id, $user, $lang);
            return $user;
            
        }
        return new User();
    }
    
    public static function GetMetas($id, $lang=null)
    {
        return static::DB_SelectChild(
            'UserMeta', 
            $id, 
            array(
                'name', 
                'value', 
                UserMeta::RELATION_KEY_USER => array(
                    array(DBRelation::GetLanguageField(), 'lang')
                )
            ), 
            array(), 
            array(), 
            array(), 
            $lang
        );
    }
    
    public static function GetID()
    {
        if (self::IsLogged())
        {
            $user = self::GetUser();
            return $user->id;
        }
        return false;
    }
    
    public static function GetUsername()
    {
        if (self::IsLogged())
        {
            $user = self::GetUser();
            return $user->username;
        }
        return '';
    }
    
    public static function GetLevel()
    {
        if (self::IsLogged())
        {
            $user = self::GetUser();
            return $user->level;
        }
        return Session::GetMinimumLevel();
    }
    
    public static function IsAdmin()
    {
        return (static::GetLevel() >= Session::GetAdminLevel());
    }
    
    protected static function GetUserFromObject($obj, $lang=null)
    {
        $args = array(
            'id' => $obj->id,
            'email' => $obj->email,
            'username' => $obj->username,
            'password' => $obj->password,
            'status' => $obj->status,
            'level' => $obj->level,
            'metas' => static::METAS_FORCE_LOAD
        );
        return new User($args, $lang);
    }
    
    protected static function GetSelectFields()
    {
        return array(
            'id',
            'email',
            'username',
            'password',
            'status',
            'level'
        );
    }
    
    protected static function GetWhereFields($args=array())
    {
        $arr = array();
        if (isset($args['id']))
        {
            $arr['id'] = $args['id'];
        }
        if (isset($args['email']))
        {
            $arr['email'] = $args['email'];
        }
        if (isset($args['username']))
        {
            $arr['username'] = $args['username'];
        }
        if (isset($args['password']))
        {
            $arr['password'] = $args['password'];
        }
        if (isset($args['status']))
        {
            $arr['status'] = $args['status'];
        }
        if (isset($args['level']))
        {
            $arr['level'] = $args['level'];
        }
        return $arr;
    }
    
    protected static function GetOrderFields($args=array())
    {
        $arr = array();
        $arr[static::RELATION_KEY_DOMAIN] = DBRelation::GetCountField();
        $arr[] = static::DB_GetPrimaryField();
        return $arr;
    }
    
    protected static function GetLimitFields($args)
    {
        $arr = array();
        if (isset($args['items']))
        {
            $items = (int)$args['items'];
            $arr = array(
                0,
                $items
            );
        }
        else if (isset($args['page']))
        {
            $page = (int)$args['page'];
            $page_items = isset($args['page_items']) ? (int)$args['page_items'] : 10;
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
        if (isset($args['user']))
        {
            $arr[static::RELATION_KEY_TYPE] = $args['user'];
        }
        return $arr;
    }
    
    

    /**
     * User login
     * 
     * @global boolean $__USER_LOGGED
     * @global boolean $__USER_LOGIN
     * @global object $__USER
     * @global string $__SESSION_ID
     * @param string $user
     * @param string $pass 
     */
    public static function Login($user=null, $pass=null)
    {
        global $__USER_LOGGED, $__USER_LOGIN, $__USER;
        $__USER_LOGGED = false;
        $__USER_LOGIN = (!is_null($user) && !is_null($pass));
        $user = is_null($user) ? Session::GetValue(self::SESSION_USER, null) : $user;
        $pass = is_null($pass) ? Session::GetValue(self::SESSION_PASSWORD, null) : self::EnctryptPassword($pass);
        $sessionId = Request::GetValueGP(self::GP_SESSION_ID, null);
        if (!is_null($user) && !is_null($pass))
        {
            $args = array(
                'username' => $user,
                'password' => $pass,
                'status' => static::STATUS_ACTIVE
            );
            $users = static::GetList($args);
            if (count($users) > 0)
            {
                $__USER = current($users);
                Session::SetValue(self::SESSION_USER, $user);
                Session::SetValue(self::SESSION_PASSWORD, $pass);
                $__USER_LOGGED = true;
            }
        }
        else if (!is_null($sessionId) && $sessionId==Session::GetID())
        {
            /**
             * @todo GP Session
             * (!is_null($sessionId) && $sessionId==Session::GetID())
             */
        }
        list($__USER, $__USER_LOGGED, $__USER_LOGIN) = action_event('user_login', $__USER, $__USER_LOGGED, $__USER_LOGIN);
        if ($__USER_LOGIN && $__USER_LOGGED)
        {
            self::RegisterLogin(self::GetID());
        }
        return $__USER_LOGGED;
    }
    
    public static function Logout($drop=false)
    {
        global $__USER, $__USER_LOGGED;
        $__USER = $__USER_LOGGED = false;
        if ($drop)
        {
            return Session::Stop(true);
        }
        return (Session::SetValue(self::SESSION_USER) && Session::SetValue(self::SESSION_PASSWORD));
    }
    
    public static function RegisterLogin($id)
    {
        $last = array(
            'ip' => getIP(),
            'session' => Session::GetID(),
            'time' => time()
        );
        list($last, $id) = action_event('user_register_login', $last, $id);
        static::DB_InsertUpdateChild(
            'UserMeta',
            array(
                'name' => UserMeta::META_LAST_VISIT,
                'value' => $last
            ),
            array(
                'name' => UserMeta::META_LAST_VISIT
            ),
            $id
        );
    }
    
    /* CACHE */
    public static function GetCacheKey($id, $lang=null)
    {
        $lang = is_null($lang) ? I18N::GetLanguage() : $lang;
        $key = 'User_' . $lang;
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
        $key = 'UserList_' . $lang;
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
}


