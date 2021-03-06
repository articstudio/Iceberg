<?php

/** Include helpers config file */
require_once ICEBERG_DIR_HELPERS . 'install.php';

/**
 * Install
 * 
 * Manage Iceberg installation
 *  
 * @todo Write htacces API / Backend / Frontend
 * @todo admin new domain
 * @package Iceberg
 * @subpackage Install
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0 
 */
class Install
{
    
    protected static $STEPS = array(
        'INSTRUCTIONS',
        'REQUERIMENTS',
        'CONFIGURATION',
        'INSTALLATION'
    );
    
    /**
     * Request key for language
     */
    const REQUEST_KEY_LANGUAGE = 'lang';
    
    /**
     * Request key for step
     */
    const REQUEST_KEY_STEP = 'step';
    
    /**
     * Request key for step
     */
    const REQUEST_KEY_TIMEZONE = 'timezone';
    
    /**
     * Request key for databases list
     */
    const REQUEST_KEY_DB_LIST = 'dbslist';
    
    /**
     * Request key for database prefix
     */
    const REQUEST_KEY_DB_PREFIX = 'dbprefix';
    
    /**
     * Request key for domains list
     */
    const REQUEST_KEY_DOMAINS_LIST = 'domainslist';
    
    /**
     * Request key for username
     */
    const REQUEST_KEY_USERNAME = 'username';
    
    /**
     * Request key for password
     */
    const REQUEST_KEY_PASSWORD = 'password';
    
    /**
     * Request key for email
     */
    const REQUEST_KEY_EMAIL = 'email';
    
    /**
     * Request key for reinstall
     */
    const REQUEST_KEY_REINSTALL = 'reinstall';
    
    
    public static function IsInstallationProcess()
    {
        return (!defined('ICEBERG_DB_PREFIX') || static::IsReInstallationProcess());
    }
    
    public static function IsReInstallationProcess()
    {
        return (Request::IssetKeyGP(self::REQUEST_KEY_REINSTALL) && IcebergSecurity::CheckNonce('iceberg_reinstall', Request::GetValueGP(self::REQUEST_KEY_REINSTALL)));
    }
    
    public static function Initialize()
    {
        if (defined('ICEBERG_INSTALL'))
        {
            /* Reinstallation */
            if (static::IsReInstallationProcess())
            {
                define('ICEBERG_REINSTALL', true);
                self::SetStep(count(self::$STEPS)-2);
            }
            
            /* Time Zone */
            self::TimeZone();
            
            /* Session */
            ini_set('session.gc_maxlifetime', ICEBERG_SESSION_TIME);
            ini_set('session.gc_divisor', 10000);
            ini_set('session.gc_probability', 1);
            ini_set('session.cookie_lifetime', 0);
            session_name(ICEBERG_SESSION_NAME);
            session_start();
            
            /* Language */
            $lang = Request::GetValueSGP(self::REQUEST_KEY_LANGUAGE, ICEBERG_DEFAULT_LANGUAGE);
            I18N::LoadLanguageInstall($lang);
            Session::SetValue(self::REQUEST_KEY_LANGUAGE, $lang);
            
            /* Step controller */
            $STEP = self::GetStep();
            self::$STEP();
            
            /* Show template*/
            self::Template();
        }
        else
        {
            throw new IcebergException('ERROR INSTALL SYSTEM SECURITY');
        }
    }
    
    public static function GetSteps()
    {
        return self::$STEPS;
    }
    
    public static function GetRequestStep()
    {
        return Request::GetValueGP(self::REQUEST_KEY_STEP, 0);
    }
    
    public static function GetStep($key=null)
    {
        $steps = self::GetSteps();
        $key = is_null($key) ? (int)Request::GetValueGP(self::REQUEST_KEY_STEP, 0) : (int)$key;
        return isset($steps[$key]) ? $steps[$key] : $steps[0];
    }
    
    public static function SetStep($key)
    {
        return Request::SetValueP(self::REQUEST_KEY_STEP, $key);
    }
    
    public static function Template()
    {
        print_template(ICEBERG_DIR_INSTALL . 'index.php', get_install_url());
        
        /*$step = self::GetStep();
        $file_index = ICEBERG_DIR_INSTALL . 'index.php';
        $tpl_index = get_template($file_index, get_install_url(), null);
        $file_step = ICEBERG_DIR_INSTALL . strtolower($step) . '.php';
        $tpl_step = get_template($file_step, get_install_url(), null);
        $tpl = str_replace('%CONTENT%', $tpl_step, $tpl_index);
        print $tpl;*/
    }
    
