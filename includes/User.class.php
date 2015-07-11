<?php

/** Include helpers user file */
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
        'role' => array(
            'name' => 'ROLE',
            'type' => 'BIGINT',
            'length' => '20',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'capabilities' => array(
            'name' => 'CAPABILITIES',
            'type' => 'TEXT',
            'length' => null,
            'flags' => array(
                'NOT NULL'
            ),
            'index' => false
        ),
        'lastIP' => array(
            'name' => 'LAST IP',
            'type' => 'VARCHAR',
            'length' => '39',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => false
        ),
        'lastSession' => array(
            'name' => 'LAST SESSION',
            'type' => 'VARCHAR',
            'length' => '40',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => false
        ),
        'lastLogin' => array(
            'name' => 'LAST LOGIN',
            'type' => 'TIMESTAMP',
            'length' => null,
            'flags' => array(
                'NOT NULL'
            ),
            'index' => false
        ),
    );
    
    /**
     * Parents relation
     * @var array 
     */
    public static $DB_PARENTS = array(
        'user2domain' => array(
            'object' => 'Domain',
            'force' => true,
            'function' => 'get_domain_request_id',
            'language' => false
        ),
        'user-user' => array(
            'object' => 'User',
            'force' => false,
            'function' => '',
            'language' => false
        ),
        'user-page' => array(
            'object' => 'Page',
            'force' => false,
            'function' => '',
            'language' => false
        )
    );
    
    /**
     * Childs relation
     * @var array 
     */
    public static $DB_CHILDS = array(
        'user2meta' => array(
            'object' => 'UserMeta',
            'autodelete' => true
        ),
        'user-user' => array(
            'object' => 'User',
            'autodelete' => false
        ),
        'user2page' => array(
            'object' => 'Page',
            'autodelete' => false
        )
    );
    
    const RELATION_KEY_DOMAIN = 'user2domain';
    const RELATION_KEY_USER = 'user-user';
    const RELATION_KEY_PAGE = 'user-page';
    const RELATION_KEY_META = 'user2meta';
    const RELATION_KEY_PAGE_RELATED = 'user2page';
    
    const SESSION_USER = 'user';
    const SESSION_PASSWORD = 'password';
    const GP_SESSION_ID = 'session';
    const GP_REMEMBERME = 'rememberme';
    const COOKIE_REMEMBER = 'user_rememeber';
    
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
            $pass_encrypted = apply_filters('user_enctryptpass', $pass_encrypted, $pass);
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
    var $role;
    var $capabilities;
    var $metas;
    var $relations;
    
    const METAS_FORCE_LOAD = 'METAS_FORCE_LOAD';
    
    public function __construct($args=array(), $lang=null)
    {
        $this->id = (int)(isset($args['id']) ? $args['id'] : -1);
        $this->email = isset($args['email']) ? $args['email'] : '';
        $this->username = isset($args['username']) ? $args['username'] : '';
        $this->password = isset($args['password']) ? $args['password'] : '';
        $this->status = (int)(isset($args['status']) ? $args['status'] : static::STATUS_ACTIVE);
        $this->role = (int)(isset($args['role']) ? $args['role'] : -1);
        $this->capabilities = (isset($args['capabilities']) ? $args['capabilities'] : array());
        $this->lang = is_null($lang) ? I18N::GetLanguage() : $lang;
        $this->metas = isset($args['metas']) ? $args['metas'] : static::METAS_FORCE_LOAD;
        if ($this->metas === static::METAS_FORCE_LOAD)
        {
            $this->metas = array();
            if ($this->id!=-1)
            {
                $this->LoadMetas($this->lang);
            }
        }
        $this->relations = array();
        if ($this->id!=-1)
        {
            //$this->LoadRelations($this->lang);
        }
    }
    
    public function LoadMetas($lang=null)
    {
        $this->metas = !is_array($this->metas) ? array() : $this->metas;
        $metas = static::GetMetas($this->id, $lang);
        foreach ($metas AS $meta)
        {
            $this->SetMeta($meta->name, static::DB_DecodeFieldValue($meta->value), $lang);
        }
    }
    
    public function LoadRelations($lang=null)
    {
        $this->relations = array();
        $parents = static::DB_GetParents();
        foreach ($parents AS $rel => $parent)
        {
            $this->relations[$rel] = (isset($this->relations[$rel]) && is_array($this->relations[$rel])) ? $this->relations[$rel]  : array();
            $objs = static::DB_SelectParentRelation($this->id, $rel, null, $lang);
            if (is_array($objs))
            {
                foreach ($objs AS $obj)
                {
                    array_push($this->relations[$rel], $obj->pid);
                }
            }
        }
    }
    
    public function GetRelation($rel)
    {
        return is_array($this->relations[$rel]) ? $this->relations[$rel]  : array();
    }
    
    public function SetMeta($key, $value, $lang=null)
    {
        $lang = is_null($lang) ? $this->lang : $lang;
        $this->metas = !is_array($this->metas) ? array() : $this->metas;
        $this->metas[$lang] = (!isset($this->metas[$lang]) || !is_array($this->metas[$lang])) ? array() : $this->metas[$lang];
        $this->metas[$lang][$key] = $value;
        return true;
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
    
    public static function InsertUpdateMeta($id, $key, $value, $lang=null)
    {
        $args_meta = array(
            'name' => $key,
            'value' => $value
        );
        $where = array(
            'name' => $key
        );
        return static::DB_InsertUpdateChilds($id, static::RELATION_KEY_META, null, $args_meta, $where, array(), $lang);
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
    
    public static function Unactive($id)
    {
        $args = array(
            'status' => static::STATUS_UNACTIVE
        );
        return static::DB_Update($id, $args);
    }
    
    public static function Active($id)
    {
        $args = array(
            'status' => static::STATUS_ACTIVE
        );
        return static::DB_Update($id, $args);
    }
    
    public static function Insert($args=array(), $lang=null)
    {
        $insert_args = array(
            'email' => isset($args['email']) ? $args['email'] : '',
            'username' => isset($args['username']) ? $args['username'] : uniqid(),
            'password' => User::EnctryptPassword(isset($args['password']) ? $args['password'] : uniqid()),
            'role' => isset($args['role']) ? $args['role'] : -1,
            'capabilities' => isset($args['capabilities']) ? $args['capabilities'] : array(),
            'status' => isset($args['status']) ? $args['status'] : static::STATUS_ACTIVE
        );
        if (static::UsernameExists($insert_args['username']))
        {
            return false;
        }
        $relations_args = static::GetRelationsFields($args);
        $userID = static::DB_Insert($insert_args, $relations_args, $lang);
        return $userID;
    }
    
    public static function Update($id, $args=array(), $lang=null)
    {
        $update_args = array();
        if (isset($args['email']))
        {
            $update_args['email'] = $args['email'];
        }
        if (isset($args['username']))
        {
            $update_args['username'] = $args['username'];
        }
        if (isset($args['password']))
        {
            $update_args['password'] = User::EnctryptPassword($args['password']);
        }
        if (isset($args['role']))
        {
            $update_args['role'] = $args['role'];
        }
        if (isset($args['capabilities']))
        {
            $update_args['capabilities'] = $args['capabilities'];
        }
        if (isset($args['status']))
        {
            $update_args['status'] = $args['status'];
        }
        if (isset($args['lastIP']))
        {
            $update_args['lastIP'] = $args['lastIP'];
        }
        if (isset($args['lastSession']))
        {
            $update_args['lastSession'] = $args['lastSession'];
        }
        if (isset($args['lastLogin']))
        {
            $update_args['lastLogin'] = $args['lastLogin'];
        }
        if (isset($update_args['username']) && static::UsernameExists($update_args['username'], $id))
        {
            return false;
        }
        $done = static::DB_Update($id, $update_args, $lang);
        if ($done)
        {
            $relations_args = static::GetRelationsFields($args);
            foreach ($relations_args AS $rel => $parent)
            {
                static::DB_InsertUpdateParentRelation($id, $rel, null, $parent, $lang, null);
            }
        }
        return $done;
    }
    
    public static function Remove($id, $lang=null)
    {
        $where = static::GetWhereFields(array(static::DB_GetPrimaryField() => $id));
        return static::DB_Delete($where, array(), null);
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
        return static::DB_SelectChilds(
            $id,
            static::RELATION_KEY_META,
            null,
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
            array(),
            $lang
        );
    }
    
    public static function GetID()
    {
        if (self::IsLogged())
        {
            $user = self::GetUser();
            return (int)$user->id;
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
    
    public static function GetRole()
    {
        if (self::IsLogged())
        {
            $user = self::GetUser();
            return (int)$user->role;
        }
        return -1;
    }
    
    public static function GetRoleObject()
    {
        $role_id = static::GetRole();
        return UserRole::Get($role_id);
    }
    
    public static function GetCapabilities()
    {
        if (self::IsLogged())
        {
            $user = self::GetUser();
            return is_array($user->capabilities) ? $user->capabilities : array();
        }
        return array();
    }
    
    public static function HasCapability($capability)
    {
        $role = static::GetRoleObject();
        return $role->HasCapability($capability, static::GetCapabilities());
    }
    
    public static function HasFullCapability($capability)
    {
        $role = static::GetRoleObject();
        return $role->HasFullCapability($capability, static::GetCapabilities());
    }
    
    public static function HasOwnCapability($capability)
    {
        $role = static::GetRoleObject();
        return $role->HasOwnCapability($capability, static::GetCapabilities());
    }
    
    public static function IsAdmin()
    {
        return static::HasCapability(UserCapability::ADMIN_LOGIN);
    }
    
    protected static function GetUserFromObject($obj, $lang=null)
    {
        $args = array(
            'id' => $obj->id,
            'email' => $obj->email,
            'username' => $obj->username,
            'password' => $obj->password,
            'status' => $obj->status,
            'role' => $obj->role,
            'capabilities' => static::DB_DecodeFieldValue($obj->capabilities),
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
            'role',
            'capabilities'
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
        if (isset($args['role']))
        {
            $arr['role'] = $args['role'];
        }
        return $arr;
    }
    
    protected static function GetOrderFields($args=array())
    {
        $arr = array();
        if (isset($args['order']))
        {
            if ($args['order'] == 'username')
            {
                $arr[] = 'username';
            }
        }
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
        /*else if (isset($args['page']))
        {
            $page = (int)$args['page'];
            $page_items = isset($args['page_items']) ? (int)$args['page_items'] : 10;
            $arr = array(
                $page * $page_items,
                $page_items
            );
        }*/
        return $arr;
    }
    
    protected static function GetRelationsFields($args)
    {
        $arr = array();
        if (isset($args['user']))
        {
            $arr[static::RELATION_KEY_TYPE] = $args['user'];
        }
        if (isset($args['page']))
        {
            $arr[static::RELATION_KEY_PAGE] = $args['page'];
        }
        return $arr;
    }
    
    
    public static function UsernameExists($username, $excludeID=null)
    {
        $args = array(
            'username' => $username
        );
        $users = static::GetList($args);
        if (count($users) === 0)
        {
            return false;
        }
        else if ($excludeID===null && count($users)>0)
        {
            return true;
        }
        $excludeID = is_array($excludeID) ? $excludeID : array($excludeID);
        foreach ($users AS $user)
        {
            if (!in_array($user->id, $excludeID))
            {
                return true;
            }
        }
        return false;
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
    public static function Login($user=null, $pass=null, $login=null)
    {
        global $__USER_LOGGED, $__USER_LOGIN, $__USER;
        $__USER_LOGGED = false;
        $login = is_null($login) ? Request::GetValueGP('login') : $login;
        $__USER_LOGIN = (!is_null($user) && !is_null($pass) && nonce_check('login', $login));
        $user = $__USER_LOGIN ? $user : Session::GetValue(self::SESSION_USER, null);
        $pass = $__USER_LOGIN ? self::EnctryptPassword($pass) : Session::GetValue(self::SESSION_PASSWORD, null);
        $sessionId = Request::GetValueGP(self::GP_SESSION_ID, null);
        $cookie_user = Request::GetCookie(self::COOKIE_REMEMBER);
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
                $__USER = reset($users);
                Session::SetValue(self::SESSION_USER, $user);
                Session::SetValue(self::SESSION_PASSWORD, $pass);
                $__USER_LOGGED = true;
            }
            $rememberme = Request::GetValueGP(self::GP_REMEMBERME);
            if ($__USER_LOGIN)
            {
                if ($rememberme)
                {
                    $done = Request::SetCookie(self::COOKIE_REMEMBER, self::GetID() . '_' . IcebergSecurity::MakeNonce(self::COOKIE_REMEMBER), 3600 * 24 * 30);
                }
                else
                {
                    Request::DeleteCookie(self::COOKIE_REMEMBER);
                }
            }
        }
        else if (!$__USER_LOGIN && !is_null($sessionId) && $sessionId==Session::GetID())
        {
            /**
             * @todo GP Session
             * (!is_null($sessionId) && $sessionId==Session::GetID())
             */
        }
        else if (!$__USER_LOGIN && $cookie_user)
        {
            $cookie_user = explode('_', $cookie_user);
            $user_nonce = IcebergSecurity::MakeNonce(self::COOKIE_REMEMBER);
            if (count($cookie_user)===2)
            {
                $args = array(
                    'id' => $cookie_user[0],
                    'status' => static::STATUS_ACTIVE
                );
                $users = static::GetList($args);
                if (count($users) > 0 && $user_nonce === $cookie_user[1])
                {
                    $__USER = current($users);
                    Session::SetValue(self::SESSION_USER, $__USER->username);
                    Session::SetValue(self::SESSION_PASSWORD, $__USER->password);
                    $__USER_LOGGED = true;
                }
            }
        }
        //list($__USER, $__USER_LOGGED, $__USER_LOGIN) = do_action('user_login', $__USER, $__USER_LOGGED, $__USER_LOGIN);
        do_action('user_login', $__USER, $__USER_LOGGED, $__USER_LOGIN);
        if ($__USER_LOGIN && $__USER_LOGGED)
        {
            self::RegisterLogin(self::GetID());
        }
        return $__USER_LOGGED;
    }
    
    public static function Logout($drop=true)
    {
        global $__USER, $__USER_LOGGED;
        $__USER = $__USER_LOGGED = false;
        Request::DeleteCookie(self::COOKIE_REMEMBER);
        if ($drop)
        {
            return Session::Stop(true);
        }
        return (Session::SetValue(self::SESSION_USER) && Session::SetValue(self::SESSION_PASSWORD));
    }
    
    public static function RegisterLogin($id)
    {
        $last = array(
            'lastIP' => getIP(),
            'lastSession' => Session::GetID(),
            'lastLogin' => date('Y-m-d H:i:s')
        );
        do_action('user_register_login', $last, $id);
        return static::Update($id, $last);
    }
    
    /* PAGES */
    public static function GetParentPageID($id=null)
    {
        $id = is_null($id) ? get_user_id() : $id;
        $relations = static::DB_SelectParentRelation($id, static::RELATION_KEY_PAGE);
        foreach ($relations AS $relation)
        {
            return (int)$relation->pid;
        }
        return -1;
    }
    
    public static function GetDependencePagesID($id=null)
    {
        $results = $direct_pages = array(static::GetParentPageID($id));
        foreach ($direct_pages AS $direact_page)
        {
            $results = array_merge($results, Page::GetChildsDependences((int)$direact_page, true, $results));
        }
        return array_unique($results);
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


