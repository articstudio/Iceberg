<?php

/** Include helpers request file */
require_once ICEBERG_DIR_HELPERS . 'themes.php';

/**
 * Iceberg Theme
 * 
 * Theme management
 *  
 * @abstract
 * @package Iceberg
 * @author Marc Mascort Bou
 * @version 1.0
 */
abstract class ThemeBase extends ObjectConfig
{
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'theme_config';
    
    /**
     * Theme frontend config key to load
     * @var string 
     */
    public static $THEME_FRONTEND_CONFIG_KEY = 'frontend';
    
    /**
     * Theme backend config key to load
     * @var string 
     */
    public static $THEME_BACKEND_CONFIG_KEY = 'backend';
    
    /**
     * Theme api config key to load
     * @var string 
     */
    public static $THEME_API_CONFIG_KEY = 'api';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'frontend' => array(
            'name' => 'Iceberg default theme',
            'dirname' => 'default'
        ),
        'backend' => array(
            'name' => 'Iceberg default theme',
            'dirname' => 'default'
        ),
        'api' => array(
            'name' => 'Iceberg default theme',
            'dirname' => 'default'
        )
    );
    
    /**
     * Default theme info
     * @var array 
     */
    public static $DEFAULT_INFO = array(
        'name' => '',
        'description' => '',
        'version' => '',
        'author' => '',
        'email' => '',
        'www' => ''
    );
    
    /**
     * Theme config key to load
     * @var string 
     */
    public static $THEME_CONFIG_KEY = '';
    
    protected static $SETTINGS_FILE = 'settings.php';
    protected static $INFO_FILE = 'theme.php';
    protected static $INSTALL_FILE = 'install.php';
    protected static $UNINSTALL_FILE = 'uninstall.php';
    protected static $HEADER_FILE = 'header.php';
    protected static $FOOTER_FILE = 'footer.php';
    protected static $PAGE_FILE = 'page.php';
    
    public static function GetFrontendTheme()
    {
        return static::GetConfigValue(static::$THEME_FRONTEND_CONFIG_KEY);
    }
    
    public static function GetBackendTheme()
    {
        return static::GetConfigValue(static::$THEME_BACKEND_CONFIG_KEY);
    }
    
    public static function GetAPITheme()
    {
        return static::GetConfigValue(static::$THEME_API_CONFIG_KEY);
    }
    
    public static function GetThemeFileInfo($dir, $dirname)
    {
        $theme = array();
        $path = $dir . $dirname;
        if (is_dir($path)) {
            $path_file = $path . DIRECTORY_SEPARATOR . static::$INFO_FILE;
            if (is_file($path_file)) {
                require $path_file;
                $theme['dirname'] = $dirname;
                $theme['dir'] = $path .DIRECTORY_SEPARATOR;
            }
        }
        return static::NormalizeInfo($theme);
    }
    
    public static function GetThemes($dir)
    {
        $themes = array();
        $dh = opendir($dir);
        if ($dh !== false) {
            while (($file = readdir($dh)) !== false) {
                $path = $dir . $file;
                if (is_dir($path) && $file !== '.' && $file !== '..') {
                    $theme = static::GetThemeFileInfo($dir, $file);
                    array_push($themes, $theme);
                }
            }
            closedir($dh);
        }
        return $themes;
    }
    
    public static function GetFrontendThemes()
    {
        return static::GetThemes(ICEBERG_DIR_THEMES);
    }
    
    public static function GetBackendhemes()
    {
        return static::GetThemes(ICEBERG_DIR_ADMIN_THEMES);
    }
    
    static public function Active($type, $dirname)
    {
        $done = false;
        $type = $type=='backend' ? $type : 'frontend';
        $dir = $type=='backend' ? ICEBERG_DIR_ADMIN_THEMES : ICEBERG_DIR_THEMES;
        $theme = static::GetThemeFileInfo($dir, $dirname);
        $theme_config = array(
            'name' => $theme['name'],
            'dirname' => $dirname
        );
        
        $install_file = $theme['dir'] . DIRECTORY_SEPARATOR . static::$INSTALL_FILE;
        $install_settings_file = $theme['dir'] . DIRECTORY_SEPARATOR . static::$SETTINGS_FILE;
        
        $config_buffer = static::GetConfigValue($type, array('dirname'=>''));
        $uninstall_file = $dir . $config_buffer['dirname'] . DIRECTORY_SEPARATOR . static::$UNINSTALL_FILE;
        //$uninstall_settings_file = $dir . $config_buffer['dirname'] . DIRECTORY_SEPARATOR . static::$SETTINGS_FILE;
        
        $done = static::SaveConfigValue($type, $theme_config, Config::REPLICATE_ALL_LANGUAGES);
        if ($done)
        {
            //if (is_file($uninstall_settings_file) && is_readable($uninstall_settings_file)) {
            //    require_once $uninstall_settings_file;
            //}
            if (is_file($uninstall_file) && is_readable($uninstall_file)) {
                require_once $uninstall_file;
            }
            if (is_file($install_settings_file) && is_readable($install_settings_file)) {
                require_once $install_settings_file;
            }
            if (is_file($install_file) && is_readable($install_file)) {
                require_once $install_file;
            }
        }
        return $done;
    }
    
    
    private static function NormalizeInfo($info)
    {
        return array_merge(static::$DEFAULT_INFO, $info);
    }
}

