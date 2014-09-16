<?php

/**
 * Iceberg
 * 
 * Content Management System
 *  
 * @package Iceberg
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0 
 */
class Iceberg {
    
    private static $ENVIRONMENT_LIST = array(
        array(
            'function' => 'in_admin',
            'class' => 'IcebergBackend'
        ),
        array(
            'function' => 'in_api',
            'class' => 'IcebergAPI'
        ),
        array(
            'function' => 'in_web',
            'class' => 'IcebergFrontend'
        )
    );
    
    private $Environment = null;
    
    private $time_start = 0;
    
    private $time_end = 0;
    
    /**
     * Constructor a Iceberg
     */
    public function __construct()
    {
        $this->time_start = microtime(true);
        self::LoadBase();
        self::Initialize();
        self::Session();
    }

    /**
     * Load basic libraries of Iceberg
     */
    private static function LoadBase()
    {
        require_once ICEBERG_DIR_INCLUDES .'MySQL.class.php'; /* @todo MySQL documentation */
        require_once ICEBERG_DIR_INCLUDES .'ObjectDBBase.class.php'; /* @todo ObjectDBBase documentation */
        require_once ICEBERG_DIR_INCLUDES .'ObjectDB.class.php'; /* @todo ObjectDB documentation */
        require_once ICEBERG_DIR_INCLUDES .'DBRelation.class.php'; /* @todo DBRelation documentation */
        require_once ICEBERG_DIR_INCLUDES .'ObjectDBRelations.class.php'; /* @todo ObjectDBRelations documentation */
        
        require_once ICEBERG_DIR_INCLUDES .'Config.class.php';
        require_once ICEBERG_DIR_INCLUDES .'ObjectConfig.class.php'; /* @todo ObjectConfig documentation */
        require_once ICEBERG_DIR_INCLUDES .'IcebergCache.class.php';
        
        
        require_once ICEBERG_DIR_INCLUDES .'Domain.class.php';
        
        
        require_once ICEBERG_DIR_INCLUDES .'Time.class.php';
        require_once ICEBERG_DIR_INCLUDES .'Number.class.php';
        require_once ICEBERG_DIR_INCLUDES .'Metatags.class.php';
        require_once ICEBERG_DIR_INCLUDES .'File.class.php'; /* @todo File documentation */
        require_once ICEBERG_DIR_INCLUDES .'Session.class.php';
        require_once ICEBERG_DIR_INCLUDES .'Request.class.php';
        require_once ICEBERG_DIR_INCLUDES .'I18N.class.php';
        require_once ICEBERG_DIR_INCLUDES .'Extension.class.php';
        require_once ICEBERG_DIR_INCLUDES .'Menubar.class.php';
        
        
        require_once ICEBERG_DIR_INCLUDES .'Routing.class.php';
        require_once ICEBERG_DIR_INCLUDES .'RoutingBackendAPI.class.php';
        
        
        
        require_once ICEBERG_DIR_INCLUDES .'User.class.php';
        require_once ICEBERG_DIR_INCLUDES .'UserMeta.class.php';
        
        
        
        require_once ICEBERG_DIR_INCLUDES .'Template.class.php';
        require_once ICEBERG_DIR_INCLUDES .'Theme.class.php';
        require_once ICEBERG_DIR_INCLUDES .'ThemeBackendAPI.class.php';
        require_once ICEBERG_DIR_INCLUDES .'Environment.class.php';
        require_once ICEBERG_DIR_INCLUDES .'IcebergBackend.class.php';
        require_once ICEBERG_DIR_INCLUDES .'IcebergAPI.class.php';
        require_once ICEBERG_DIR_INCLUDES .'IcebergFrontend.class.php';
        require_once ICEBERG_DIR_INCLUDES .'Maintenance.class.php';
        
        
        require_once ICEBERG_DIR_INCLUDES .'PageConfig.class.php';
        require_once ICEBERG_DIR_INCLUDES .'Taxonomy.class.php';
        require_once ICEBERG_DIR_INCLUDES .'TaxonomyElements.class.php';
        require_once ICEBERG_DIR_INCLUDES .'ObjectTaxonomy.class.php';
        require_once ICEBERG_DIR_INCLUDES .'PageTaxonomy.class.php';
        require_once ICEBERG_DIR_INCLUDES .'PageGroup.class.php';
        require_once ICEBERG_DIR_INCLUDES .'PageType.class.php';
        
        
        require_once ICEBERG_DIR_INCLUDES .'PageMeta.class.php';
        require_once ICEBERG_DIR_INCLUDES .'Page.class.php';
        
        /*
        require_once ICEBERG_DIR_INCLUDES .'Widget.class.php';
        require_once ICEBERG_DIR_INCLUDES .'TaxonomyElements.class.php';
        require_once ICEBERG_DIR_INCLUDES .'TaxonomyRelationship.class.php';
        require_once ICEBERG_DIR_INCLUDES .'PHPMailer.class.php';
        */
        require_once ICEBERG_DIR_INCLUDES .'Install.class.php'; /* @todo MySQL documentation */
    }

