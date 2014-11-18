<?php

/** Include helpers template file */
require_once ICEBERG_DIR_HELPERS . 'templates.php';


abstract class TemplateBase extends ObjectConfig
{
    
    /**
     * Configuration use language
     * @var boolean
     */
    public static $CONFIG_USE_LANGUAGE = false;
    
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'template_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'home' => array(),
        'content' => array(),
        'maintenance' => array()
    );
    
    
    public static function AddTemplate($environment, $template)
    {
        $templates = static::GetConfigValue($environment, array());
        if (is_array($template))
        {
            $templates = array_merge($templates, $template);
        }
        else
        {
            array_push($templates, $template);
        }
        array_unique ($templates);
        return static::SaveConfigValue($environment, $templates);
    }
    
    public static function RemoveTemplate($environment, $template)
    {
        $templates = static::GetConfigValue($environment, array());
        if (is_array($template))
        {
            foreach ($template AS $temp)
            {
                if (isset($templates[$temp]))
                {
                    $templates[$temp] = null;
                    unset($templates[$temp]);
                }
            }
        }
        else
        {
            $flip = array_flip($templates);
            if (isset($flip[$template]))
            {
                $flip[$template] = null;
                unset($flip[$template]);
            }
            $templates = array_flip($flip);
        }
        return static::SaveConfigValue($environment, $templates);
    }
    
    public static function GetTemplates($environment)
    {
        $templates = static::GetConfigValue($environment, array());
        return $templates;
    }
    
    public static function GetTemplatesContent()
    {
        return static::GetTemplates('content');
    }
}

/**
 * CMS Template
 * 
 * Templates management
 *  
 * @package Iceberg
 * @author Marc Mascort Bou
 * @version 1.0
 */
class Template extends TemplateBase
{
    /**#@+
     * Private variables
     */
    private $template = null;
    private $template_callback = null;
    private $template_url = null;
    private $template_dir = null;
    private $template_name = null;
    private $template_file = null;
    private $template_content = null;
    /**#@-*/

    /**
     * Constructor
     * @param string $dir
     * @param string $template
     * @param string $url
     * @param string $callback 
     */
    public function  __construct($dir=null, $template=null, $url=null, $callback=null)
    {
        $this->SetTemplateDir($dir);
        $this->SetTemplate($template);
        $this->SetTemplateUrl($url);
        $this->SetTemplateCallback($callback);
    }

    /**
     * Generate template content
     * @uses action_event() for 'template_generate_content'
     * @return boolean 
     */
    public function GenerateTemplateContent()
    {
        $done = false;
        if (is_file($this->GetTemplateFile()) && is_readable($this->GetTemplateFile())) {
            ob_start($this->template_callback);
            include $this->GetTemplateFile();
            $content = ob_get_clean();
            $content = apply_filters('template_generate_content', $content, $this->GetTemplate());
            $this->template_content = $content;
            $done = true;
        }
        return $done;
    }

    /**
     * Sets directory of template
     * @param string $dir
     * @return boolean 
     */
    public function SetTemplateDir($dir)
    {
        if ( !is_null($this->template) ) { $this->SetTemplateFile( $dir . $this->template ); }
        return $this->template_dir = $dir;
    }
    
    /**
     * Sets URL of template
     * @param string $url
     * @return boolean 
     */
    public function SetTemplateUrl($url)
    {
        return $this->template_url = $url;
    }
    
    /**
     * Sets filename of template
     * @param string $template
     * @return boolean 
     */
    public function SetTemplate($template)
    {
        $this->SetTemplateFile( $this->template_dir . $template );
        return $this->template = $template;
    }
    
    /**
     * Sets file of template (Directory + Filename)
     * @param type $file
     * @return type 
     */
    public function SetTemplateFile($file)
    {
        $this->SetTemplateName( is_string($file) ? get_file_name($file) : null );
        return $this->template_file = $file;
    }
    
    /**
     * Sets name of template
     * @param string $name
     * @return boolean 
     */
    public function SetTemplateName($name=null)
    {
        return $this->template_name = $name;
    }
    
    /**
     * Sets callback function of template generate
     * @param string $callback
     * @return boolean 
     */
    public function SetTemplateCallback($callback)
    {
        return $this->template_callback = $callback;
    }
    
    /**
     * Sets content of template
     * @param string $content
     * @return boolean 
     */
    public function SetTemplateContent($content)
    {
        return $this->template_content = $content;
    }

    /**
     * Returns directory of template
     * @return string 
     */
    public function GetTemplateDir()
    {
        return $this->template_dir;
    }
    
    /**
     * Returns URL of template
     * @return string 
     */
    public function GetTemplateUrl()
    { return $this->template_url; }
    
    /**
     * Returns filename of template
     * @return string 
     */
    public function GetTemplate()
    { return $this->template; }
    
    /**
     * Returns name of template
     * @return string 
     */
    public function GetTemplateName()
    { return $this->template_name; }
    
    /**
     * Returns file of template (Directory + Filename)
     * @return string 
     */
    public function GetTemplateFile()
    { return $this->template_file; }
    
    /**
     * Returns callback function of template generate
     * @return string 
     */
    public function GetTemplateCallback()
    { return $this->template_callback; }
    
    /**
     * Returns content of template
     * @return string 
     */
    public function GetTemplateContent()
    {
        return sprintf( '%s', $this->template_content );
    }

    /**
     * Print content of template 
     */
    public function PrintTemplateContent()
    {
        printf( '%s', $this->template_content );
    }
}
