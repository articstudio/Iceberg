<?php

require_once ICEBERG_DIR_ADMIN_INCLUDES . 'backend.php';

class IcebergBackend extends Environment
{
    /**
     * Routing class name
     * @var string 
     */
    protected static $ROUTING_CLASS = 'RoutingBackend';
    
    /**
     * Theme class name
     * @var string 
     */
    protected static $THEME_CLASS = 'ThemeBackend';
    
    /**
     * Environment
     * @var array 
     */
    protected $environments = array(
        'login' => 'login.php',
        'restore-password' => 'restore-password.php',
        'signup' => 'signup.php',
        'application' => 'application.php'
    );
    
    
    public function Load()
    {
        UserCapability::EnableApplyCapabilities();
        require_once ICEBERG_DIR_ADMIN_INCLUDES . 'RoutingBackend.class.php';
        require_once ICEBERG_DIR_ADMIN_INCLUDES . 'ThemeBackend.class.php';
        require_once ICEBERG_DIR_ADMIN_INCLUDES . 'helpers.php';
        do_action('iceberg_backend_load');
        return parent::Load();
    }
    
    public function Config()
    {
        $this->routing->ProcessRequest();
        $this->environment = 'login';
        if (User::IsLogged())
        {
            if (RoutingBackend::GetRequestLogout())
            {
                User::Logout();
                Request::Locate(get_base_url_admin());
            }
            if (User::IsAdmin())
            {
                $this->environment = 'application';
            }
            else
            {
                Request::Locate(get_base_url(), 403);
            }
        }
        else
        {
            $module = RoutingBackend::GetRequestModule();
            if ($module === 'application')
            {
                Request::Locate(get_base_url_admin(), 403);
            }
            else
            {
                if (isset($this->environments[$module]))
                {
                    $this->environment = $module;
                }
            }
        }
        do_action('iceberg_backend_config');
        return parent::Config();
    }
    
    public function Generate()
    {
        /* Exec controllers */
        $this->ExecController(RoutingBackend::GetModule('template', false));
        $this->ExecController(RoutingBackend::GetMode('template', false));
        $this->ExecController(RoutingBackend::GetAction('template', false));
        
        do_action('iceberg_backend_generate');
        return parent::Generate();
    }
    
    public function Show()
    {
        do_action('iceberg_backend_show');
        return parent::Show();
    }
    
    public static function ExecController($template)
    {
        $file = is_file($template) ? $template : ICEBERG_DIR_ADMIN_CONTROLLERS . $template;
        if (is_file($file) && is_readable($file))
        {
            include $file;
            return true;
        }
        return false;
    }
    
    
    
}
