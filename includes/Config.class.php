<?php

/** Include helpers config file */
require_once ICEBERG_DIR_HELPERS . 'config.php';


abstract class ConfigBase extends ObjectDBRelations
{
    /**
     * DB table name
     * @var string
     */
    public static $DB_TABLE = 'ICEBERG_DB_CONFIG';
    
    /**
     * List of fields
     * @var array
     */
    public static $DB_FIELDS = array(
        'name' => array(
            'name' => 'CONFIGURATION NAME',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => array(
                'NOT NULL'
            ),
            'index' => true
        ),
        'value' => array(
            'name' => 'CONFIGURATION VALUE',
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
        'config-domain' => array(
            'object' => 'Domain',
            'force' => true,
            'function' => 'get_domain_request_id',
            'language' => true
        )
    );

    /**
     * Constructor 
     */
    public function  __construct()
    {
        global $__CONFIG, $__CONFIG_ROWS;
        $__CONFIG = array();
        $__CONFIG_ROWS = array();
    }
    
}

/**
 * Config
 * 
 * Configuration management
 *  
 * @package Iceberg
 * @subpackage Configuration
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 */
class Config extends ConfigBase
{
    
    /**
     * Select configuration value for keyname (If is buffer not set value)
     * 
     * @global array $__CONFIG
     * @param string $keyname
     * @param mixed $default
     * @param boolean $isBuffer
     * @return mixed 
     */
    public static function SelectConfig($keyname, $default=false, $isBuffer=false, $lang=null)
    {
        global $__CONFIG;
        $value = $default;
        $buffer = static::DB_Select(array('value'), array('name' => $keyname), array(), array(), array(), $lang);
        if (count($buffer) > 0) {
            $config = current($buffer);
            $value = static::DB_DecodeFieldValue($config->value);
        }
        list($keyname, $value, $default, $isBuffer, $lang) = action_event('config_select', $keyname, $value, $default, $isBuffer, $lang);
        if (!$isBuffer)
        {
            $__CONFIG[$keyname] = $value;
        }
        return $value;
    }

    /**
     * Add key to row
     * 
     * @global array $__CONFIG_ROWS
     * @param string $keyname 
     */
    public static function AddRow($keyname)
    {
        global $__CONFIG_ROWS;
        if (is_null($__CONFIG_ROWS) || !is_array($__CONFIG_ROWS)) { $__CONFIG_ROWS = array(); }
        if (!in_array($keyname, $__CONFIG_ROWS)) { array_push($__CONFIG_ROWS, $keyname); }
    }

    /**
     * Remove key to row
     * 
     * @global array $__CONFIG_ROWS
     * @param string $keyname 
     */
    public static function RemoveRow($keyname)
    {
        global $__CONFIG_ROWS;
        if (is_null($__CONFIG_ROWS)) { $__CONFIG_ROWS=array(); }
        if (is_array($__CONFIG_ROWS) && in_array($keyname, $__CONFIG_ROWS)) { unset($__CONFIG_ROWS[array_search($keyname, $__CONFIG_ROWS)]); }
    }

    /**
     * Configuration loader
     * 
     * @uses action_event() for 'config_load'
     * @global array $__CONFIG
     * @global array $__CONFIG_ROWS 
     * @param array $keys Configuration keys to load (If empty uses $__CONFIG_ROWS)
     * @return boolean
     */
    public static function LoadConfig($keys=array(), $lang=null)
    {
       global $__CONFIG, $__CONFIG_ROWS;
       $init = empty($keys) ? true : false;
       $keys = $init ? $__CONFIG_ROWS : $keys;
       $buffer = static::DB_Select(array('name', 'value'), array('name' => $keys), array(), array(), array(), $lang);
       if (count($buffer) > 0) {
           foreach ($buffer AS $config) {
               $__CONFIG[$config->name] = static::DB_DecodeFieldValue($config->value);
           }
           list($__CONFIG, $init, $keys) = action_event('config_load', $__CONFIG, $init, $keys);
           return true;
       }
       else {
           return false;
       }
    }

    /**
     * Set a configuration value for a key, if value is null unset the key
     * 
     * @uses action_event() for 'config_set'
     * @global array $__CONFIG
     * @param string $keyname
     * @param mixed $value
     * @return boolean 
     */
    public static function SetConfig($keyname, $value=null)
    {
        global $__CONFIG;
        $done = false;
        if (!is_array($__CONFIG)) { $__CONFIG = array(); }
        if (is_null($value)) {
            $done = self::UnsetConfig($keyname);
        }
        else {
            $__CONFIG[$keyname] = $value;
            $done = true;
        }
        list($keyname, $value) = action_event('config_set', $keyname, $value);
        return $done;
    }
    
    /**
     * Unset a configuration value for a key
     * 
     * @global array $__CONFIG
     * @param string $keyname
     * @return boolean 
     */
    public static function UnsetConfig($keyname)
    {
        global $__CONFIG;
        $__CONFIG[$keyname] = null;
        unset($__CONFIG[$keyname]);
        list($keyname) = action_event('config_unset', $keyname);
        return true;
    }
    
