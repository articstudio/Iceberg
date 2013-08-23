<?php

class ThemeFrontend extends Theme
{
    
    /**
     * Theme config key to load
     * @var string 
     */
    public static $THEME_CONFIG_KEY = 'frontend';
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->SetDirectory(ICEBERG_DIR_THEMES);
    }
    
    /**
     * Print page template
     * @return boolean 
     */
    public function Page()
    {
        $page_id = RoutingFrontend::GetRequestPage();
        $page = get_page($page_id);
        $template = $page->GetTemplate();
        if (empty($template))
        {
            $taxonomy = $page->GetTaxonomy();
            $templates = $taxonomy->GetTemplates();
            $template = empty($templates) ? '' : current($templates);
        }
        action_event('theme_print_page');
        return $this->ThemeSnippet($template);
    }
}
