<?php

class RoutingFrontend extends Routing
{
    
    public function ParseRequest()
    {
        
    }
    
    
    public static function GetBreadcrumb()
    {
        /*$module = static::GetModule();
        $mode = static::GetMode();
        $arr = array(
            $module['name'] => get_link(array(static::REQUEST_KEY_MODULE=>$module['module'])),
            $mode['name'] => get_link(array(static::REQUEST_KEY_MODULE=>$module['module'], static::REQUEST_KEY_MODE=>$mode['mode']))
        );
        list($arr) = action_event('get_admin_breadcrumb', $arr);
        return $arr;*/
    }
    
}