    /**
     * Save configuration value for a keyname (If value is NULL Unsave it)
     * 
     * @param string $keyname
     * @param mixed $value
     * @return boolean 
     */
    public static function SaveConfig($keyname, $value=null, $lang=null)
    {
        $done = (is_null($lang) || I18N::GetLanguage() == $lang || static::REPLICATE_ALL_LANGUAGES == $lang) ? self::SetConfig($keyname, $value) : true;
        if ($done)
        {
            if (is_null($value)) {
                $done = self::DB_Delete(array('name' => $keyname), array(), $lang);
            }
            else {
                static::DB_InsertUpdate(
                    array(
                        'value' => $value,
                    ),
                    array(
                        'name' => $keyname,
                    ),
                    array(),
                    $lang
                );
            }
        }
        /*$done = (is_null($lang) || I18N::GetLanguage() == $lang) ? self::SetConfig($keyname, $value) : true;
        if ($done) {
            if ($lang === static::REPLICATE_CONFIG_ALL_LANGUAGES)
            {
                $languages = I18N::GetLanguages();
                foreach ($languages AS $language)
                {
                    static::SaveConfig($keyname, $value, $language['locale']);
                }
                $done = true;
            }
            else
            {
                if (is_null($value)) {
                    $done = self::DB_Delete(array('name' => $keyname), array(), $lang);
                }
                else {
                    static::DB_InsertUpdate(
                        array(
                            'value' => $value,
                        ),
                        array(
                            'name' => $keyname,
                        ),
                        array(),
                        $lang
                    );
                }
            }
        }*/
        return $done;
    }
    
    /**
     * Unsave configuration value for keyname
     * 
     * @uses self::SaveConfig()
     * @param string $keyname
     * @return boolean 
     */
    public static function UnsaveConfig($keyname, $lang=null)
    {
        return self::SaveConfig($keyname, null, $lang);
    }

    /**
     * Get a configuration value for a key
     * 
     * @uses action_event() for 'config_get'
     * @global array $__CONFIG
     * @param string $keyname
     * @param mixed $default
     * @return mixed 
     */
    public static function GetConfig($keyname, $default=false)
    {
        global $__CONFIG;
        $return = $default;
        if (is_array($keyname)) {
            $return = array();
            foreach ($keyname AS $key=>$value) {
                if (is_array($value)) { $return[$key] = self::GetConfig($keyname); }
                if (is_string($value) && isset($__CONFIG[$keyname])) { $return[$key] = $__CONFIG[$keyname]; }
                else { $return[$key] = $default; }
            }
        }
        else if (is_string($keyname) && isset($__CONFIG[$keyname])) {
            $return = $__CONFIG[$keyname];
        }
        list($return, $keyname, $default) = action_event('config_get', $return, $keyname, $default);
        return $return;
    }
    
    public static function SelectAll($isBuffer=false, $lang=null)
    {
        global $__CONFIG;
        $config = array();
        $buffer = static::DB_Select(array('id', 'name', 'value'), array(), array(), array(), array(), $lang);
        if (count($buffer) > 0) {
            foreach ($buffer AS $k => $v) {
                $config[$v->name] = static::DB_DecodeFieldValue($config->value);
}
            $config = current($buffer);
        }
        list($config, $isBuffer, $lang) = action_event('config_select_all', $config, $isBuffer, $lang);
        if (!$isBuffer)
        {
            $__CONFIG = $config;
        }
        return $config;
    }
    
    public static function SelectAllObjects($lang=null)
    {
        $config = static::DB_Select(array('id', 'name', 'value'), array(), array(), array(), array(), $lang);
        list($config, $lang) = action_event('config_select_all_objects', $config, $lang);
        return $config;
    }
    
    public static function SelectConfigObject($keyname, $lang=null)
    {
        $config = false;
        $buffer = static::DB_Select(array('id', 'name', 'value'), array('name' => $keyname), array(), array(), array(), $lang);
        if (count($buffer) > 0) {
            $config = current($buffer);
        }
        list($keyname, $config, $lang) = action_event('config_select_object_by_id', $keyname, $config, $lang);
        if ($config === false)
        {
            return static::GetVoidObject();
        }
        return $config;
    }
    
    public static function SelectConfigObjectByID($id)
    {
        $config = false;
        $buffer = static::DB_Select(array('id', 'name', 'value'), array('id' => $id), array(), array(), array(), null);
        if (count($buffer) > 0) {
            $config = current($buffer);
        }
        list($config, $id) = action_event('config_select_object_by_id', $config, $id);
        if ($config === false)
        {
            return static::GetVoidObject();
        }
        return $config;
    }
    
    public static function GetVoidObject()
    {
        $obj = new stdClass();
        $obj->id = -1;
        $obj->name = '';
        $obj->value = '';
        return $obj;
    }
}
