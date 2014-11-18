<?php

class RoutingAPI extends RoutingBackendAPI
{
    
    public static $DEFAULT_APIS = array(
        'permalinks' => 'permalinks.php'
    );
    
    public function ParseRequest()
    {
        parent::ParseRequest();
        $uri = Request::GetURI();
        $uri = (substr($uri, -1) == DIRECTORY_SEPARATOR) ? substr($uri, 0, -1) : $uri;
        $uri = empty($uri) ? static::REQUEST_ENVIRONMENT_API : $uri;
        $this->request[static::REQUEST_KEY_ENVIRONMENT] = Request::GetValueGP(static::REQUEST_KEY_ENVIRONMENT, $uri, true);
    }
    
    
    
    public static function GetRequestEnvironment()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_ENVIRONMENT);
    }
    
    public static function InEnvironment($environment)
    {
        return static::GetRequestEnvironment() === $environment;
    }
}