    public static function INSTRUCTIONS()
    {}
    
    public static function REQUERIMENTS()
    {}
    
    public static function CONFIGURATION()
    {
        if (defined('ICEBERG_REINSTALL'))
        {
            self::Uninstall();
        }
    }
    
    public static function INSTALLATION()
    {
        if (IcebergSecurity::CheckNonce('iceberg_install', Request::GetValueGP(IcebergSecurity::REQUEST_KEY_NONCE)))
        {
            $dbs = Request::GetValueGP(self::REQUEST_KEY_DB_LIST, '[]', true);
            $dbs = str_replace(array('"[',']"'), array('[',']'), $dbs);
            $dbs = json_decode($dbs, true);
            $done = self::GenerateDBFile($dbs);
            if ($done)
            {
                $done = self::CreateDBTables();
                if ($done)
                {
                    $domain = get_base_url(false);
                    $domainAliases = json_decode(Request::GetValueGP(self::REQUEST_KEY_DOMAINS_LIST, '[]', true), true);
                    $timezone = Request::GetValueGP(self::REQUEST_KEY_TIMEZONE, date_default_timezone_get(), true);
                    $username = Request::GetValueGP(self::REQUEST_KEY_USERNAME, '', true);
                    $password = Request::GetValueGP(self::REQUEST_KEY_PASSWORD, '', true);
                    $email = Request::GetValueGP(self::REQUEST_KEY_EMAIL, '', true);
                    $domainID = self::NewDomain($domain, $domainAliases, $timezone, $username, $password, $email);
                }
            }
            if (count_install_errors() == 0)
            {
                add_install_alert('Install finished correctly.');
            }
            else
            {
                add_install_alert('Install not finished.', 'error');
            }
        }
        else
        {
            add_install_alert('Installation error', 'error');
        }
    }
    
    protected static function TimeZone()
    {
        $timezone = Request::GetValueGP(self::REQUEST_KEY_TIMEZONE, 'Europe/Madrid', true);
        ini_set('date.timezone', $timezone);
    }
    
    protected static function GenerateDBFile($dbs)
    {
        $__ICEBERG_DB = array();
        $dbs_php_arr = array();
        $tab = '    ';
        foreach ($dbs AS $value)
        {
            $db_php_arr = array(
                $tab . $tab ."'host'=>'" . $value[0] . "'",
                $tab . $tab ."'port'=>'" . $value[1] . "'",
                $tab . $tab ."'user'=>'" . $value[2] . "'",
                $tab . $tab ."'password'=>'" . $value[3] . "'",
                $tab . $tab ."'dbname'=>'" . $value[4] . "'",
                $tab . $tab ."'charset'=>'" . substr($value[5], 0, strpos($value[5], '_')) . "'",
                $tab . $tab ."'collate'=>'" . $value[5] . "'",
            );
            $db_php = $tab . 'array(' . "\n" . implode(",\n", $db_php_arr) . "\n" . $tab . ')';
            array_push($dbs_php_arr, $db_php);
        }
        $dbs_php = "\n" . '$__ICEBERG_DB=array(' . "\n" . implode(",\n", $dbs_php_arr) . "\n" . ');' . "\n";
        $dbs_php .= "define('ICEBERG_DB_PREFIX', '" . get_request_gp('dbprefix', 'iceberg_'). "');\n";
        eval($dbs_php);
        MySQL::ConnectAll(false, $__ICEBERG_DB);
        $done = MySQL::Connected();
        if ($done)
        {
            $dbs_php_content = '<?php' . $dbs_php;
            $done = file_write(ICEBERG_DB_FILE, $dbs_php_content, File::FILE_WRITE_OVERRIDE);
            if (!$done)
            {
                add_install_error('Create DB File.');
            }
        }
        else
        {
            add_install_error('Create DB Connections fail.');
        }
        return $done;
    }
    
