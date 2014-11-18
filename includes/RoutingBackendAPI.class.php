<?php

/** Include helpers routing file */
require_once ICEBERG_DIR_HELPERS . 'routing-backend-api.php';

/** Include frontend routing file */
require_once ICEBERG_DIR_INCLUDES . 'RoutingFrontend.class.php';

abstract class RoutingBackendAPI extends Routing
{
    
    const REQUEST_KEY_ENVIRONMENT = 'environment';
    const REQUEST_KEY_LANGUAGE = 'lang';
    const REQUEST_KEY_MODULE = 'module';
    const REQUEST_KEY_MODE = 'mode';
    const REQUEST_KEY_ACTION = 'action';
    const REQUEST_KEY_ID = 'id';
    const REQUEST_KEY_GROUP = 'group';
    
    const REQUEST_KEY_PAGE = 'page';
    const REQUEST_KEY_TAXONOMY = 'taxonomy';
    
    const REQUEST_KEY_USERNAME = 'username';
    const REQUEST_KEY_PASSWORD = 'password';
    const REQUEST_KEY_LOGIN = 'login';
    const REQUEST_KEY_LOGOUT = 'logout';
    
    const REQUEST_ENVIRONMENT_ICEBERG_API = 'iceberg';
    const REQUEST_ENVIRONMENT_ADMIN_API = 'admin';
    const REQUEST_ENVIRONMENT_API = 'api';
    
    protected $request = array(
        'environment' => null,
        'lang' => null,
        'module' => null,
        'mode' => null,
        'action' => null,
        'id' => null,
        'group' => null,
        'page' => null,
        'taxonomy' => null,
        'username' => null,
        'password' => null
    );
    
    public static $MODULE_DASHBOARD = array(
        'module' => 'dashboard',
        'template' => 'dashboard.php',
        'name' => 'Dashboard',
        'capability' => 'admin_login'
    );
    
    public static $ROUTING_ERROR = array(
        'module' => 'error',
        'mode' => 'error',
        'action' => 'error',
        'template' => 'error.php',
        'name' => 'Error'
    );
    
    protected static $MODULES = array(
        'structure' => array(
            'template' => 'structure.php',
            'name' => 'Structure',
        ),
        'content' => array(
            'template' => 'content.php',
            'name' => 'Content',
        ),
        'media' => array(
            'template' => 'media.php',
            'name' => 'Media',
        ),
        'extensions' => array(
            'template' => 'extensions.php',
            'name' => 'Extensions'
        ),
        'profile' => array(
            'template' => 'profile.php',
            'name' => 'Profile'
        ),
        'configuration' => array(
            'template' => 'configuration.php',
            'name' => 'Configuration',
        )
    );
    
    protected $modules;
    protected $module;
    protected $modes;
    protected $mode;
    protected $actions;
    protected $action;
    
    public function ParseRequest()
    {
        $this->request[static::REQUEST_KEY_LANGUAGE] = Request::GetValueSGP(static::REQUEST_KEY_LANGUAGE, ICEBERG_DEFAULT_LANGUAGE, true);
        $this->request[static::REQUEST_KEY_MODULE] = Request::GetValueSGP(static::REQUEST_KEY_MODULE, null, true);
        $this->request[static::REQUEST_KEY_MODE] = Request::GetValueSGP(static::REQUEST_KEY_MODE, null, true);
        $this->request[static::REQUEST_KEY_ACTION] = Request::GetValueSGP(static::REQUEST_KEY_ACTION, null, true);
        $this->request[static::REQUEST_KEY_ID] = Request::GetValueSGP(static::REQUEST_KEY_ID, null, true);
        $this->request[static::REQUEST_KEY_GROUP] = Request::GetValueSGP(static::REQUEST_KEY_GROUP, null, true);
        $this->request[static::REQUEST_KEY_PAGE] = Request::GetValueSGP(static::REQUEST_KEY_PAGE, null, true);
        $this->request[static::REQUEST_KEY_TAXONOMY] = Request::GetValueSGP(static::REQUEST_KEY_TAXONOMY, null, true);
        /* @todo Only correct values */
        $this->request[static::REQUEST_KEY_USERNAME] = Request::GetValueGP(static::REQUEST_KEY_USERNAME, null, true);
        $this->request[static::REQUEST_KEY_PASSWORD] = Request::GetValueGP(static::REQUEST_KEY_PASSWORD, null, true);
        $this->request[static::REQUEST_KEY_LOGIN] = (bool)Request::GetValueGP(static::REQUEST_KEY_LOGIN, false, true);
        $this->request[static::REQUEST_KEY_LOGOUT] = (bool)Request::GetValueGP(static::REQUEST_KEY_LOGOUT, false, true);
        Session::SetValue(static::REQUEST_KEY_LANGUAGE, $this->request[static::REQUEST_KEY_LANGUAGE]);
        $this->SetLanguage($this->request[static::REQUEST_KEY_LANGUAGE]);
        $this->SetLogin($this->request[static::REQUEST_KEY_USERNAME], $this->request[static::REQUEST_KEY_PASSWORD]);
    }
    
