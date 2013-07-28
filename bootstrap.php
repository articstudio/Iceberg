<?php
/** Defines the Bootstrap file is included */
define('ICEBERG_BOOTSTRAP', true );

/** Required configuration file */
require_once dirname( __FILE__ ) . '/config.php';

/** Required Database configuration file */
if (is_file(ICEBERG_DB_FILE))
{
    require_once ICEBERG_DB_FILE;
}

/** Required settings file */
require_once ICEBERG_DIR_INCLUDES . '/settings.php';

/** Required IcebergException file */
require_once ICEBERG_DIR_INCLUDES . '/Exception.class.php';

/**
 * Iceberg Bootstrap
 * 
 * Boot the system
 *  
 * @package Iceberg
 * @subpackage Bootstrap
 * @author Marc Mascort Bou marc@marcmascort.com
 * @version 1.0
 * @since 0.1
 */
class Bootstrap
{
    /**
     * Bootstrap status: Not initialized
     */
    const STATUS_NULL = 0;
    
    /**
     * Bootstrap status: Initialized
     */
    const STATUS_BOOTSTRAP = 1;
    
    /**
     * Bootstrap status: Initialized + ICEBERG
     */
    const STATUS_ICEBERG = 2;

    /**
     * Default options
     * <ul>
     * <li>admin (bool) => Is admin environment
     * <li>api (bool) => Is API environment
     * <li>initialized (bool) => Is initialized
     * <li>root (string) => Root path
     * </ul>
     * 
     * @var array
     */
    private static $default_options = array(
        'admin' => false,
        'api' => false,
        'initialized' => false,
        'root' => '/'
    );

    /**
     * Initialize Bootstrap
     * 
     * @param array Arguments for bootstrap initialization
     * @throws IcebergException Bootstrap is initialized, can't be reinitialized
     */
    public static function Initialize( $args=array() )
    {
        if ( !Bootstrap::IsInitialized() )
        {
            Bootstrap::Config($args);
            Bootstrap::Load();
            Bootstrap::Settings();
            Bootstrap::Iceberg();
        }
        else
        {
            throw new IcebergException( 'ICEBERG BOOTSTRAP ERROR: Bootstrap is initialized, can\'t be reinitialized.' );
        }
    }

    /**
     * Return if Bootstrap is initialized
     * 
     * @global bool $__ICEBERG_INITIALIZED
     * @return bool 
     */
    public static function IsInitialized()
    {
        global $__ICEBERG_INITIALIZED;
        return ( isset($__ICEBERG_INITIALIZED) && ($__ICEBERG_INITIALIZED > Bootstrap::STATUS_NULL) );
    }

    /**
     * Return a bootstrap configuration value for key
     * 
     * @global array $__ICEBERG_BOOTSTRAP
     * @param string Index of value
     * @return mixed 
     */
    public static function GetValue( $key )
    {
        global $__ICEBERG_BOOTSTRAP;
        return ( isset($__ICEBERG_BOOTSTRAP) && is_array($__ICEBERG_BOOTSTRAP) && array_key_exists($key , $__ICEBERG_BOOTSTRAP) ) ? $__ICEBERG_BOOTSTRAP[$key] : false;
    }

    /**
     * Configuration of Bootstrap
     * 
     * @global bool $__ICEBERG_INITIALIZED
     * @global bool $__ICEBERG_ADMIN
     * @global bool $__ICEBERG_API
     * @global array $__ICEBERG_BOOTSTRAP
     * @see Bootstrap::$default_options
     * @param array Arguments for bootstrap configuration
     */
    private static function Config( $args )
    {
        global $__ICEBERG_INITIALIZED, $__ICEBERG_ADMIN, $__ICEBERG_API, $__ICEBERG_BOOTSTRAP;
        $args[ 'initialized' ] = $__ICEBERG_INITIALIZED = Bootstrap::STATUS_BOOTSTRAP;
        $__ICEBERG_BOOTSTRAP = array_merge(Bootstrap::$default_options , $args);
        $__ICEBERG_ADMIN = Bootstrap::GetValue('admin');
        $__ICEBERG_API = Bootstrap::GetValue('api');
    }

    /**
     * Loading basic libraries of system
     */
    private static function Load()
    {
        /**
         * Basic functions file
         */
        require_once ICEBERG_DIR_INCLUDES . 'functions.php';
        
        /**
         * Actions functions file
         */
        require_once ICEBERG_DIR_INCLUDES . 'actions.php';
        
        /**
         * Hooks functions file
         */
        //require_once ICEBERG_DIR_INCLUDES . 'hooks.php';
        
        /**
         * Iceberg class file
         */
        require_once ICEBERG_DIR_INCLUDES . 'Iceberg.class.php';
    }

    /**
     * Sets basic system settings
     */
    private static function Settings()
    {
        self::SetDebugMode(ICEBERG_DEBUG_MODE);
        self::CompatibleVersion();
    }

    /**
     * Sets Debug Mode system
     * 
     * @param bool Active debug mode
     */
    private static function SetDebugMode($debug=true)
    {
        if ($debug)
        {
            error_reporting(-1);
            ini_set('display_errors', 1);
        }
        else
        {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
    }

    /**
     * Checks if PHP version is compatible with Iceberg version requeriments
     * 
     * @throws IcebergException 
     */
    private static function CompatibleVersion()
    {
        if ( !phpVersionCompatible( ICEBERG_PHP_VERSION_REQUIRED ) )
        {
            throw new IcebergException( sprintf( 'Your server is running version %1$s to PHP, but Iceberg v%2$s requires at least version %3$s.', phpversion(), ICEBERG_VERSION, ICEBERG_PHP_VERSION_REQUIRED ) );
        }
    }
    
    /**
     * Execute Iceberg
     * 
     * @static
     * @global Iceberg $__ICEBERG 
     */
    private static function Iceberg()
    {
        global $__ICEBERG;
        $__ICEBERG = new Iceberg();
        $__ICEBERG->LoadEnvironment();
    }
    
}