    protected static function CreateDBTables()
    {
        $done = true;
        if (!DBRelation::DB_CreateTable())
        {
            $done = false;
            add_install_error('Create DBRELATION table.');
        }
        if (!Domain::DB_CreateTable())
        {
            $done = false;
            add_install_error('Create DOMAINS table.');
        }
        if (!User::DB_CreateTable())
        {
            $done = false;
            add_install_error('Create USERS table.');
        }
        if (!UserMeta::DB_CreateTable())
        {
            $done = false;
            add_install_error('Create USERS METAS table.');
        }
        if (!Config::DB_CreateTable())
        {
            $done = false;
            add_install_error('Create CONFIG table.');
        }
        if (!Taxonomy::DB_CreateTable())
        {
            $done = false;
            add_install_error('Create TAXONOMY table.');
        }
        if (!Page::DB_CreateTable())
        {
            $done = false;
            add_install_error('Create PAGE table.');
        }
        if (!PageMeta::DB_CreateTable())
        {
            $done = false;
            add_install_error('Create PAGE METAS table.');
        }
        
        /**
        * Taxonomy
        * Page
        * PageMeta
        */
        
        return $done;
    }
    
    protected static function AddRoot($domainID, $role_id, $user, $password=null, $email=null)
    {
        if (is_int($user) && is_null($password) && is_null($email))
        {
            /* @todo Add relation */
            
        }
        else if (is_string($user) && is_string($password) && is_string($email))
        {
            $userID = User::Insert(array(
                'email' => $email,
                'username' => $user,
                'password' => $password,
                'role' => $role_id
            ));
            if ($userID)
            {
                $done = User::Login($user, $password, IcebergSecurity::MakeNonce('login'));
                if (!$done)
                {
                    add_install_error('Root user login.');
                }
            }
            else
            {
                add_install_error('Insert Root user.');
            }
            return $userID;
        }
        return false;
        
    }
    
    public static function NewDomain($domain, $domainAliases, $timezone, $user, $password=null, $email=null)
    {
        $domainID = Domain::DB_Insert(array(
            'name' => $domain
        ));
        if ($domainID)
        {
            /* SET DOMAIN REQUEST ID */
            Domain::SetRequestID($domainID);
            
            /* DOMAIN ALIASES */
            if (is_array($domainAliases) && !empty($domainAliases))
            {
                foreach ($domainAliases AS $alias)
                {
                    $alias .= substr($alias, -1) == '/' ? '' : '/';
                    if ($alias != $domain)
                    {
                        $done = Domain::DB_Insert(
                            array(
                                'name' => $alias,
                            ),
                            array(
                                Domain::RELATION_KEY_CANONICAL => $domainID
                            )
                        );
                        if (!$done)
                        {
                            add_install_error('Insert URL alias to DOMAINS list.');
                        }
                    }
                }
            }
            
            /* PAGE TAXONOMY */
            $pagetaxonomy = new PageTaxonomy(array(
                'name' => _T('Page'),
                'locked' => true
            ));
            $pagetaxonomy_page = PageTaxonomy::Insert($pagetaxonomy);
            
            /* PAGE TYPE */
            $pagetype = new PageType(array(
                'name' => _T('Page'),
                'locked' => true,
                'taxonomy' => array($pagetaxonomy_page)
            ));
            $pagetype_page = PageType::Insert($pagetype);
            
            /* PAGE GROUP */
            $pagegroup = new PageGroup(array(
                'name' => _T('Pages'),
                'locked' => true,
                'type' => array($pagetype_page)
            ));
            $pagegroup_pages = PageGroup::Insert($pagegroup);
            
            /* PAGE CONFIG */
            PageConfig::SaveConfig(array(
                'pagetaxonomy' => $pagetaxonomy_page,
                'pagegroup' => $pagegroup_pages,
                'pagetype' => $pagetype_page
            ));
            
            /* CACHE */
            IcebergCache::SaveConfig(array());
            
            /* REQUEST */
            Request::SaveConfig(array());
            
            /* ROUTING */
            Routing::SaveConfig(array());
            
            /* SESSION */
            Session::SaveConfig(array(
                'name' => ICEBERG_SESSION_NAME,
                'time' => ICEBERG_SESSION_TIME
            ));
            
            /* TIME */
            Time::SaveConfig(array(
                'timezone' => $timezone
            ));
            
            /* FILES */
            File::SaveConfig(array());
            
            /* NUMBERS */
            Number::SaveConfig(array());
            
            /* I18N */
            I18N::SaveConfig(array(
                'default' => get_lang(),
                'languages' => I18N::GetLanguages()
            ));
            
            /* TEMPLATES */
            Template::SaveConfig(array());
            
            /* THEMES */
            Theme::SaveConfig(array());
            
            /* MAINTENANCE */
            Maintenance::SaveConfig(array(
                'active' => true
            ));
            
            /* EXTENSIONS */
            Extension::SaveConfig(array());
            
            /* TAXONOMY ELEMENTS */
            TaxonomyElements::SaveConfig(array());
            
            /* ROLES */
            $role_root = new UserRole(array(
                'name' => _T('Root'),
                'capabilities' => array(
                    'admin_root'
                )
            ));
            $role_root_id = UserRole::Insert($role_root);
            $role_admin = new UserRole(array(
                'name' => _T('Administrator'),
                'capabilities' => array(
                    'admin_login',
                    'module_dashboard_full',
                    'module_structure_full',
                    'module_content_full',
                    'module_media_full',
                    'module_extensions_full',
                    'module_profile_full',
                    'module_configuration_full',
                    'media_edit'
                )
            ));
            UserRole::Insert($role_admin);
            $role_editor = new UserRole(array(
                'name' => _T('Editor'),
                'capabilities' => array(
                    'admin_login',
                    'module_content_full',
                    'module_media_full',
                    'module_profile_own',
                    'media_edit'
                )
            ));
            UserRole::Insert($role_editor);
            $role_translator = new UserRole(array(
                'name' => _T('Author'),
                'capabilities' => array(
                    'admin_login',
                    'module_content_own',
                    'module_media_own',
                    'module_profile_own',
                    'media_edit_own'
                )
            ));
            UserRole::Insert($role_translator);
            $role_user = new UserRole(array(
                'name' => _T('User'),
                'capabilities' => array()
            ));
            $role_user_id = UserRole::Insert($role_user);
            
            /* USER CONFIG */
            UserConfig::SaveConfig(array(
                'rootrole' => $role_root_id,
                'defaultrole' => $role_user_id
            ));
            
            /* ROOT USER */
            $userID = static::AddRoot($domainID, $role_root_id, $user, $password, $email);
            
            /**
             * Metatags
             * Menubar/s
             */
            
            /* THEME INSTALL */
            Theme::Active('backend', 'default');
            Theme::Active('frontend', 'default');
            
        }
        else {
            add_install_error('Insert URL Base to DOMAINS list.');
        }
        return $domainID;
    }
    
