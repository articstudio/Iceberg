<?php

require_once ICEBERG_DIR_INCLUDES . 'frontend.php';

class IcebergFrontend extends Environment
{
    /**
     * Routing class name
     * @var string 
     */
    protected static $ROUTING_CLASS = 'RoutingFrontend';
    
    /**
     * Theme class name
     * @var string 
     */
    protected static $THEME_CLASS = 'ThemeFrontend';
    
    /**
     * Environment
     * @var array 
     */
    protected $environments = array(
        'home' => 'index.php',
        'content' => 'content.php',
        '404' => '404.php',
        'maintenance' => 'maintenance.php'
    );
    
    
    public function Load()
    {
        require_once ICEBERG_DIR_INCLUDES . 'RoutingFrontend.class.php';
        require_once ICEBERG_DIR_INCLUDES . 'ThemeFrontend.class.php';
        action_event('iceberg_frontend_load');
        return parent::Load();
    }
    
    public function Config()
    {
        if (Maintenance::InFrontendMode())
        {
            $this->environment = 'maintenance';
        }
        else
        {
            $this->environment = '404';
            $page_id = RoutingFrontend::GetRequestPage();
            if (is_null($page_id))
            {
                $this->environment = 'home';
            }
            else
            {
                $page = get_page($page_id);
                if ($page->id != -1)
                {
                    $this->environment = 'content';
                }
            }
        }
        //IF NO PAGE => HOME
        //ELSE IF PAGE => CONTENT
        //ELSE => 404
        action_event('iceberg_frontend_config');
        return parent::Config();
    }
    
    public function Generate()
    {
        /* Exec controllers */
        //
        
        action_event('iceberg_frontend_generate');
        return parent::Generate();
    }
    
    public function Show()
    {
        action_event('iceberg_frontend_show');
        return parent::Show();
    }
    
    public static function ExecController($template)
    {
        if (is_file($template) && is_readable($template))
        {
            include $template;
            return true;
        }
        return false;
    }
}
