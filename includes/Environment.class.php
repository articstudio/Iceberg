<?php

/** Include helpers config file */
require_once ICEBERG_DIR_HELPERS . 'environment.php';

interface EnvironmentInterface
{
    public function Load();
    public function Config();
    public function Generate();
    public function Show();
}

/**
 * Environment
 * 
 * Environment management
 *  
 * @package Iceberg
 * @subpackage Environment
 * @author Marc Mascort Bou
 * @version 1.0 
 * @since 1.0
 */
abstract class Environment implements EnvironmentInterface
{
    /**
     * Routing class name
     * @var string 
     */
    protected static $ROUTING_CLASS = 'Routing';
    
    /**
     * Theme class name
     * @var string 
     */
    protected static $THEME_CLASS = 'Theme';
    
    /**
     * Routing
     * @var object 
     */
    protected $routing = null;
    
    /**
     * Routing
     * @var object 
     */
    protected $theme = null;
    
    /**
     * Environments
     * @var array 
     */
    protected $environments = array();
    
    /**
     * Environment
     * @var string 
     */
    protected $environment = null;
    
    /**
     * Alerts
     * @var array
     */
    protected $alerts = array();
    
    
    public function Load()
    {
        
        
        /* ROUTING */
        $this->routing = class_exists(static::$ROUTING_CLASS) ? new static::$ROUTING_CLASS() : null;
        if (is_null($this->routing))
        {
            throw new IcebergException('ENVIRONMENT ERROR: Routing class not found.');
        }
        $this->routing->ParseRequest();
        
        /* USER */
        list($username, $password) = $this->routing->GetLogin();
        User::Login($username, $password);
        
        /* THEME */
        $this->theme = class_exists(static::$THEME_CLASS) ? new static::$THEME_CLASS() : null;
        if (is_null($this->theme))
        {
            throw new IcebergException('ENVIRONMENT ERROR: Theme class not found.');
        }
        else
        {
            $this->theme->Load();
        }
        
        /* LANGUAGE */
        $language = $this->routing->GetLanguage();
        if ($language !== I18N::GetLanguage() && I18N::IsActiveLanguage($language))
        {
            I18N::LoadLanguage($language);
        }
        
        do_action('iceberg_environment_load');
        return $this;
    }
    
    public function Config()
    {
        
        $env_tpl = isset($this->environments[$this->environment]) ? $this->environments[$this->environment] : $this->environment;
        $this->theme->SetTemplate($env_tpl);
        
        do_action('iceberg_environment_config');
        return $this;
    }
    
    public function Generate()
    {
        $alerts = Alerts::GetAlerts();
        if (!empty($alerts))
        {
            foreach ($alerts AS $alert)
            {
                $this->AddAlert($alert->GetName(), $alert->GetType());
                if (!$alert->IsPermanent())
                {
                    Alerts::Remove($alert->GetID());
                }
            }
        }
        
        $done = $this->theme->Generate();
        do_action('iceberg_environment_generate', $done);
        return $this;
    }
    
    public function Show()
    {
        if (is_object($this->theme) && get_class($this->theme) === static::$THEME_CLASS)
        {
            $this->theme->PrintTheme();
        }
        do_action('iceberg_environment_show');
        return $this;
    }
    
    
    public function GetRouting()
    {
        return $this->routing;
    }
    
    public function GetTheme()
    {
        return $this->theme;
    }
    
    
    public function AddAlert($txt, $type='info')
    {
        return array_push($this->alerts, array('type'=>$type, 'text'=>$txt));
    }
    
    public function GetAlerts()
    {
        return $this->alerts;
    }
    
    
    public static function GetEnvironment()
    {
        return Iceberg::GetIcebergEnvironment();
    }
    
    public static function GetEnvironmentRouting()
    {
        $env = Iceberg::GetIcebergEnvironment();
        return $env->GetRouting();
    }
    
    public static function GetEnvironmentTheme()
    {
        $env = Iceberg::GetIcebergEnvironment();
        return $env->GetTheme();
    }
    
    public static function GetController()
    {
        $env = Iceberg::GetIcebergEnvironment();
        return $env->environment;
    }
    
    public static function SetController($environment)
    {
        $env = Iceberg::GetIcebergEnvironment();
        return $env->environment = $environment;
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