    protected static function Uninstall()
    {
        $done = $done_file = true;
        if (defined('ICEBERG_DB_PREFIX'))
        {
            add_install_alert('DB file exists.');
            MySQL::ConnectAll();
            if (MySQL::Connected())
            {
                add_install_alert('Connected to database.');
                if (!DBRelation::DB_DropTable())
                {
                    $done = false;
                    add_install_error('Drop DBRELATION table.');
                }
                if (!Domain::DB_DropTable())
                {
                    $done = false;
                    add_install_error('Drop DOMAINS table.');
                }
                if (!User::DB_DropTable())
                {
                    $done = false;
                    add_install_error('Drop USERS table.');
                }
                if (!UserMeta::DB_DropTable())
                {
                    $done = false;
                    add_install_error('Drop USERS METAS table.');
                }
                if (!Config::DB_DropTable())
                {
                    $done = false;
                    add_install_error('Drop CONFIG table.');
                }
                if (!Taxonomy::DB_DropTable())
                {
                    $done = false;
                    add_install_error('Drop TAXONOMY table.');
                }
                if (!Page::DB_DropTable())
                {
                    $done = false;
                    add_install_error('Drop PAGE table.');
                }
                if (!PageMeta::DB_DropTable())
                {
                    $done = false;
                    add_install_error('Drop PAGE METAS table.');
                }

                /**
                * Taxonomy
                * Page
                * PageMeta
                */
                
            }
            $dbs_php_content = '<?php' . "\n\n";
            $done_file = file_write(ICEBERG_DB_FILE, $dbs_php_content, File::FILE_WRITE_OVERRIDE);
            if (!$done_file)
            {
                add_install_error('Unwrite DB File.');
            }
        }
        if ($done && $done_file)
        {
            add_install_alert('Uninstall finished correctly.');
        }
        else
        {
            add_install_alert('Uninstall not finished.', 'error');
        }
    }
}