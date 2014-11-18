<?php

class ThemeBackend extends ThemeBackendAPI
{
    
    /**
     * Theme config key to load
     * @var string 
     */
    public static $THEME_CONFIG_KEY = 'backend';
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->SetDirectory(ICEBERG_DIR_ADMIN_THEMES);
        static::LoadFrontendThemeSettings();
        parent::__construct();
    }
    
    /**
     * Print page template
     * @return boolean 
     */
    public function Page()
    {
        $template = RoutingBackend::GetAction('template');
        do_action('theme_print_page');
        $template = apply_filters('theme_backend_print_page', $template);
        return $this->ThemeSnippet($template);
    }
}