    public function ProcessRequest()
    {
        $this->modules = static::GetModules(false);
        $this->module = static::GetModule(null, false);
        $this->modes = static::GetModes(false);
        $this->mode = static::GetMode(null, false);
        $this->actions = static::GetActions(false);
        $this->action = static::GetAction(null, false); 
    }
    
    
    
    
    public static function GetRequestLogin()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_LOGIN);
    }
    
    public static function GetRequestLogout()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_LOGOUT);
    }
    
    
    public static function GetModules($cache=true)
    {
        if ($cache)
        {
            $routing = static::GetRouting(); 
            return $routing->modules;
        }
        $modules = static::$MODULES;
        $modules = apply_filters('get_modules', $modules);
        foreach ($modules AS $k => $module)
        {
            $modules[$k]['module'] = $k;
            $modules[$k]['capability'] = (!isset($module['capability']) || !is_string($module['capability'])) ? 'module_' . $k  : $module['capability'];
            $modules[$k]['icon'] = (!isset($module['icon']) || !is_string($module['icon'])) ? get_theme_url() . 'img/icon_' . $k . '.png'  : $module['icon'];
            $modules[$k]['badge'] = (!isset($module['badge']) || (!is_string($module['badge']) && !is_int($module['badge']))) ? false  : $module['badge'];
        }
        $modules = apply_filters('get_modules_after', $modules);
        $routing = static::GetRouting(); 
        $routing->modules = $modules;
        return $modules;
    }
    
    public static function GetRequestModule()
    {
        $routing = static::GetRouting(); 
        $module = apply_filters('get_request_module', $routing->GetParsedRequestValue(static::REQUEST_KEY_MODULE));
        return $module;
    }
    
    public static function GetModule($key=null, $cache=true)
    {
        if ($cache)
        {
            $routing = static::GetRouting(); 
            $data = $routing->module;
            if (!is_null($key) && is_array($data)) {
                return isset($data[$key]) ? $data[$key] : null;
            }
            return $data;
        }
        $module = static::GetRequestModule(); 
        $modules = static::GetModules($cache); 
        $data = array();
        if ($module === null)
        {
            if (User::HasCapability('module_' . static::$MODULE_DASHBOARD['module']))
            {
                $data = static::$MODULE_DASHBOARD;
                $module = $data['module'];
            }
            else if(!empty($modules))
            {
                $keys = array_keys($modules);
                $module = $keys[0];
                $data = $modules[$module];
            }
        }
        else if ($module === static::$MODULE_DASHBOARD['module']) 
        {
            $data = static::$MODULE_DASHBOARD;
            $module = $data['module'];
        }
        else if(isset($modules[$module]))
        {
            $data = $modules[$module];
        }
        $data = apply_filters('get_module', $data, $key, $module);
        $data = apply_filters('get_module_' . $module, $data, $key);
        if (!is_null($key))
        {
            $data = apply_filters('get_module_' . $module . '_' . $key, $data);
        }
        if (empty($data))
        {
            $data = static::$ROUTING_ERROR;
            $module = $data['module'];
        }
        $data['module'] = $module;
        $data['name'] = (!isset($data['name']) || !is_string($data['name']) || empty($data['name'])) ? static::$ROUTING_ERROR['name'] : $data['name'];
        $data['template'] = (!isset($data['template']) || !is_string($data['template']) || empty($data['template'])) ? static::$ROUTING_ERROR['template'] : $data['template'];
        $data['capability'] = (!isset($data['capability']) || !is_string($data['capability'])) ? 'module_' . $data['module']  : $data['capability'];
        $routing = static::GetRouting(); 
        $routing->module = $data;
        if (!is_null($key) && is_array($data)) {
            return isset($data[$key]) ? $data[$key] : null;
        }
        return $data;
    }
    
    
    public static function GetModes($cache=true)
    {
        if ($cache)
        {
            $routing = static::GetRouting(); 
            return $routing->modes;
        }
        $modes = array();
        $module = static::GetModule('module', $cache);
        $modes = apply_filters('get_modes', $modes, $module);
        $modes = apply_filters('get_modes_' . $module, $modes);
        foreach ($modes AS $k => $mode)
        {
            $modes[$k]['mode'] = $k;
            $modes[$k]['module'] = $module;
            $modes[$k]['capability'] = (!isset($mode['capability']) || !is_string($mode['capability'])) ? 'mode_' . $module . '_' . $k  : $mode['capability'];
        }
        $modes = apply_filters('get_modes_after', $modes);
        $routing = static::GetRouting(); 
        $routing->modes = $modes;
        return $modes;
    }
    
    public static function GetRequestMode()
    {
        $routing = static::GetRouting(); 
        $mode = apply_filters('get_request_mode', $routing->GetParsedRequestValue(static::REQUEST_KEY_MODE));
        return $mode;
    }
    
    public static function GetMode($key=null, $cache=true)
    {
        if ($cache)
        {
            $routing = static::GetRouting(); 
            $data = $routing->mode;
            if (!is_null($key) && is_array($data)) {
                return isset($data[$key]) ? $data[$key] : null;
            }
            return $data;
        }
        $mode = static::GetRequestMode(); 
        $modes = static::GetModes($cache);
        $module = static::GetModule('module', $cache);
        $data = array();
        if (!empty($modes))
        {
            if ($mode === null)
            {
                $keys = array_keys($modes);
                $mode = $keys[0];
            }
            if (isset($modes[$mode]))
            {
                $data = $modes[$mode];
            }
        }
        $data = apply_filters('get_mode', $data, $key, $mode, $module);
        $data = apply_filters('get_mode_' . $module, $data, $key, $mode);
        $data = apply_filters('get_mode_' . $module . '_' . $mode, $data, $key);
        if (!is_null($key))
        {
            $data = apply_filters('get_mode_' . $module . '_' . $mode . '_' . $key, $data);
        }
        if (empty($data))
        {
            $data = static::$ROUTING_ERROR;
            $mode = $data['mode'];
            $module = $data['module'];
        }
        $data['module'] = $module;
        $data['mode'] = $mode;
        $data['name'] = (!isset($data['name']) || !is_string($data['name']) || empty($data['name'])) ? static::$ROUTING_ERROR['name'] : $data['name'];
        $data['template'] = (!isset($data['template']) || !is_string($data['template']) || empty($data['template'])) ? static::$ROUTING_ERROR['template'] : $data['template'];
        $data['capability'] = (!isset($data['capability']) || !is_string($data['capability'])) ? 'mode_' . $data['module'] . '_' . $data['mode']  : $data['capability'];
        $routing = static::GetRouting(); 
        $routing->mode = $data;
        if (!is_null($key) && is_array($data)) {
            return isset($data[$key]) ? $data[$key] : null;
        }
        return $data;
    }
    
    public static function GetActions($cache=true)
    {
        if ($cache)
        {
            $routing = static::GetRouting(); 
            return $routing->actions;
        }
        $actions = array();
        $module = static::GetModule('module', $cache);
        $mode = static::GetMode('mode', $cache);
        $actions = apply_filters('get_actions', $actions, $mode, $module);
        $actions = apply_filters('get_actions_' . $module, $actions, $mode);
        $actions = apply_filters('get_actions_' . $module . '_' . $mode, $actions);
        foreach ($actions AS $k => $action)
        {
            $actions[$k]['action'] = $k;
            $actions[$k]['mode'] = $mode;
            $actions[$k]['module'] = $module;
            $actions[$k]['capability'] = (!isset($action['capability']) || !is_string($action['capability'])) ? 'action_' . $module . '_' . $mode . '_' . $k  : $action['capability'];
        }
        $actions = apply_filters('get_actions_after', $actions);
        $routing = static::GetRouting(); 
        $routing->actions = $actions;
        return $actions;
    }
    
    public static function GetRequestAction()
    {
        $routing = static::GetRouting(); 
        $action = apply_filters('get_request_action', $routing->GetParsedRequestValue(static::REQUEST_KEY_ACTION));
        return $action;
    }
    
    public static function GetAction($key=null, $cache=true)
    {
        if ($cache)
        {
            $routing = static::GetRouting(); 
            $data = $routing->action;
            if (!is_null($key) && is_array($data)) {
                return isset($data[$key]) ? $data[$key] : null;
            }
            return $data;
        }
        $action = static::GetRequestAction(); 
        $actions = static::GetActions($cache);
        $data = array();
        $module = static::GetModule('module', $cache);
        $mode = static::GetMode('mode', $cache);
        if (!empty($actions))
        {
            if ($action === null)
            {
                $keys = array_keys($actions);
                $action = $keys[0];
            }
            if (isset($actions[$action]))
            {
                $data = $actions[$action];
            }
        }
        $data = apply_filters('get_action', $data, $key, $action, $mode, $module);
        $data = apply_filters('get_action_' . $module, $data, $key, $action, $mode);
        $data = apply_filters('get_action_' . $module . '_' . $mode, $data, $key, $action);
        $data = apply_filters('get_action_' . $module . '_' . $mode . '_' . $action, $data, $key);
        if (!is_null($key))
        {
            $data = apply_filters('get_action_' . $module . '_' . $mode . '_' . $action . '_' . $key, $data);
        }
        if (empty($data))
        {
            $data = static::$ROUTING_ERROR;
            $action = $data['action'];
            $mode = $data['mode'];
            $module = $data['module'];
        }
        $data['module'] = $module;
        $data['mode'] = $mode;
        $data['action'] = $action;
        $data['name'] = (!isset($data['name']) || !is_string($data['name']) || empty($data['name'])) ? static::$ROUTING_ERROR['name'] : $data['name'];
        $data['template'] = (!isset($data['template']) || !is_string($data['template']) || empty($data['template'])) ? static::$ROUTING_ERROR['template'] : $data['template'];
        $data['capability'] = (!isset($data['capability']) || !is_string($data['capability'])) ? 'action_' . $data['module'] . '_' . $data['mode'] . '_' . $data['action']  : $data['capability'];
        $routing = static::GetRouting(); 
        $routing->action = $data;
        if (!is_null($key) && is_array($data))
        {
            return isset($data[$key]) ? $data[$key] : null;
        }
        return $data;
    }
    
    public static function GetRequestID()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_ID);
    }
    
    public static function GetRequestGroup()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_GROUP);
    }
    
    public static function SanitizeActionParams($params=array())
    {
        if (!isset($params[static::REQUEST_KEY_MODULE]))
        {
            $params[static::REQUEST_KEY_MODULE] = static::GetModule('module');
        }
        if (!isset($params[static::REQUEST_KEY_MODE]))
        {
            $params[static::REQUEST_KEY_MODE] = static::GetMode('mode');
        }
        if (!isset($params[static::REQUEST_KEY_ACTION]))
        {
            $params[static::REQUEST_KEY_ACTION] = static::GetAction('action');
        }
        return $params;
    }
    
    public static function GetLogoutURL()
    {
        return static::MakeURL(array(static::REQUEST_KEY_LOGOUT=>time()), static::GetBaseUrlAdmin());
    }
    
    public static function GetAdminActionURL($params=array())
    {
        return static::MakeURL(static::SanitizeActionParams($params), static::GetBaseUrlAdmin());
    }
    
    public static function GetAPIActionURL($params=array())
    {
        return static::MakeURL(static::SanitizeActionParams($params), static::GetBaseUrlAPI());
    }
    
    public static function GetIcebergAPIURL()
    {
        return static::GetBaseUrlAPI() . RoutingBackendAPI::REQUEST_ENVIRONMENT_ICEBERG_API . DIRECTORY_SEPARATOR;
    }
    
    public static function GetIcebergAPIActionURL($params=array())
    {
        return static::MakeURL(static::SanitizeActionParams($params), static::GetIcebergAPIURL());
    }
    
    public static function GetAdminAPIURL()
    {
        return static::GetBaseUrlAPI() . static::REQUEST_ENVIRONMENT_ADMIN_API . DIRECTORY_SEPARATOR;
    }
    
    public static function GetAdminAPIActionURL($params=array())
    {
        return static::MakeURL(static::SanitizeActionParams($params), static::GetAdminAPIURL());
    }
    
}