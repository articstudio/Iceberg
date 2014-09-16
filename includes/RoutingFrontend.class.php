<?php

/** Include helpers routing file */
require_once ICEBERG_DIR_HELPERS . 'routing-frontend.php';

add_action('routing_get_canonicals', 'routing_get_canonicals_fontend', 10, 1);
add_action('routing_get_types', 'routing_get_types_frontend', 10, 1);
add_action('routingfrontend_make_url_canonical_' . RoutingFrontend::CANONICAL_NOT_FORCE, 'routingfrontend_make_url_canonical_not_force', 10, 3);
add_action('routingfrontend_make_url_canonical_' . RoutingFrontend::CANONICAL_FORCE, 'routingfrontend_make_url_canonical_force', 10, 3);
add_action('routingfrontend_make_url_canonical_' . RoutingFrontend::CANONICAL_FORCE_BY_LANGUAGE, 'routingfrontend_make_url_canonical_force_by_language', 10, 3);
add_action('routingfrontend_make_url_type_' . RoutingFrontend::TYPE_BASIC, 'routingfrontend_make_url_type_basic', 10, 3);
add_action('routingfrontend_make_url_type_' . RoutingFrontend::TYPE_PERMALINK, 'routingfrontend_make_url_type_permalink', 10, 3);
add_action('routingfrontend_make_url_type_' . RoutingFrontend::TYPE_PERMALINK_HTML_EXT, 'routingfrontend_make_url_type_permalink_html_ext', 10, 3);
add_action('routingfrontend_parserequest_type_' . RoutingFrontend::TYPE_BASIC, 'routingfrontend_parserequest_type_basic', 10, 0);
add_action('routingfrontend_parserequest_type_' . RoutingFrontend::TYPE_PERMALINK, 'routingfrontend_parserequest_type_permalink', 10, 0);
add_action('routingfrontend_parserequest_type_' . RoutingFrontend::TYPE_PERMALINK_HTML_EXT, 'routingfrontend_parserequest_type_permalink_html_ext', 10, 0);

class RoutingFrontend extends Routing
{
    const CANONICAL_NOT_FORCE = 0;
    const CANONICAL_FORCE = 1;
    const CANONICAL_FORCE_BY_LANGUAGE = 2;
    
