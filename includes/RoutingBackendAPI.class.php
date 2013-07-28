<?php

/** Include helpers routing file */
require_once ICEBERG_DIR_HELPERS . 'routing-backend-api.php';

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
    const REQUEST_KEY_LOGOUT = 'logout';
    
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
        'name' => 'DASHBOARD'
    );
    
    public static $MODULE_ERROR = array(
        'module' => 'error',
        'mode' => 'error',
        'template' => 'error.php',
        'name' => 'ERROR'
    );
    
    protected static $MODULES = array(
        'structure' => array(
            'template' => 'structure.php',
            'name' => 'STRUCTURE',
            'level' => 500
        ),
        'content' => array(
            'template' => 'content.php',
            'name' => 'CONTENT',
            'level' => 500
        ),
        'media' => array(
            'template' => 'media.php',
            'name' => 'MEDIA',
            'level' => 500
        ),
        'extensions' => array(
            'template' => 'extensions.php',
            'name' => 'EXTENSIONS',
            'level' => 500
        ),
        'configuration' => array(
            'template' => 'configuration.php',
            'name' => 'CONFIGURATION',
            'level' => 500
        ),
    );
    
    public function ParseRequest()
    {
        $this->request[static::REQUEST_KEY_LANGUAGE] = Request::GetValueSGP(static::REQUEST_KEY_LANGUAGE, ICEBERG_DEFAULT_LANGUAGE, true);
        $this->request[static::REQUEST_KEY_MODULE] = Request::GetValueSGP(static::REQUEST_KEY_MODULE, static::$MODULE_DASHBOARD['module'], true);
        $this->request[static::REQUEST_KEY_MODE] = Request::GetValueSGP(static::REQUEST_KEY_MODE, null, true);
        $this->request[static::REQUEST_KEY_ACTION] = Request::GetValueSGP(static::REQUEST_KEY_ACTION, null, true);
        $this->request[static::REQUEST_KEY_ID] = Request::GetValueSGP(static::REQUEST_KEY_ID, null, true);
        $this->request[static::REQUEST_KEY_GROUP] = Request::GetValueSGP(static::REQUEST_KEY_GROUP, null, true);
        $this->request[static::REQUEST_KEY_PAGE] = Request::GetValueSGP(static::REQUEST_KEY_PAGE, null, true);
        $this->request[static::REQUEST_KEY_TAXONOMY] = Request::GetValueSGP(static::REQUEST_KEY_TAXONOMY, null, true);
        /* @todo Only correct values */
        $this->request[static::REQUEST_KEY_USERNAME] = Request::GetValueGP(static::REQUEST_KEY_USERNAME, null, true);
        $this->request[static::REQUEST_KEY_PASSWORD] = Request::GetValueGP(static::REQUEST_KEY_PASSWORD, null, true);
        $this->request[static::REQUEST_KEY_LOGOUT] = (bool)Request::GetValueGP(static::REQUEST_KEY_LOGOUT, false, true);
        Session::SetValue(static::REQUEST_KEY_LANGUAGE, $this->request[static::REQUEST_KEY_LANGUAGE]);
        $this->SetLanguage($this->request[static::REQUEST_KEY_LANGUAGE]);
        $this->SetLogin($this->request[static::REQUEST_KEY_USERNAME], $this->request[static::REQUEST_KEY_PASSWORD]);
    }
    
    public function GetParsedRequest()
    {
        return $this->request;
    }
    
    public function GetParsedRequestValue($key)
    {
        $request = $this->GetParsedRequest();
        return array_key_exists($key, $request) ? $request[$key] : false;
    }
    
    
    
    
    
    
    public static function GetRequestLogout()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_LOGOUT);
    }
    
    public static function GetModules()
    {
        $arr = static::$MODULES;
        list($arr) = action_event('get_modules', $arr);
        return $arr;
    }
    
    public static function GetRequestModule()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_MODULE);
    }
    
    public static function GetModule($key=null)
    {
        $module = static::GetRequestModule(); 
        $modules = static::GetModules(); 
        $data = isset($modules[$module]) ? $modules[$module] : array();
        if ($module==static::$MODULE_DASHBOARD['module']) {
            $module = static::$MODULE_DASHBOARD['module'];
            $data = static::$MODULE_DASHBOARD;
        }
        if (empty($data)) {
            $module = static::$MODULE_ERROR['module'];
            $data = static::$MODULE_ERROR;
        }
        $data['module'] = $module;
        $data['name'] = (!isset($data['name']) || !is_string($data['name']) || empty($data['name'])) ? static::$MODULE_ERROR['name'] : $data['name'];
        $data['template'] = (!isset($data['template']) || !is_string($data['template']) || empty($data['template'])) ? static::$MODULE_ERROR['template'] : $data['template'];
        $data['level'] = (!isset($data['level']) || !is_int($data['level'])) ? Session::GetAdminLevel() : $data['level'];
        list($data, $key) = action_event('get_module', $data, $key);
        if (!is_null($key)) {
            return isset($data[$key]) ? $data[$key] : null;
        }
        return $data;
    }
    
    public static function GetModes()
    {
        $arr = array();
        $module = static::GetModule('module');
        list($arr) = action_event('get_modes', $arr);
        foreach ($arr AS $k => $mode)
        {
            $arr[$k]['mode'] = $k;
            $arr[$k]['module'] = $module;
        }
        return $arr;
    }
    
    public static function GetRequestMode()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_MODE);
    }
    
    public static function GetMode($key=null)
    {
        $mode = static::GetRequestMode(); 
        $modes = static::GetModes();
        if (!$mode)
        {
            $keys = array_keys($modes);
            $mode = !empty($keys) ? $keys[0] : false;
        }
        $data = isset($modes[$mode]) ? $modes[$mode] : array();
        if (empty($data)) {
            $mode = static::$MODULE_ERROR['mode'];
            $data = static::$MODULE_ERROR;
        }
        $data['mode'] = $mode;
        $data['name'] = (!isset($data['name']) || !is_string($data['name']) || empty($data['name'])) ? static::$MODULE_ERROR['name'] : $data['name'];
        $data['template'] = (!isset($data['template']) || !is_string($data['template']) || empty($data['template'])) ? static::$MODULE_ERROR['template'] : $data['template'];
        $data['level'] = (!isset($data['level']) || !is_int($data['level'])) ? Session::GetAdminLevel() : $data['level'];
        list($data, $key) = action_event('get_mode', $data, $key);
        if (!is_null($key)) {
            return isset($data[$key]) ? $data[$key] : null;
        }
        return $data;
    }
    
    public static function GetRequestAction()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_ACTION);
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
    
    public static function GetAdminAPIURL()
    {
        return get_base_url_api() . static::REQUEST_ENVIRONMENT_ADMIN_API . DIRECTORY_SEPARATOR;
    }
    
}