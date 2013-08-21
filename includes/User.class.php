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
    
    const SESSION_USER = 'user';
    const SESSION_PASSWORD = 'password';
    const GP_SESSION_ID = 'session';

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
            $users = static::DB_Select(
                array(
                    'username',
                    'email',
                    'status',
                    'level'
                ),
                array(
                    'username' => $user,
                    'password' => $pass,
                    'status' => 1
                )
            );
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
    
    
    public static function GetID()
    {
        if (self::IsLogged())
        {
            $user = self::GetUser();
            return $user->id;
        }
        return false;
    }
    
    public static function GetUser($id=null)
    {
        if (is_null($id))
        {
            global $__USER;
            return $__USER;
        }
        $users = static::DB_Select(
            array(
                'username',
                'email',
                'status',
                'level'
            ),
            array(
                'id' => $id,
            )
        );
        if (count($users) > 0)
        {
            return current($users);
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
    
    public static function IsAdmin()
    {
        return (static::GetLevel() >= Session::GetAdminLevel());
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
    
}