    /**
     * Initialize Iceberg
     * 
     * @global bool $__ICEBERG_INITIALIZED
     * @throws Exception 
     */
    private static function Initialize()
    {
        global $__ICEBERG_INITIALIZED;
        if ( defined( 'ICEBERG_BOOTSTRAP' ) && Bootstrap::IsInitialized() && !self::isInitialized() )
        {
            $__ICEBERG_INITIALIZED = Bootstrap::STATUS_ICEBERG;
            self::LanguageInitialize(); /* Load default language */
            self::LoadRequest(); /* Load resquest */
            self::Install(); /* Install if it is necessary */
            self::DataBases(); /* Database connect */
            $domain = self::Domain(); /* Load domain data */
            if (!is_null($domain))
            {
                self::LoadConfig(); /* Load basic configuration */
                self::LoadExtensions(); /* Load Extensions */
                self::LoadDinamicLanguages(); /* Load Dinamic Languages */
                self::Configure(); /* Configure */
                self::Taxonomy(); /* Load taxonomy */
            }
        }
        else
        {
            throw new IcebergException( 'ICEBERG INITIALIZATION ERROR: The bootstrap is not initialized.' );
        }
    }
    
    /**
     * Initialize languages 
     */
    private static function LanguageInitialize()
    {
        I18N::Initialize();
        I18N::LoadDefaultLanguage();
    }
    
    /**
     * Initialize request 
     */
    private static function LoadRequest()
    {
        Request::LoadRequest();
        action_event('iceberg_load_request');
    }

    /**
     * Install process
     * 
     * @todo Reinstall
     */
    private static function Install()
    {
        if (Install::IsInstallationProcess()) {
            define('ICEBERG_INSTALL', true);
            Install::Initialize();
            
            MySQL::PrintLog();
            
            exit();
        }
    }

    /**
     * Initialize Data Bases
     */
    private static function DataBases()
    {
        MySQL::ConnectAll(true);
    }
    
    /**
     * Initialize domain 
     */
    private static function Domain()
    {
        return Domain::Initialize();
    }

    /**
     * Configuration loader
     */
    private static function LoadConfig()
    {
        Config::AddRow(I18N::$CONFIG_KEY);
        Config::AddRow(Request::$CONFIG_KEY);
        Config::AddRow(Routing::$CONFIG_KEY);
        Config::AddRow(Session::$CONFIG_KEY);
        Config::AddRow(Time::$CONFIG_KEY);
        Config::AddRow(File::$CONFIG_KEY);
        Config::AddRow(Number::$CONFIG_KEY);
        Config::AddRow(Time::$CONFIG_KEY);
        Config::AddRow(Theme::$CONFIG_KEY);
        Config::AddRow(Maintenance::$CONFIG_KEY);
        Config::AddRow(Extension::$CONFIG_KEY);
        Config::AddRow(PageConfig::$CONFIG_KEY);
        Config::AddRow(Metatag::$CONFIG_KEY);
        Config::AddRow(IcebergCache::$CONFIG_KEY);
        Config::AddRow(Template::$CONFIG_KEY);
        Config::AddRow(TaxonomyElements::$CONFIG_KEY);
        Config::AddRow(Menubar::$CONFIG_KEY);
        return Config::LoadConfig();
    }
    
    /**
     * Extensions loader
     * @static
     * @global array $__ICEBERG_EXTENSIONS 
     */
    private static function LoadExtensions()
    {
        Extension::LoadActives();
    }
    
    /**
     * Load dinamic languages 
     */
    private static function LoadDinamicLanguages()
    {
        I18N::LoadDinamicLanguages();
    }
    
    /**
     * Configure Iceberg
     * 
     * @uses action_event() for 'iceberg_configure'
     */
    private static function Configure()
    {
        Time::Configure();
        action_event('iceberg_configure');
    }
    
    /**
     * Taxonomy CMS
     * @static
     * @uses action_event() for 'cms_taxonomy' 
     */
    private static function Taxonomy()
    {
        TaxonomyElements::Load();
        action_event('iceberg_taxonomy');
    }

    /**
     * Session loader
     * @static 
     */
    private static function Session()
    {
        Session::Start();
        //Session::LoadSession();
        action_event('iceberg_session');
    }

    /**
     * Environment loader
     */
    public function LoadEnvironment()
    {
        $class = null;
        foreach (self::$ENVIRONMENT_LIST AS $env)
        {
            if (function_exists($env['function']))
            {
                $in = $env['function']();
                if ($in)
                {
                    $class = $env['class'];
                    break;
                }
            }
        }
        if (!is_null($class) && class_exists($class)) {
            $this->Environment = new $class();
            
            $this->Environment->Load()->Config();
            $content = '';
            
            list($content) = action_event('filter_iceberg_environment_content', $content);
            if (empty($content))
            {
                ob_start();
                $this->Environment->Generate()->Show();
                $content = ob_get_clean();
            }
            list($content) = action_event('iceberg_environment_content', $content);
            printf('%s', $content);
            
            $this->time_end = microtime(true);
            $time = $this->time_end - $this->time_start;
            IcebergDebug::PrintLog($time);
            
            action_event('iceberg_loaded');
        }
        else {
            throw new IcebergException('ICEBERG INITIALIZATION ERROR: Environment not found.');
        }
    }
    
    public function GetEnvironment()
    {
        return $this->Environment;
    }
    
    /**
     * Return if ICEBERG is initialized
     * @static
     * @global bool $__ICEBERG_INITIALIZED
     * @return bool 
     */
    public static function isInitialized() {
        global $__ICEBERG_INITIALIZED;
        return ( !isset( $__ICEBERG_INITIALIZED ) OR $__ICEBERG_INITIALIZED < Bootstrap::STATUS_ICEBERG ) ? false : true;
    }
    
    public static function GetIceberg()
    {
        global $__ICEBERG;
        return $__ICEBERG;
    }
    
    public static function GetIcebergEnvironment()
    {
        $iceberg = self::GetIceberg();
        return $iceberg->GetEnvironment();
    }
}
