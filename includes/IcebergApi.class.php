<?php

class IcebergAPI extends Environment
{
    /**
     * Routing class name
     * @var string 
     */
    protected static $ROUTING_CLASS = 'RoutingAPI';
    
    /**
     * Theme class name
     * @var string 
     */
    protected static $THEME_CLASS = 'ThemeAPI';
    
    /**
     * Environment
     * @var array 
     */
    protected $environments = array();
    
    protected $alerts = array();
    
    
    public function Load()
    {
        UserCapability::EnableApplyCapabilities();
        require_once ICEBERG_DIR_API_INCLUDES . 'RoutingAPI.class.php';
        require_once ICEBERG_DIR_API_INCLUDES . 'ThemeAPI.class.php';
        require_once ICEBERG_DIR_API_INCLUDES . 'api.php';
        do_action('iceberg_api_load');
        return parent::Load();
    }
    
    public function Config()
    {
        $this->routing->ProcessRequest();
        do_action('iceberg_api_config');
        return parent::Config();
    }
    
    public function Generate()
    {
        do_action('iceberg_api_generate');
        return parent::Generate();
    }
    
    public function Show()
    {
        do_action('iceberg_api_show');
        return parent::Show();
    }
    
}
