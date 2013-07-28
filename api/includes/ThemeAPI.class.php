<?php

class ThemeAPI extends ThemeBackendAPI
{
    
    /**
     * Theme config key to load
     * @var string 
     */
    public static $THEME_CONFIG_KEY = 'api';
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->SetDirectory('');
        static::LoadFrontendThemeSettings();
        static::LoadBackendThemeSettings();
    }
    
    /**
     * Print page template
     * @return boolean 
     */
    public function Page()
    {
        /*$template = RoutingBackend::GetMode('template');
        action_event('theme_print_page');
        return $this->ThemeSnippet($template);
        */
        return false;
    }
}
