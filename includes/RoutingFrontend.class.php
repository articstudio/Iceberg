<?php

/** Include helpers routing file */
require_once ICEBERG_DIR_HELPERS . 'routing-frontend.php';

class RoutingFrontend extends Routing
{
    const REQUEST_KEY_LANGUAGE = 'lang';
    const REQUEST_KEY_PAGE = 'page';
    const REQUEST_KEY_FILTER = 'id';
    const REQUEST_KEY_PARENT = 'parent';
    
    protected $request = array(
        'page' => null,
        'lang' => null,
        'id' => null,
        'parent' => null,
    );
    
    public function ParseRequest()
    {
        $this->request[static::REQUEST_KEY_LANGUAGE] = Request::GetValueSGP(static::REQUEST_KEY_LANGUAGE, ICEBERG_DEFAULT_LANGUAGE, true);
        $this->request[static::REQUEST_KEY_PAGE] = Request::GetValueGP(static::REQUEST_KEY_PAGE, null, true);
        $this->request[static::REQUEST_KEY_FILTER] = Request::GetValueGP(static::REQUEST_KEY_FILTER, null, true);
        $this->request[static::REQUEST_KEY_PARENT] = Request::GetValueGP(static::REQUEST_KEY_PARENT, null, true);
        Session::SetValue(static::REQUEST_KEY_LANGUAGE, $this->request[static::REQUEST_KEY_LANGUAGE]);
        $this->SetLanguage($this->request[static::REQUEST_KEY_LANGUAGE]);
        
    }
    
    
    public static function GetBreadcrumb($id=false)
    {
        $breadcrumb = array();
        $filter = $id===false ? static::GetRequestFilter() : null;
        $parent = $id===false ? static::GetRequestParent() : null;
        $page_id = $id===false ? static::GetRequestPage() : $id;
        $page = get_page($page_id);
        if ($page->id != -1)
        {
            if (!is_null($filter))
            {
                $breadcrumb = self::GetBreadcrumb($filter);
                if (!is_null($parent))
                {
                    $page_parent = get_page($parent);
                    if ($page_parent->id != -1)
                    {
                        array_push($breadcrumb, $page_parent);
                    }
                }
            }
            else if (!is_null($page->parent))
            {
                $breadcrumb = self::GetBreadcrumb($page->parent);
            }
            array_push($breadcrumb, $page);
        }
        list($breadcrumb, $page_id) = action_event('get_breadcrumb', $breadcrumb, $page_id);
        return $breadcrumb;
    }
    
    
    
    public static function GetRequestParent()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_PARENT);
    }
    
    public static function GetRequestFilter()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_FILTER);
    }
    
    public static function GetRequestPage()
    {
        $routing = static::GetRouting(); 
        return $routing->GetParsedRequestValue(static::REQUEST_KEY_PAGE);
    }
    
}