interface ThemeInterface
{
    public function __contruct();
}

abstract class Theme extends ThemeBase
{
    private $directory = null;
    private $url = null;
    private $template = null;
    private $templateObject = null;
    private $scripts_reg = array();
    private $scripts_enqueue = array();
    private $styles_reg = array();
    private $styles_enqueue = array();
    
    
    public function SetDirectory($basedir)
    {
        $theme = Theme::GetConfigValue(static::$THEME_CONFIG_KEY, array());
        $dirname = isset($theme['dirname']) ? $theme['dirname'] : '';
        $this->directory = $basedir . $dirname . DIRECTORY_SEPARATOR;
        $this->url = File::GetURL($this->directory, ICEBERG_DIR, Request::GetBaseUrl());
    }
    
    public function GetURL()
    {
        return $this->url;
    }
    
    public function GetDirectory()
    {
        return $this->directory;
    }
    
    public function Load()
    {
        $file = $this->GetDirectory() . static::$SETTINGS_FILE;
        if (is_file($file) && is_readable($file)) {
            require_once $file;
            return true;
        }
        return false;
    }
    
    public function SetTemplate($template)
    {
        $this->template = $template;
    }
    
    public function GetTemplate()
    {
        return $this->template;
    }
    
    public function Generate()
    {
        $this->templateObject = new Template($this->GetDirectory(), $this->GetTemplate(), $this->GetURL());
        return $this->templateObject->GenerateTemplateContent();
    }
    
    public function PrintTheme()
    {
        $this->templateObject->PrintTemplateContent();
    }
    
    public static function GetTheme()
    {
        return Environment::GetEnvironmentTheme();
    }
    
    /**
     * Print/Returns part of theme
     * @param string $file
     * @param boolean $print
     * @param string $callback
     * @return boolean|string 
     */
    public function ThemeSnippet($file, $print=true, $callback=null)
    {
        $template = new Template($this->GetDirectory(), $file, $this->GetURL(), $callback);
        $done = $template->GenerateTemplateContent();
        if ($done) {
            if ($print) {
                $template->PrintTemplateContent();
            }
            else {
                $done = $template->GetTemplateContent();
            }
        }
        return $done;
    }
    
    /**
     * Print header template
     * @return boolean 
     */
    public function Header()
    {
        action_event('theme_print_header');
        return $this->ThemeSnippet(static::$HEADER_FILE);
    }
    
    /**
     * Print footer template
     * @return boolean 
     */
    public function Footer()
    {
        action_event('theme_print_footer');
        return $this->ThemeSnippet(static::$FOOTER_FILE);
    }
    
    /**
     * Print page template
     * @return boolean 
     */
    public function Page()
    {
        action_event('theme_print_page');
        return $this->ThemeSnippet(static::$PAGE_FILE);
    }
    
    
    public function RegisterScript($name, $url, $version=null, $dependency=array(), $in_footer=false)
    {
        $name = (string)$name;
        $url = (string)$url;
        $version = is_null($version) ? ICEBERG_VERSION : (string)$version;
        $script = array(
            'name' => $name,
            'url' => (string)$url,
            'version' => $version,
            'dependency' => is_array($dependency) ? $dependency : array($dependency),
            'in_footer' => $in_footer
        );
        if (!isset($this->scripts_reg[$name])) { $this->scripts_reg[$name] = array(); }
        if (!isset($this->scripts_reg[$name][$version]))
        {
            $this->scripts_reg[$name][$version] = $script;
        }
        return true;
    }
    
    public function EnqueueScript($name, $url='', $version=null, $dependency=array(), $in_footer=false)
    {
        $name = (string)$name;
        $url = (string)$url;
        $version = is_null($version) ? ICEBERG_VERSION : (string)$version;
        $script = array(
            'name' => $name,
            'url' => $url,
            'version' => $version,
            'dependency' => is_array($dependency) ? $dependency : array($dependency),
            'in_footer' => $in_footer
        );
        if (!isset($this->scripts_enqueue[$name])) { $this->scripts_enqueue[$name] = array(); }
        if (!isset($this->scripts_enqueue[$name][$version]))
        {
            $this->scripts_enqueue[$name][$version] = $script;
        }
        return true;
    }
    
    public function RegisterStyle($name, $url, $version=null, $dependency=array(), $media='all')
    {
        $name = (string)$name;
        $url = (string)$url;
        $version = is_null($version) ? ICEBERG_VERSION : (string)$version;
        $media = (string)$media;
        $script = array(
            'name' => $name,
            'url' => (string)$url,
            'version' => $version,
            'dependency' => is_array($dependency) ? $dependency : array($dependency),
            'media' => $media
        );
        if (!isset($this->styles_reg[$name])) { $this->styles_reg[$name] = array(); }
        if (!isset($this->styles_reg[$name][$version]))
        {
            $this->styles_reg[$name][$version] = $script;
        }
        return true;
    }
    
