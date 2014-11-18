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
    
}
