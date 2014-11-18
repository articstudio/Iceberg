<?php

/** Include helpers user role file */
require_once ICEBERG_DIR_HELPERS . 'userrole.php';

class UserRole extends MultiObjectConfig
{
    
    public static $CONFIG_KEY = 'user_role';
    
    protected $capabilites = array();
    
    public function __construct($args=array()) {
        $this->capabilities = isset($args['capabilities']) ? $args['capabilities'] : array();
        parent::__construct($args);
    }
    
    public function SetCapabilities($capabilites=array())
    {
        return $this->capabilities = $capabilites;
    }
    
    public function GetCapabilities()
    {
        return $this->capabilities;
    }
    
    public function HasCapability($capability, $extra_capabilities=array())
    {
        return UserCapability::CheckCapability($capability, array_merge($this->capabilities, $extra_capabilities));
    }
    
    public function HasFullCapability($capability, $extra_capabilities=array())
    {
        return UserCapability::CheckFullCapability($capability, array_merge($this->capabilities, $extra_capabilities));
    }
    
    public function HasOwnCapability($capability, $extra_capabilities=array())
    {
        return UserCapability::CheckOwnCapability($capability, array_merge($this->capabilities, $extra_capabilities));
    }
    
}