    public function EnqueueStyle($name, $url='', $version=null, $dependency=array(), $media='all')
    {
        $name = (string)$name;
        $url = (string)$url;
        $version = is_null($version) ? ICEBERG_VERSION : (string)$version;
        $media = (string)$media;
        $script = array(
            'name' => $name,
            'url' => $url,
            'version' => $version,
            'dependency' => is_array($dependency) ? $dependency : array($dependency),
            'media' => $media
        );
        if (!isset($this->styles_enqueue[$name])) { $this->styles_enqueue[$name] = array(); }
        if (!isset($this->styles_enqueue[$name][$version]))
        {
            $this->styles_enqueue[$name][$version] = $script;
        }
        return true;
    }

    /**
     * Print CMS head
     * @todo Script dependency
     * @return boolean 
     */
    public function Head()
    {
        action_event('theme_print_head');
        $output = '';
        /* SCRIPTS */
        foreach ($this->scripts_enqueue AS $name => $versions)
        {
            $found = false;
            if (isset($this->scripts_reg[$name]))
            {
                $versions = array_merge($versions, $this->scripts_reg[$name]);
            }
            krsort($versions, SORT_NUMERIC);
            foreach ($versions AS $version => $script)
            {
                if (!empty($script['url']) && !$script['in_footer'])
                {
                    $output .= '<script type="text/javascript" src="' . get_html_attr($script['url']) . '?v=' . get_html_attr($version) . '"></script>' . "\n";
                    $found = false;
                    break;
                }
            }
            if ($found)
            {
                $this->scripts_enqueue[$name] = array();
                unset($this->scripts_enqueue[$name]);
                break;
            }
        }
        /* STYLES */
        foreach ($this->styles_enqueue AS $name => $versions)
        {
            $found = false;
            if (isset($this->styles_reg[$name]))
            {
                $versions = array_merge($versions, $this->styles_reg[$name]);
            }
            krsort($versions, SORT_NUMERIC);
            foreach ($versions AS $version => $script)
            {
                if (!empty($script['url']))
                {
                    $output .= '<link rel="stylesheet" id="' . get_html_attr($script['name']) . '-css"  href="' . get_html_attr($script['url']) . '?v=' . get_html_attr($version) . '" type="text/css" media="' . get_html_attr($script['media']) . '" />' . "\n";
                    $found = false;
                    break;
                }
            }
            if ($found)
            {
                $this->styles_enqueue[$name] = array();
                unset($this->styles_enqueue[$name]);
                break;
            }
        }
        printf( '%s', $output );
        return true;
    }

    /**
     * Print CMS head
     * @todo Script dependency
     * @return boolean 
     */
    public function Foot()
    {
        action_event('theme_print_foot');
        $output = '';
        /* SCRIPTS */
        foreach ($this->scripts_enqueue AS $name => $versions)
        {
            $found = false;
            if (isset($this->scripts_reg[$name]))
            {
                $versions = array_merge($versions, $this->scripts_reg[$name]);
            }
            krsort($versions, SORT_NUMERIC);
            foreach ($versions AS $version => $script)
            {
                if (!empty($script['url']) && $script['in_footer'])
                {
                    $output .= '<script type="text/javascript" src="' . get_html_attr($script['url']) . '?v=' . get_html_attr($version) . '"></script>' . "\n";
                    $found = false;
                    break;
                }
            }
            if ($found)
            {
                $this->scripts_enqueue[$name] = array();
                unset($this->scripts_enqueue[$name]);
                break;
            }
        }
        printf( '%s', $output );
        return true;
    }
    
    
    
    
    
    

    /**
     * Print sidebar template
     * @return boolean 
     */
    public function CMSSidebar()
    {
        return $this->CMSMiniTemplate( CMSTheme::CMSGetTemplateFileBasic('sidebar') );
    }

    
    public function SetThemeInfo($info)
    {
        return $this->theme_information = $info;
    }
    
    public function GetThemeInfo($key=null)
    {
        $info = $this->theme_information;
        if (!is_null($key) && isset($info[$key])) {
            $info = $info[$key];
        }
        return $info;
    }

    

    /**
     * Returns filename of template part of theme
     * @uses action_event() for 'theme_get_template_file'
     * @param string $key
     * @return string 
     */
    static public function CMSGetTemplateFileBasic($key)
    {
        $file = isset(CMSTheme::$theme_files[$key]) ? CMSTheme::$theme_files[$key] : CMSTheme::$theme_file_null;
        list($file) = action_event('theme_get_template_file_basic', $file, CMSTheme::$theme_files, $key);
        return $file;
    }
    
    /*
    static public function GetConfig($key, $default=false)
    {
        $Config = Config::GetConfig( Config::$KEY_THEME );
        $value = isset($Config[$key]) ? $Config[$key] : $default;
        $value = $key==='all' ? $Config : $value;
        list($value) = action_event('theme_get_config', $value, $key, $default);
        return $value;
    }
    
    static public function SaveConfig($key, $value)
    {
        $config = self::GetConfig('all');
        $config[$key] = $value;
        return Config::SaveConfig(Config::$KEY_THEME, $config, true);
    }
    */
}