    const TYPE_BASIC = 1;
    const TYPE_PERMALINK = 2;
    const TYPE_PERMALINK_HTML_EXT = 3;
    
    
    const REQUEST_KEY_LANGUAGE = 'lang';
    const REQUEST_KEY_PAGE = 'page';
    const REQUEST_KEY_FILTER = 'id';
    const REQUEST_KEY_PARENT = 'parent';
    const REQUEST_KEY_HASH = '#';
    const REQUEST_KEY_PERMALINK = 'permalink';
    
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
        $this->request[static::REQUEST_KEY_PERMALINK] = null;
        Session::SetValue(static::REQUEST_KEY_LANGUAGE, $this->request[static::REQUEST_KEY_LANGUAGE]);
        $this->SetLanguage($this->request[static::REQUEST_KEY_LANGUAGE]);
        $r_type = get_routing_type();
        action_event('routingfrontend_parserequest_type_' . $r_type);
    }
    
    
    public static function GetBreadcrumb($id=false)
    {
        $breadcrumb = array();
        $filter = $id===false ? static::GetRequestFilter() : null; //var_dump($filter);
        $parent = $id===false ? static::GetRequestParent() : null; //var_dump($parent);
        $page_id = $id===false ? static::GetRequestPage() : $id; //var_dump($page_id);
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
    
    
    public static function GetFrontendCanonicals($args=array())
    {
        list($arr) = $args;
        $arr[static::CANONICAL_NOT_FORCE] = 'Not force';
        $arr[static::CANONICAL_FORCE] = 'Force canonical domain';
        $arr[static::CANONICAL_FORCE_BY_LANGUAGE] = 'Force canonical domain by language';
        return array($arr);
    }
    
    public static function GetFrontendTypes($args=array())
    {
        list($arr) = $args;
        $arr[static::TYPE_BASIC] = array(
            'name' => 'Basic routing',
            'example' => 'http://www.example.com/?lang=en_US&page=10'
        );
        $arr[static::TYPE_PERMALINK] = array(
            'name' => 'Permalink routing 1',
                'example' => 'http://www.example.com/en/hello-world'
        );
        $arr[static::TYPE_PERMALINK_HTML_EXT] = array(
            'name' => 'Permalink routing 2',
                'example' => 'http://www.example.com/en/hello-world.html'
        );
        return array($arr);
    }
    
    public static function MakeURL($params=array(), $baseurl=null)
    {
        $canonical_url = Domain::GetCanonical();
        $baseurl = is_null($baseurl) ? $canonical_url : $baseurl;
        $url = parent::MakeURL($params, $baseurl);
        $canonical_childs = Domain::GetDomainsByParent(Domain::GetID());
        $childs_url = array();
        foreach ($canonical_childs AS $canonical_child_id => $canonical_child)
        {
            $childs_url[$canonical_child_id] = Request::GetProtocol() . '://' . $canonical_child->name;
        }
        if ($baseurl == $canonical_url || in_array($baseurl, $childs_url))
        {
            $r_canonical = get_routing_canonical();
            list($url, $baseurl, $params) = action_event('routingfrontend_make_url_canonical_' . $r_canonical, $url, $baseurl, $params);
            $r_type = get_routing_type();
            list($url, $baseurl, $params) = action_event('routingfrontend_make_url_type_' . $r_type, $url, $baseurl, $params);
        }
        list($url, $baseurl, $params) = action_event('routingfrontend_make_url', $url, $baseurl, $params);
        return $url;
    }
    
    public static function MakeURLCanonicalForce($args=array())
    {
        list($url, $baseurl, $params) = $args;
        $default_language = I18N::GetDefaultLanguage();
        if (isset($params[static::REQUEST_KEY_LANGUAGE]) && $params[static::REQUEST_KEY_LANGUAGE] === $default_language)
        {
            $params[static::REQUEST_KEY_LANGUAGE] = null;
            unset($params[static::REQUEST_KEY_LANGUAGE]);
        }
        $baseurl = Domain::GetCanonical();
        $url = parent::MakeURL($params, $baseurl);
        return array($url, $baseurl, $params);
    }
    
    public static function MakeURLCanonicalForceByLanguage($args=array())
    {
        list($url, $baseurl, $params) = static::MakeURLCanonicalForce($args);
        $default_language = I18N::GetDefaultLanguage();
        $locale = isset($params[static::REQUEST_KEY_LANGUAGE]) ? $params[static::REQUEST_KEY_LANGUAGE] : $default_language;
        if ($locale !== $default_language)
        {
            $canonical_childs = Domain::GetDomainsByParent(Domain::GetID());
            $domains_by_language = get_routing_domains_by_language();
            $language_domains = isset($domains_by_language[$locale]) ? $domains_by_language[$locale] : array();
            
            foreach ($language_domains AS $language_domain_id)
            {
                if (isset($canonical_childs[$language_domain_id]))
                {
                    $language_domain = $canonical_childs[$language_domain_id];
                    $baseurl = Request::GetProtocol() . '://' . $language_domain->name;
                    $url = parent::MakeURL($params, $baseurl);
                    break;
                }
            }
        }
        return array($url, $baseurl, $params);
    }
    
    public static function MakeURLTypeBasic($args=array())
    {
        list($url, $baseurl, $params) = $args;
        $baseurl = is_null($baseurl) ? Domain::GetCanonical() : $baseurl;
        $url = parent::MakeURL($params, $baseurl);
        return array($url, $baseurl, $params);
    }
    
    public static function MakeURLTypePermalink($args=array())
    {
        list($url, $baseurl, $params) = static::MakeURLTypeBasic($args); 
        $domain_name = explode('://', $baseurl);
        $domain_name = isset($domain_name[1]) ? $domain_name[1] : $domain_name[0];
        $domain = Domain::GetDomainByName($domain_name);
        $default_language = I18N::GetDefaultLanguage();
        $locale = isset($params[static::REQUEST_KEY_LANGUAGE]) ? $params[static::REQUEST_KEY_LANGUAGE] : $default_language;
        //$canonical_childs = Domain::GetDomainsByParent(Domain::GetID());
        $domains_by_language = get_routing_domains_by_language();
        $language_domains = isset($domains_by_language[$locale]) ? $domains_by_language[$locale] : array();
        if (in_array($domain->id, $language_domains) || $domain->id == Domain::GetID())
        {
            if (isset($params[static::REQUEST_KEY_LANGUAGE]) && $params[static::REQUEST_KEY_LANGUAGE] === $default_language)
            {
                $params[static::REQUEST_KEY_LANGUAGE] = null;
                unset($params[static::REQUEST_KEY_LANGUAGE]);
            }
        }
        
        $url = $baseurl;
        $languages = I18N::GetActiveLanguages();
        if (isset($params[static::REQUEST_KEY_LANGUAGE]) && isset($languages[$params[static::REQUEST_KEY_LANGUAGE]]))
        {
            $blang = $languages[$params[static::REQUEST_KEY_LANGUAGE]];
            $url .= $blang['iso'] . DIRECTORY_SEPARATOR;
            $params[static::REQUEST_KEY_LANGUAGE] = null;
            unset($params[static::REQUEST_KEY_LANGUAGE]);
        }
        if (isset($params[static::REQUEST_KEY_PAGE]))
        {
            $page_permalink = array();
            $buffer_page_parent = null;
            $page_id = $params[static::REQUEST_KEY_PAGE];
            $page = Page::GetPage($page_id, $locale);
            if ($page->id !== -1)
            {
                $page_permalink = array(
                    $page->GetPermalink($locale)
                );
                $buffer_page_parent =  $page->parent;
            }
            if (isset($params[static::REQUEST_KEY_PARENT]))
            {
                $parent_id = $params[static::REQUEST_KEY_PARENT];
                $parent = Page::GetPage($parent_id, $locale);
                if ($parent->id !== -1)
                {
                    array_unshift($page_permalink, $parent->GetPermalink($locale));
                    $buffer_page_parent = $parent->parent;
                }
            }
            if (isset($params[static::REQUEST_KEY_FILTER]))
            {
                $filter_id = $params[static::REQUEST_KEY_FILTER];
                $filter = Page::GetPage($filter_id, $locale);
                if ($filter->id !== -1)
                {
                    array_unshift($page_permalink, $filter->GetPermalink($locale));
                    $buffer_page_parent = $filter->parent;
                }
            }
            
            while (!is_null($buffer_page_parent))
            {
                $parent = Page::GetPage($buffer_page_parent, $locale);
                if ($parent->id !== -1)
                {
                    if ($parent->GetTaxonomy()->UsePermalink())
                    {
                        array_unshift($page_permalink, $parent->GetPermalink($locale));
                        $buffer_page_parent = $parent->parent;
                    }
                }
                else
                {
                    $buffer_page_parent = null;
                }
            }
            unset($params[static::REQUEST_KEY_PAGE]);
            unset($params[static::REQUEST_KEY_PARENT]);
            unset($params[static::REQUEST_KEY_FILTER]);
            $url .= empty($page_permalink) ? '' : implode(DIRECTORY_SEPARATOR, $page_permalink);
        }
        $hash = '';
        if (isset($params[static::REQUEST_KEY_HASH]))
        {
            $hash = $params[static::REQUEST_KEY_HASH];
            $params[static::REQUEST_KEY_HASH] = null;
            unset($params[static::REQUEST_KEY_HASH]);
        }
        $params = is_array($params) ? $params : array();
        $query = http_build_query($params);
        $url .= empty($query) ? '' : '?' . $query;
        $url .= empty($hash) ? '' : '#' . $hash;
        return array($url, $baseurl, $params);
    }
    
    public static function MakeURLTypePermalinkHTMLExt($args=array())
    {
        list($url, $baseurl, $params) = static::MakeURLTypePermalink($args);
        $url = explode('?', $url);
        if (substr($url[0], -1) !== '/')
        {
            $url[0] .= '.html';
        }
        $url = implode('?', $url);
        return array($url, $baseurl, $params);
    }
    
    public static function ParseRequestTypeBasic($args=array())
    {
        return $args;
    }
    
    public static function ParseRequestTypePermalink($args=array())
    {
        if (isset($_SERVER['REDIRECT_URL']))
        {
            $uri = Request::GetURI();
            $uri = explode('/', $uri);
            $n_uri = count($uri);
            $r_filter = $r_permalink = $r_locale = $uri[0];
            $pr_locale = I18N::GetDefaultLanguage();
            $pr_page = null;
            $pr_filter = null;
            if ($n_uri > 1)
            {
                $r_filter = $r_permalink = $uri[$n_uri - 1];
                if ($n_uri > 2)
                {
                    $r_filter = $uri[$n_uri - 2];
                }
            }
            
            $languages = I18N::GetLanguages();
            foreach ($languages AS $locale => $lang)
            {
                if ($r_locale === $lang['iso'])
                {
                    $pr_locale = $locale;
                    break;
                }
            }
            
            $page_parent = null;
            $page = Page::GetPageByPermalink($r_permalink, $pr_locale, false);
            if ($page->id !== -1)
            {
                $pr_page = $page->id;
                $page_parent = $page->parent;
            }
            
            if ($r_permalink !== $r_filter)
            {
                $page = Page::GetPageByPermalink($r_filter, $pr_locale, false);
                if ($page->id !== -1 && $page_parent !== $page->id)
                {
                    $pr_filter = $page->id;
                }
            }
            
            
            $routing = static::GetRouting();
            $routing->request[static::REQUEST_KEY_LANGUAGE] = is_null($pr_locale) ? $routing->request[static::REQUEST_KEY_LANGUAGE] : $pr_locale;
            $routing->request[static::REQUEST_KEY_PAGE] = is_null($pr_page) ? $routing->request[static::REQUEST_KEY_PAGE] : $pr_page;
            $routing->request[static::REQUEST_KEY_PERMALINK] = is_null($r_permalink) ? $routing->request[static::REQUEST_KEY_PERMALINK] : $r_permalink;
            $routing->request[static::REQUEST_KEY_FILTER] = is_null($pr_filter) ? $routing->request[static::REQUEST_KEY_FILTER] : $pr_filter;
            /*$this->request[static::REQUEST_KEY_PARENT] = Request::GetValueGP(static::REQUEST_KEY_PARENT, null, true);*/
            Session::SetValue(static::REQUEST_KEY_LANGUAGE, $routing->request[static::REQUEST_KEY_LANGUAGE]);
        }
        return $args;
    }
    
    public static function ParseRequestTypePermalinkHTMLExt($args=array())
    {
        if (isset($_SERVER['REDIRECT_URL']))
        {
            
            $domain_name = Request::GetBaseUrl(false);
            $domain = Domain::GetDomainByName($domain_name);
            $domains_by_language = get_routing_domains_by_language();
            $languages = I18N::GetLanguages();
            $default_language = I18N::GetDefaultLanguage();
            $uri = Request::GetURI();
            $uri = explode('/', $uri);
            $n_uri = count($uri);
            $r_filter = $r_parent = $r_permalink = $r_locale = $uri[0];
            $pr_locale = null;
            $pr_page = null;
            $pr_parent = null;
            $pr_filter = null;
            $page_parent = null;
            
            
            foreach ($languages AS $locale => $lang)
            {
                if ($r_locale === $lang['iso'])
                {
                    $pr_locale = $locale;
                    break;
                }
            }
            
            if (is_null($pr_locale))
            {
                $pr_page = -1;
                $pr_locale = $default_language;
                foreach ($domains_by_language AS $locale => $domains_ids)
                {
                    if (in_array($domain->id, $domains_ids))
                    {
                        $pr_locale = $locale;
                        break;
                    }
                }
            }
            
            if ($n_uri > 1)
            {
                $pr_page = -1;
                $r_permalink = $uri[$n_uri - 1];
                if ($n_uri > 2)
                {
                    $r_parent = $uri[$n_uri - 2];
                }
                if ($n_uri > 3)
                {
                    $r_filter = $uri[$n_uri - 3];
                }
            }
            $r_permalink = str_replace('.html', '', $r_permalink);
            
            //var_dump($r_parent);
            //var_dump($r_filter);
            //var_dump($r_permalink);
            
            $page = Page::GetPageByPermalink($r_permalink, $pr_locale, false);
            if ($page->id !== -1)
            {
                $pr_page = $page->id;
                $page_parent = $page->parent;
            }
            
            if ($r_permalink !== $r_filter)
            {
                $page = Page::GetPageByPermalink($r_filter, $pr_locale, false);
                if ($page->id !== -1 && $page_parent !== $page->id)
                {
                    $pr_filter = $page->id;
                }
            }
            
            if ($r_filter !== $r_parent)
            {
                $page = Page::GetPageByPermalink($r_parent, $pr_locale, false);
                if ($page->id !== -1) // && $page_parent !== $page->id
                {
                    $pr_parent = $page->id;
                }
            }
            
            if (is_null($pr_filter) && !is_null($pr_parent))
            {
                $pr_filter = $pr_parent;
                $pr_parent = null;
            }
            
            $routing = static::GetRouting();
            $routing->request[static::REQUEST_KEY_LANGUAGE] = is_null($pr_locale) ? $routing->request[static::REQUEST_KEY_LANGUAGE] : $pr_locale;
            $routing->request[static::REQUEST_KEY_PAGE] = is_null($pr_page) ? $routing->request[static::REQUEST_KEY_PAGE] : $pr_page;
            $routing->request[static::REQUEST_KEY_PERMALINK] = is_null($r_permalink) ? $routing->request[static::REQUEST_KEY_PERMALINK] : $r_permalink;
            $routing->request[static::REQUEST_KEY_FILTER] = is_null($pr_filter) ? $routing->request[static::REQUEST_KEY_FILTER] : $pr_filter;
            $routing->request[static::REQUEST_KEY_PARENT] = is_null($pr_parent) ? $routing->request[static::REQUEST_KEY_PARENT] : $pr_parent;
            Session::SetValue(static::REQUEST_KEY_LANGUAGE, $routing->request[static::REQUEST_KEY_LANGUAGE]);
            //var_dump($routing->request); die();
        }
        return $args;
    }
}
