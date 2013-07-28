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
    }
    
    /**
     * Print page template
     * @return boolean 
     */
    public function Page()
    {
        $template = RoutingBackend::GetMode('template');
        /*if (realpath($template) !== $template)
        {
            $template = $this->GetDirectory() . $template;
        }
        var_dump($template);*/
        action_event('theme_print_page');
        return $this->ThemeSnippet($template);
    }
}
