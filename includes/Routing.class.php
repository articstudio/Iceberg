<?php

/** Include helpers routing file */
require_once ICEBERG_DIR_HELPERS . 'routing.php';

interface RoutingInterface
{
    public function ParseRequest();
    public function GetParsedRequest();
    public function GetParsedRequestValue($key);
}

/**
 * Routing
 * 
 * Routing management
 *  
 * @package Iceberg
 * @subpackage Routing
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 */

abstract class Routing extends Request
{
    
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'routing_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'canonical' => 0,
        'type' => 1,
        'domains_by_language' => array(),
        'domains' => array()
    );
    
    /**
     * Language
     * @var string 
     */
    protected $language = null;
    
    /**
     * User
     * @var string 
     */
    protected $user = null;
    
    /**
     * Password
     * @var string 
     */
    protected $password = null;
    
    protected $request = array();
    
    
    public function GetLanguage()
    {
        return $this->language;
    }
    
    public function SetLanguage($language)
    {
        $lang = I18N::GetLanguage();
        if ($lang === $language)
        {
            return $this->language = $language;
        }
        if (I18N::LoadLanguage($language, true))
        {
            return $this->language = $language;
        }
        return false;
    }
    
    
    public function GetLogin()
    {
        return array($this->user, $this->password);
    }
    
    public function SetLogin($user, $password)
    {
        return (($this->user = $user) && ($this->password = $password));
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
    
    public function SetParsedRequestValue($key, $value)
    {
        return ($this->request[$key] = $value);
    }
    
    public function GenerateURL($params=array(), $baseurl=null)
    {
        return static::MakeURL($params, $baseurl);
    }
    
    
    
    public static function Save($config)
    {
        return static::SaveConfig($config);
    }
    
    public static function GetRouting()
    {
        return Environment::GetEnvironmentRouting();
    }
    
    public static function GetCanonicals()
    {
        $arr = array();
        list($arr) = action_event('routing_get_canonicals', $arr);
        return $arr;
    }
    
    public static function GetTypes()
    {
        $arr = array();
        list($arr) = action_event('routing_get_types', $arr);
        return $arr;
    }
    
    /**
     * Make a URL
     * 
     * @uses action_event() for 'request_makeurl'
     * @param string $url
     * @param array $params
     * @return string 
     */
    static public function MakeURL($params=array(), $baseurl=null)
    {
        if (is_null($baseurl))
        {
            $baseurl = get_base_url();
            $baseurl = in_admin() ? get_base_url_admin() : $baseurl;
            $baseurl = in_api() ? get_base_url_api() : $baseurl;
        }
        $hash = '';
        if (isset($params['#'])) {
            $hash = $params['#'];
            $params['#'] = null;
            unset($params['#']);
        }
        $query = http_build_query($params);
        $url = $baseurl . (empty($query) ? '' : '?' . $query);
        if (!empty($hash))
        {
            $url .= '#' . $hash;
        }
        list($url, $baseurl, $params) = action_event('routing_make_url', $url, $baseurl, $params);
        return $url;
    }
    
    
    /**
     * Parse URL
     * @global array $__LANGUAGES
     * @global string $__DOMAIN_CANONICAL
     * @param boolean $force 
     */
    /*
    static public function ParseURL($force=false)
    {
        global $__LANGUAGES, $__DOMAIN_CANONICAL;
        //var_dump($_SERVER);
        if (in_web() || $force)
        {
            $lang = get_language_default();
            $domain = get_base_url(false);
            $found = false;
            foreach ($__LANGUAGES AS $k => $l) {
                //var_dump($l);
                if (isset($l['urltype']) && $l['urltype']==3 && ($domain==$l['domain']) || in_array($domain, $l['alias']))
                {
                    $lang = $k;
                    $found = true;
                    break;
                }
            }
            if (isset($_SERVER['REDIRECT_URL']))
            {
                $uri = self::GetURI();
                $query = (isset($_SERVER['QUERY_STRING']) &&!empty ($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
                $uri = str_replace($query, '', $uri);
                $uri = explode('/',$uri);
                if (substr($uri[0], -5)=='.html') // DOMAIN.COM/PAGE.HTML
                {
                    self::SetValueG(REQUEST_VAR_LANGUAGE, $lang);
                    $page = str_replace('.html', '', $uri[0]);
                    self::SetValueG(REQUEST_VAR_PAGE, $page);
                } else { // DOMAIN.COM/LANG/PAGE.HTML
                    $found = false;
                    foreach($__LANGUAGES AS $key => $value) {
                        if ($value['iso']==$uri[0]) {
                            $lang = $key;
                            $found = true;
                            break;
                        }
                    }
                    if ($found) {
                        self::SetValueG(REQUEST_VAR_LANGUAGE, $lang);
                    }
                    if (isset($uri[1]) && !empty($uri[1])) {
                        $page = str_replace('.html', '', $uri[1]);
                        self::SetValueG(REQUEST_VAR_PAGE, $page);
                    }
                }
            } else {
                if ($found) {
                    self::SetValueG(REQUEST_VAR_LANGUAGE, $lang);
                }
            }
        }
    }
*/
    /*
    static public function MakeURL($params=array())
    {
        global $__LANGUAGES;
        $b_params = $params;
        $hash = '';
        $url = $baseurl;
        $type_url = isset($params['urltype']) ? $params['urltype'] : 1;
        if (in_web() && !defined('ICEBERG_INSTALL') && !isset($params['urltype'])) {
            if (isset($params[REQUEST_VAR_LANGUAGE]) && isset($__LANGUAGES[$params[REQUEST_VAR_LANGUAGE]])) {
                $blang = $__LANGUAGES[$params[REQUEST_VAR_LANGUAGE]];
                $type_url = isset($blang['urltype']) ? $blang['urltype'] : '1';
            }
        }
        
        //domain.com/?lang=LANGUAGE&page=PAGE
        if ($type_url==1) {
            $query_string = array();
            foreach( $params AS $key => $value ) {
                if ($key == '#') {
                $hash = '#' . $value; 
                }
                else {
                    $value = (!is_string($value) && !is_numeric($value)) ? serialize($value) : $value;
                    $value = urlencode( $value );
                    $var = $key . '=' . $value;
                    array_push($query_string, $var);
                }
            }
            $url .= (!empty($params) ? '?' . implode('&', $query_string) : '') . $hash;
        }
        //domain.com/LANGUAGE/PAGE.html  
        else if ($type_url==2) {
            if (isset($params[REQUEST_VAR_LANGUAGE]) && isset($__LANGUAGES[$params[REQUEST_VAR_LANGUAGE]])) {
                $blang = $__LANGUAGES[$params[REQUEST_VAR_LANGUAGE]];
                $url .= $blang['iso'] . '/';
            }
            else {
                $blang = get_lang();
                $blang = $__LANGUAGES[$blang];
                $url .= $blang['iso'] . '/';
            }
            unset($params[REQUEST_VAR_LANGUAGE]);
            if (isset($params[REQUEST_VAR_PAGE])) {
                $page_permalink = $params[REQUEST_VAR_PAGE];
                $page = get_page($page_permalink);
                if ($page->_id!==-1) {
                    $page_permalink = $page->GetPermalink($blang['locale']);
                    $url .= $page_permalink . '.html';
                }
                unset($params[REQUEST_VAR_PAGE]);
            }
            $query_string = array();
            foreach( $params AS $key => $value ) {
                if ($key !== '#') {
                    $value = (!is_string($value) && !is_numeric($value)) ? serialize($value) : $value;
                    $value = urlencode( $value );
                    $var = $key . '=' . $value;
                    array_push($query_string, $var);
                }
            }
            $url .= (!empty($query_string) ? '?' . implode('&', $query_string) : '');
            if (isset($params['#'])) {
                $url .= '#' . $params['#'];
                unset($params['#']);
            }
        }
        //domain.LANG/PAGE.html
        else if ($type_url==3) {
            $blang = get_lang();
            $blang = $__LANGUAGES[$blang];
            if (isset($params[REQUEST_VAR_LANGUAGE]) && isset($__LANGUAGES[$params[REQUEST_VAR_LANGUAGE]]))
            {
                $blang = $__LANGUAGES[$params[REQUEST_VAR_LANGUAGE]];
            }
            if (isset($blang['domain']) && !empty($blang['domain']))
            {
                $url = Request::GetProtocol() . '://' . $blang['domain'];
                unset($params[REQUEST_VAR_LANGUAGE]);
                if (isset($params[REQUEST_VAR_PAGE])) {
                    $page_permalink = $params[REQUEST_VAR_PAGE];
                    $page = get_page($page_permalink);
                    if ($page->_id!==-1) {
                        $page_permalink = $page->GetPermalink($blang['locale']);
                        $url .= $page_permalink . '.html';
                    }
                    unset($params[REQUEST_VAR_PAGE]);
                }
                $query_string = array();
                foreach( $params AS $key => $value ) {
                    if ($key !== '#') {
                        $value = (!is_string($value) && !is_numeric($value)) ? serialize($value) : $value;
                        $value = urlencode( $value );
                        $var = $key . '=' . $value;
                        array_push($query_string, $var);
                    }
                }
                $url .= (!empty($query_string) ? '?' . implode('&', $query_string) : '');
                if (isset($params['#'])) {
                    $url .= '#' . $params['#'];
                    unset($params['#']);
                }
            } else {
                $params['urltype'] = 1;
                $url = self::MakeURL($baseurl, $params);
            }
        }
        
        list($url, $baseurl, $params) = action_event('request_makeurl', $url, $baseurl, $b_params);
        return $url;
    }
*/
    /**
     * Make a URL to directory
     * 
     * @uses action_event() for 'request_makeurldir'
     * @param string $dir
     * @param array $params
     * @return string 
     */
    static public function MakeURLDir($dir, $params=array())
    {
        $url = self::GetBaseUrl() . str_replace(ICEBERG_DIR, '', $dir);
        $url = self::MakeURL($url, $params);
        list($url) = action_event('request_makeurldir', $url, $dir, $params);
        return $url;
    }
    
    
}

/* CANONICAL REDIRECT */
        /*if (in_web())
        {
            db_select(ICEBERG_DB_DOMAINS, 'name', "WHERE id='" . mysql_escape( $__DOMAIN_ID ) . "'");
            if (db_numrows()>0)
            {
                $row = db_next();
                $canonical_domain = $row->name;
                if ($domain != $canonical_domain)
                {
                    $url = Request::GetProtocol() . '://' . $canonical_domain . Request::GetURI();
                    Request::Locate($url, 301);
                }
            }
            else
            {
                throw new Exception( 'CMS DOMAIN INITIALIZATION ERROR: No configuration of canonical domain.' );
            }
        }*/


