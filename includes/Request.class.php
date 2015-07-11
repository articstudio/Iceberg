<?php

/** Include helpers request file */
require_once ICEBERG_DIR_HELPERS . 'request.php';

/**
 * Request
 * 
 * Request management
 *  
 * @package Iceberg
 * @subpackage Routing
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 */
class Request extends ObjectConfig
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
    public static $CONFIG_KEY = 'request_config';

    /**
     * HTTP headers responses
     * <ul>
     * <li><b>100</b>: Continue</li>
     * <li><b>101</b>: Switching Protocols</li>
     * <li><b>200</b>: OK</li>
     * <li><b>201</b>: Created</li>
     * <li><b>202</b>: Accepted</li>
     * <li><b>203</b>: Non-Authoritative Information</li>
     * <li><b>204</b>: No Content</li>
     * <li><b>205</b>: Reset Content</li>
     * <li><b>206</b>: Partial Content</li>
     * <li><b>300</b>: Multiple Choices</li>
     * <li><b>301</b>: Moved Permanently</li>
     * <li><b>302</b>: Found</li>
     * <li><b>303</b>: See Other</li>
     * <li><b>304</b>: Not Modified</li>
     * <li><b>305</b>: Use Proxy</li>
     * <li><b>307</b>: Temporary Redirect</li>
     * <li><b>400</b>: Bad Request</li>
     * <li><b>401</b>: Unauthorized</li>
     * <li><b>402</b>: Payment Required</li>
     * <li><b>403</b>: Forbidden</li>
     * <li><b>404</b>: Not Found</li>
     * <li><b>405</b>: Method Not Allowed</li>
     * <li><b>406</b>: Not Acceptable</li>
     * <li><b>407</b>: Proxy Authentication Required</li>
     * <li><b>408</b>: Request Time-out</li>
     * <li><b>409</b>: Conflict</li>
     * <li><b>410</b>: Gone</li>
     * <li><b>411</b>: Length Required</li>
     * <li><b>412</b>: Precondition Failed</li>
     * <li><b>413</b>: Request Entity Too Large</li>
     * <li><b>414</b>: Request-URI Too Large</li>
     * <li><b>415</b>: Unsupported Media Type</li>
     * <li><b>416</b>: Requested range not satisfiable</li>
     * <li><b>417</b>: Expectation Failed</li>
     * <li><b>500</b>: Internal Server Error</li>
     * <li><b>501</b>: Not Implemented</li>
     * <li><b>502</b>: Bad Gateway</li>
     * <li><b>503</b>: Service Unavailable</li>
     * <li><b>504</b>: Gateway Time-out</li>
     * </ul>
     * 
     * @var array 
     */
    static public $HTTP_RESPONSES = array (
	   100 => "HTTP/1.1 100 Continue",
	   101 => "HTTP/1.1 101 Switching Protocols",
	   200 => "HTTP/1.1 200 OK",
	   201 => "HTTP/1.1 201 Created",
	   202 => "HTTP/1.1 202 Accepted",
	   203 => "HTTP/1.1 203 Non-Authoritative Information",
	   204 => "HTTP/1.1 204 No Content",
	   205 => "HTTP/1.1 205 Reset Content",
	   206 => "HTTP/1.1 206 Partial Content",
	   300 => "HTTP/1.1 300 Multiple Choices",
	   301 => "HTTP/1.1 301 Moved Permanently",
	   302 => "HTTP/1.1 302 Found",
	   303 => "HTTP/1.1 303 See Other",
	   304 => "HTTP/1.1 304 Not Modified",
	   305 => "HTTP/1.1 305 Use Proxy",
	   307 => "HTTP/1.1 307 Temporary Redirect",
	   400 => "HTTP/1.1 400 Bad Request",
	   401 => "HTTP/1.1 401 Unauthorized",
	   402 => "HTTP/1.1 402 Payment Required",
	   403 => "HTTP/1.1 403 Forbidden",
	   404 => "HTTP/1.1 404 Not Found",
	   405 => "HTTP/1.1 405 Method Not Allowed",
	   406 => "HTTP/1.1 406 Not Acceptable",
	   407 => "HTTP/1.1 407 Proxy Authentication Required",
	   408 => "HTTP/1.1 408 Request Time-out",
	   409 => "HTTP/1.1 409 Conflict",
	   410 => "HTTP/1.1 410 Gone",
	   411 => "HTTP/1.1 411 Length Required",
	   412 => "HTTP/1.1 412 Precondition Failed",
	   413 => "HTTP/1.1 413 Request Entity Too Large",
	   414 => "HTTP/1.1 414 Request-URI Too Large",
	   415 => "HTTP/1.1 415 Unsupported Media Type",
	   416 => "HTTP/1.1 416 Requested range not satisfiable",
	   417 => "HTTP/1.1 417 Expectation Failed",
	   500 => "HTTP/1.1 500 Internal Server Error",
	   501 => "HTTP/1.1 501 Not Implemented",
	   502 => "HTTP/1.1 502 Bad Gateway",
	   503 => "HTTP/1.1 503 Service Unavailable",
	   504 => "HTTP/1.1 504 Gateway Time-out"
   );

    /**
     * Constructor 
     */
    public function __construct()
    {
        
    }

    /**
     * Load Request variables
     */
    static public function LoadRequest()
    {
        if (!phpVersionCompatible('5.3.0'))
        {
            set_magic_quotes_runtime( 0 );
        }
        ini_set('magic_quotes_sybase', 0);
        if (get_magic_quotes_gpc())
        {
            $_GET    = self::StripSlashes($_GET);
            $_POST   = self::StripSlashes($_POST);
            $_COOKIE = self::StripSlashes($_COOKIE);
        }
        $_GET = self::AddMagicQuotes($_GET);
        $_POST = self::AddMagicQuotes($_POST);
        $_COOKIE = self::AddMagicQuotes($_COOKIE);
        $_SERVER = self::AddMagicQuotes($_SERVER);
        $_REQUEST = array_merge($_GET, $_POST);
        
        if (isset($_REQUEST['ICEBERG_AUTHOR']))
        {
            ob_clean();
            print '<h1>ICEBERG v' . ICEBERG_VERSION . '</h1>';
            print '<h2>By Artic studio (<a href="http://www.articstudio.com">www.articstudio.com</a>)</h2>';
            print '<h3>Developed by</h3>';
            print '<ul>';
            print '<li>Marc Mascort Bou (<a href="http://www.marcmascort.com">www.marcmascort.com</a>)</li>';
            print '</ul>';
            print '<h4>Designed by</h4>';
            print '<ul>';
            print '<li>Gerard Yanes Font (<a href="http://www.gerardyanes.com">www.gerardyanes.com</a>)</li>';
            print '</ul>';
            die();
        }
    }
    
    /*
    static public function LoadDinamicRedirect()
    {
        if (in_web() && isset($_SERVER['REDIRECT_URL'])) {
            $page = self::GetValueG(REQUEST_VAR_PAGE);
            if ($page !== false) {
                $page = Page::GetIdForPermalink($page);
                self::SetValueG(REQUEST_VAR_PAGE, $page);
            }
        }
    }
    */

    /**
     * Get the request protocol
     * 
     * @uses action_event() for 'request_get_protocol'
     * @return string Request protocol (http, https, ...)
     */
    static public function GetProtocol()
    {
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : '';
        $protocol = explode(DIRECTORY_SEPARATOR, $_SERVER['SERVER_PROTOCOL']);
        $protocol = strtolower($protocol[0]);
        $protocol = apply_filters('request_get_protocol', $protocol);
        return $protocol;
    }

    /**
     * Get the request URI
     * 
     * @uses action_event() for 'request_get_protocol'
     * @return string Request URI
     */
    static public function GetURI($relative=true)
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
        $script = substr($script, 0, strrpos($script, DIRECTORY_SEPARATOR));
        $uri = $relative ? str_replace($script, '', $uri) : $uri;
        $uri = substr($uri, 0, 1)==DIRECTORY_SEPARATOR ? substr($uri, 1) : $uri;
        $query = static::GetQuery(true);
        $uri = str_replace($query, '', $uri);
        $uri = apply_filters('request_get_uri', $uri);
        return $uri;
    }
    
    public static function GetQuery($withSeparator=false)
    {
        $query = (isset($_SERVER['QUERY_STRING']) && !empty ($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
        return (($withSeparator && !empty($query)) ? '?' : '') . $query;
    }

    /**
     * Get the domain
     * 
     * @uses action_event() for 'request_get_domain'
     * @return string Domain: $_SERVER['HTTP_HOST']
     */
    static public function GetDomain()
    {
        $domain = $_SERVER['HTTP_HOST'];
        $domain = apply_filters('request_get_domain', $domain);
        return $domain;
    }

    /**
     * Get the URL (With or without protocol)
     * 
     * @uses action_event() for 'request_get_url'
     * @param bool $withProtocol With or without protocol
     * @return string Request URL
     */
    static public function GetUrl($withProtocol=true)
    {
        $url = $withProtocol ? self::GetProtocol() . '://' : '';
        $url .= $_SERVER['HTTP_HOST'] . DIRECTORY_SEPARATOR . static::GetURI(false);
        $url = apply_filters('request_get_url', $url, $withProtocol);
        return $url;
    }
    
    static public function GetFullUrl()
    {
        $url = self::GetProtocol() . '://';
        $url .= $_SERVER['HTTP_HOST'] . DIRECTORY_SEPARATOR . static::GetURI(false);
        $url .= static::GetQuery(true);
        $url = apply_filters('request_get_full_url', $url);
        return $url;
    }

    /**
     * Get the base URL (With or without protocol)
     * 
     * @uses action_event() for 'request_get_baseurl'
     * @param bool $withProtocol With or without protocol
     * @return string Base URL
     */
    static public function GetBaseUrl($withProtocol=true)
    {
        $url = self::GetUrl($withProtocol);
        /*if (isset($_SERVER['REDIRECT_URL'])) {
            $url = str_replace($_SERVER['REDIRECT_URL'], '' , $url) . '/';
        }*/
        $uri = self::GetURI();
        $url = str_replace($uri, '', $url);
        $admin_uri = str_replace(ICEBERG_DIR, '', ICEBERG_DIR_ADMIN);
        $api_uri = str_replace(ICEBERG_DIR, '', ICEBERG_DIR_API);
        if ( substr($url, (-1 * strlen($admin_uri)) ) === $admin_uri ) {
            $url = substr( $url, 0, (-1 * strlen($admin_uri)) );
        }
        else if ( substr($url, (-1 * strlen($api_uri)) ) === $api_uri ) {
            $url = substr( $url, 0, (-1 * strlen($api_uri) ) );
        }
        $url = apply_filters('request_get_baseurl', $url, $withProtocol);
        return $url;
    }

    /**
     * Get the base URL ADMIN (With or without protocol)
     * 
     * @uses action_event() for 'request_get_baseurladmin'
     * @param bool $withProtocol With or without protocol
     * @return string Base URL of admin
     */
    static public function GetBaseUrlAdmin($withProtocol=true)
    {
        $url = self::GetBaseUrl($withProtocol). str_replace(ICEBERG_DIR, '', ICEBERG_DIR_ADMIN);
        $url = apply_filters('request_get_baseurladmin', $url, $withProtocol);
        return $url;
    }

    /**
     * Get the base URL API (With or without protocol)
     * 
     * @uses action_event() for 'request_get_baseurlapi'
     * @param bool $withProtocol With or without protocol
     * @return string Base URL of API
     */
    static public function GetBaseUrlAPI($withProtocol=true)
    {
        $url = Request::GetBaseUrl($withProtocol). str_replace(ICEBERG_DIR, '', ICEBERG_DIR_API);
        $url = apply_filters('request_get_baseurlapi', $url, $withProtocol);
        return $url;
    }

    /**
     * Get value of GET
     * 
     * @uses action_event() for 'request_get_value_g'
     * @param string $key
     * @param mixed $default
     * @param bool $stripSlashes
     * @return mixed 
     */
    static function GetValueG($key, $default=false, $stripSlashes=false)
    {
        $value = isset($_GET[$key]) ? $_GET[$key] : $default;
        $value = $stripSlashes ? self::StripSlashes($value) : $value;
        //list($value, $key, $default, $stripSlashes) = do_action('request_get_value_g', $value, $key, $default, $stripSlashes);
        return $value;
    }

    /**
     * Get value of POST
     * 
     * @uses action_event() for 'request_get_value_p'
     * @param string $key
     * @param mixed $default
     * @param bool $stripSlashes
     * @return mixed 
     */
    static function GetValueP($key, $default=false, $stripSlashes=false)
    {
        $value = isset($_POST[$key]) ? $_POST[$key] : $default;
        $value = $stripSlashes ? self::StripSlashes($value) : $value;
        //list($value, $key, $default, $stripSlashes) = do_action('request_get_value_p', $value, $key, $default, $stripSlashes);
        return $value;
    }

    /**
     * Get value of GET > POST
     * 
     * @uses action_event() for 'request_get_value_gp'
     * @param string $key
     * @param mixed $default
     * @param bool $stripSlashes
     * @return mixed 
     */
    static function GetValueGP($key, $default=false, $stripSlashes=false)
    {
        $value = self::GetValueG($key, $default, $stripSlashes);
        $value = self::GetValueP($key, $value, $stripSlashes);
        //list($value, $key, $default, $stripSlashes) = do_action('request_get_value_gp', $value, $key, $default, $stripSlashes);
        return $value;
    }

    /**
     * Get value of SESSION > GET > POST
     * 
     * @uses action_event() for 'request_get_value_sgp'
     * @param string $key
     * @param mixed $default
     * @param bool $stripSlashes
     * @return mixed 
     */
    static function GetValueSGP($key, $default=false, $stripSlashes=false)
    {
        $value = Session::GetValue($key, $default);
        $value = self::GetValueG($key, $value, $stripSlashes);
        $value = self::GetValueP($key, $value, $stripSlashes);
        //list($value, $key, $default, $stripSlashes) = do_action('request_get_value_sgp', $value, $key, $default, $stripSlashes);
        return $value;
    }

    /**
     * Check is set key in SESSION
     * 
     * @uses action_event() for 'request_isset_s'
     * @param string $key
     * @return bool 
     */
    static function IssetKeyS($key)
    {
        return Session::IssetKey($key);
    }

    /**
     * Check is set key in GET
     * 
     * @uses action_event() for 'request_isset_g'
     * @param string $key
     * @return bool 
     */
    static function IssetKeyG($key)
    {
        $isset = isset($_GET[$key]) ? true : false;
        //list($isset, $key) = do_action('request_isset_g', $isset, $key);
        return $isset;
    }

    /**
     * Check is set key in POST
     * 
     * @uses action_event() for 'request_isset_gp'
     * @param string $key
     * @return bool 
     */
    static function IssetKeyP($key)
    {
        $isset = isset($_POST[$key]) ? true : false;
        //list($isset, $key) = do_action('request_isset_p', $isset, $key);
        return $isset;
    }

    /**
     * Check is set key in GET > POST
     * 
     * @uses action_event() for 'request_isset_gp'
     * @param string $key
     * @return bool 
     */
    static function IssetKeyGP($key)
    {
        $isset = (self::IssetKeyG($key) || self::IssetKeyP($key)) ? true : false;
        //list($isset, $key) = do_action('request_isset_gp', $isset, $key);
        return $isset;
    }

    /**
     * Check is set key in SESSION > GET > POST
     * 
     * @uses action_event() for 'request_isset_sgp'
     * @param string $key
     * @return bool 
     */
    static function IssetKeySGP($key)
    {
        $isset = (Session::IssetKey($key) || self::IssetKeyG($key) || self::IssetKeyP($key)) ? true : false;
        //list($isset, $key) = do_action('request_isset_sgp', $isset, $key);
        return $isset;
    }
    
    /**
     * Set GET value for a key
     * 
     * @param string $key
     * @param mixed $value
     * @return boolean 
     */
    static function SetValueG($key, $value=false)
    {
        if ($value === false) {
            $_GET[$key] = null;
            unset($_GET[$key]);
            return true;
        }
        return $_GET[$key] = $value;
    }
    
    /**
     * Set POST value for a key
     * 
     * @param string $key
     * @param mixed $value
     * @return boolean 
     */
    static function SetValueP($key, $value=false)
    {
        if ($value === false) {
            $_POST[$key] = null;
            unset($_POST[$key]);
            return true;
        }
        return $_POST[$key] = $value;
    }
    
    /**
     * Get value of Cookie
     * @param String $name
     * @param String $default
     * @return mixed
     */
    static function GetCookie($name, $default=false)
    {
        if (isset($_COOKIE) && isset($_COOKIE[$name]))
        {
            return $_COOKIE[$name];
        }
        return $default;
    }
    
    /**
     * Check if is set key in Cookies
     * @param String $name
     * @return Boolean
     */
    static function IssetCookie($name)
    {
        return (isset($_COOKIE) && isset($_COOKIE[$name]));
    }
    
    /**
     * Set a Cookie value for a key
     * @param String $name
     * @param mixed $value
     * @param Int $duration
     * @return Boolean
     */
    static function SetCookie($name, $value, $duration=86400)
    {
        return setcookie($name, $value, time()+$duration);
    }
    
    /**
     * Delete cookie
     * @param String $name
     * @return Boolean
     */
    static function DeleteCookie($name)
    {
        return setcookie($name, '', time()-3600);
    }

    /**
     * Strip slashes of values
     * 
     * @uses action_event() for 'request_stripslashes'
     * @param mixed $var
     * @return mixed 
     */
    static public function StripSlashes($var)
    {
        if ( is_array($var) ) {
            foreach ($var AS $key=>$value) { $var[$key]=Request::StripSlashes( $value ); }
        }
        else if ( is_object($var) ) {
            $ovars = get_object_vars( $var );
            foreach ($ovars as $key=>$value) { $value->{$key} = Request::StripSlashes( $value ); }
        }
        else if (!is_null($var) && !is_bool($var)) {
            $var = stripslashes( $var );
            $var = stripcslashes( $var );
        }
        //list($var) = do_action('request_stripslashes', $var);
        return $var;
    }

    /**
     * ADD magic quotes of values
     * 
     * @uses action_event() for 'request_addmagicquotes'
     * @param mixed $var
     * @return mixed 
     */
    static public function AddMagicQuotes($var)
    {
        if ( is_array($var) ) {
            foreach ($var AS $key=>$value) { $var[$key]=Request::AddMagicQuotes( $value ); }
        }
        else if ( is_object($var) ) {
            $ovars = get_object_vars( $var );
            foreach ($ovars as $key=>$value) { $value->{$key} = Request::AddMagicQuotes( $value ); }
        }
        else {
            $var = addslashes( $var );
        }
        //list($var) = do_action('request_addmagicquotes', $var);
        return $var;
    }

    /**
     * Locate request
     * 
     * @param string $url New URL
     * @param int $code Header response code
     */
    static public function Locate($url, $code=100)
    {
        $response = isset(self::$HTTP_RESPONSES[$code]) ? self::$HTTP_RESPONSES[$code] : null;
        if (!is_null($response))
        {
            header($response);
        }
        header('Location: ' . $url);
        exit();
    }

    /**
     * Response request
     * 
     * @param int $code Header response code
     */
    static public function Response($code=400)
    {
        $response = isset(self::$HTTP_RESPONSES[$code]) ? self::$HTTP_RESPONSES[$code] : null;
        if (!is_null($response))
        {
            header($response);
        }
        //exit();
    }
}
