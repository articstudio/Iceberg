<?php

/** Include helpers user role file */
require_once ICEBERG_DIR_HELPERS . 'alerts.php';

class Alerts extends MultiObjectConfig
{
    
    public static $CONFIG_KEY = 'alert';
    
    public $type = array();
    public $permanent = false;
    
    public function __construct($args=array()) {
        $this->type = isset($args['type']) ? $args['type'] : 'info';
        parent::__construct($args);
    }
    
    public function GetType()
    {
        return $this->type;
    }
    
    public function SetType($type)
    {
        return ($this->type = $type);
    }
    
    public function IsPermanent()
    {
        return (bool)$this->permanent;
    }
    
    public function SetPermanent($permanent)
    {
        return ($this->permanent = (bool)$permanent);
    }
    
    public static function RegisterAlert($txt, $type='info', $permanent=false, $user_id=true)
    {
        $relations = array();
        if ($user_id)
        {
            if ($user_id === true)
            {
                $relations['user'] = get_user_id();
            }
            else
            {
                $relations['user'] = $user_id;
            }
        }
        return static::Register(array('name'=>$txt,'type'=>$type,'permanent'=>$permanent), $relations);
    }
    
    public static function GetAlerts($user_id=true)
    {
        $args = array();
        if ($user_id)
        {
            if ($user_id === true)
            {
                $args['user'] = get_user_id();
            }
            else
            {
                $args['user'] = $user_id;
            }
        }
        return static::GetList($args);
    }
}
