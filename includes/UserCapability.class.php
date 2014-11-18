<?php

/** Include helpers user capability file */
require_once ICEBERG_DIR_HELPERS . 'usercapability.php';


/**
 * admin_root => Root access
 * 
 * ********* ADMIN ************
 * admin_login => Backend access
 * 
 * module_%MODULE% => Access to module %MODULE%
 * module_%MODULE%_full => Full acess to %MODULE%
 * module_%MODULE%_own => Own acess to %MODULE%
 * mode_%MODULE%_%MODE% > Access to mode %MODULE% > %MODE%
 * mode_%MODULE%_%MODE%_full > Full access to mode %MODULE% > %MODE%
 * mode_%MODULE%_%MODE%_own > Own access to mode %MODULE% > %MODE%
 * action_%MODULE%_%MODE%_%ACTION% > Access to mode %MODULE% > %MODE% > %ACTION
 * 
 * profile_edit => Edit own profile
 * 
 * 
 * media_read => read all files
 * media_read_own => read own files
 * media_edit => edit all files
 * media_edit_own => edit own files
 * 
 * ********* DEBUG ************
 * debug => Show debug
 * 
 */

class UserCapability extends MultiObjectConfig
{
    
    public static $CONFIG_KEY = 'user_capability';
    
    protected $capability = '';
    
    const ROOT = 'admin_root';
    const ADMIN_LOGIN = 'admin_login';
    
    public function __construct($args=array()) {
        $this->capability = isset($args['capability']) ? $args['capability'] : '';
        parent::__construct($args);
    }
    
    public function SetCapability($capability='')
    {
        return $this->capability = $capability;
    }
    
    public function GetCapability()
    {
        return $this->capability;
    }
    
    
    
    static function CheckCapability($capability, $capabilities)
    {
        $has_capability = in_array($capability, $capabilities) || in_array(static::ROOT, $capabilities);
        if (!$has_capability)
        {
            if (static::IsBackendAPIRoutingCapability($capability))
            {
                return static::CheckBackendAPIRoutingCapability($capability, $capabilities);
            }
            else if (static::IsMediaCapability($capability))
            {
                return static::CheckMediaCapability($capability, $capabilities);
            }
        }
        return $has_capability;
    }
    
    static function CheckFullCapability($capability, $capabilities)
    {
        $has_capability = in_array($capability, $capabilities) || in_array(static::ROOT, $capabilities);
        if (!$has_capability)
        {
            if (static::IsBackendAPIRoutingCapability($capability))
            {
                return static::CheckBackendAPIRoutingFullCapability($capability, $capabilities);
            }
            else if (static::IsMediaCapability($capability))
            {
                return static::CheckMediaFullCapability($capability, $capabilities);
            }
        }
        return $has_capability;
    }
    
    static function CheckOwnCapability($capability, $capabilities)
    {
        $has_capability = in_array($capability, $capabilities) || in_array(static::ROOT, $capabilities);
        if (!$has_capability)
        {
            if (static::IsBackendAPIRoutingCapability($capability))
            {
                return static::CheckBackendAPIRoutingOwnCapability($capability, $capabilities);
            }
            else if (static::IsMediaCapability($capability))
            {
                return static::CheckMediaOwnCapability($capability, $capabilities);
            }
        }
        return $has_capability;
    }
    
    
    
    static function IsBackendAPIRoutingCapability($capability)
    {
        return (strpos($capability, 'module_')===0 || strpos($capability, 'mode_')===0 || strpos($capability, 'action_')===0);
    }
    
    static function CheckBackendAPIRoutingCapability($capability, $capabilities)
    {
        $breadcrumb = explode('_', $capability);
        $module_full = 'module_' . $breadcrumb[1] . '_full';
        $module_own = 'module_' . $breadcrumb[1] . '_own';
        if (in_array($module_full, $capabilities) || in_array($module_own, $capabilities))
        {
            return true;
        }
        if ($breadcrumb[0]==='mode' || $breadcrumb[0]==='action')
        {
            $mode_full = 'mode_' . $breadcrumb[1] . '_' . $breadcrumb[2] . '_full';
            $mode_own = 'mode_' . $breadcrumb[1] . '_' . $breadcrumb[2] . '_own';
            //var_dump($capability);
            //var_dump($mode_full);
            //var_dump($mode_own);
            //var_dump($capabilities);
            if (in_array($mode_full, $capabilities) || in_array($mode_own, $capabilities))
            {
                //var_dump(true); var_dump('--------------------');
                return true;
            }
            //var_dump(false); var_dump('--------------------');
        }
        return false;
    }
    
    static function CheckBackendAPIRoutingOwnCapability($capability, $capabilities)
    {
        $breadcrumb = explode('_', $capability);
        $module_own = 'module_' . $breadcrumb[1] . '_own';
        if (in_array($module_own, $capabilities))
        {
            return true;
        }
        if ($breadcrumb[0]==='mode' || $breadcrumb[0]==='action')
        {
            $mode_own = 'mode_' . $breadcrumb[1] . '_' . $breadcrumb[2] . '_own';
            if (in_array($mode_own, $capabilities))
            {
                return true;
            }
        }
        return false;
    }
    
    static function CheckBackendAPIRoutingFullCapability($capability, $capabilities)
    {
        $breadcrumb = explode('_', $capability);
        $module_full = 'module_' . $breadcrumb[1] . '_full';
        if (in_array($module_full, $capabilities))
        {
            return true;
        }
        if ($breadcrumb[0]==='mode' || $breadcrumb[0]==='action')
        {
            $mode_full = 'mode_' . $breadcrumb[1] . '_' . $breadcrumb[2] . '_full';
            if (in_array($mode_full, $capabilities))
            {
                return true;
            }
        }
        return false;
    }
    
    
    
    static function IsMediaCapability($capability)
    {
        return (strpos($capability, 'media_')===0);
    }
    
    static function CheckMediaCapability($capability, $capabilities)
    {
        /*$media_full = $capability . '_full';
        $media_own = $capability . '_own';
        if (in_array($media_full, $capabilities) || in_array($media_own, $capabilities))
        {
            return true;
        }*/
        return false;
    }
    
    static function CheckMediaOwnCapability($capability, $capabilities)
    {
        $media_own = $capability . '_own';
        if (in_array($media_own, $capabilities))
        {
            return true;
        }
        return false;
    }
    
    static function CheckMediaFullCapability($capability, $capabilities)
    {
        $media_full = $capability . '_full';
        if (in_array($media_full, $capabilities))
        {
            return true;
        }
        return false;
    }
    
    
    
    static function EnableApplyCapabilities()
    {
        global $__APPLY_ROLE_CAPABILITIES;
        $__APPLY_ROLE_CAPABILITIES = true;
    }
    
    static function DisableApplyCapabilities()
    {
        global $__APPLY_ROLE_CAPABILITIES;
        $__APPLY_ROLE_CAPABILITIES = false;
    }
    
    
    
}
