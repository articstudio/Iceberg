<?php

class RoutingBackend extends RoutingBackendAPI
{
    
    public function ParseRequest()
    {
        parent::ParseRequest();
    }
    
    
    public static function GetBreadcrumb()
    {
        $module = static::GetModule();
        $mode = static::GetMode();
        $action = static::GetAction();
        $breadcrumb = array(
            $module['name'] => get_link(array(static::REQUEST_KEY_MODULE=>$module['module'])),
            $mode['name'] => get_link(array(static::REQUEST_KEY_MODULE=>$module['module'], static::REQUEST_KEY_MODE=>$mode['mode'])),
            $action['name'] => get_link(array(static::REQUEST_KEY_MODULE=>$module['module'], static::REQUEST_KEY_MODE=>$mode['mode'], static::REQUEST_KEY_ACTION=>$action['action']))
        );
        $breadcrumb = apply_filters('get_breadcrumb', $breadcrumb, $action, $mode, $module);
        $breadcrumb = apply_filters('get_breadcrumb_' . $module['module'], $breadcrumb, $action, $mode);
        $breadcrumb = apply_filters('get_breadcrumb_' . $module['module'] . '_' . $mode['mode'], $breadcrumb, $action);
        $breadcrumb = apply_filters('get_breadcrumb_' . $module['module'] . '_' . $mode['mode'] . '_' . $action['action'], $breadcrumb);
        return $breadcrumb;
    }
    
}
