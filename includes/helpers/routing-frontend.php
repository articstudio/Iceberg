<?php

function get_request_page()
{
    return RoutingFrontend::GetRequestPage();
}

function get_request_filter()
{
    return RoutingFrontend::GetRequestFilter();
}

function get_request_parent()
{
    return RoutingFrontend::GetRequestParent();
}

function get_breadcrumb($id=false)
{
    return RoutingFrontend::GetBreadcrumb($id);
}

function routing_get_canonicals_fontend($arr)
{
    return RoutingFrontend::GetFrontendCanonicals($arr);
}

function routing_get_types_frontend($arr)
{
    return RoutingFrontend::GetFrontendTypes($arr);
}

function routingfrontend_make_url_canonical_not_force($args=array())
{
    return $args;
}

function routingfrontend_make_url_canonical_force($url, $baseurl, $params)
{
    return RoutingFrontend::MakeURLCanonicalForce($url, $baseurl, $params);
}

function routingfrontend_make_url_canonical_force_by_language($url, $baseurl, $params)
{
    return RoutingFrontend::MakeURLCanonicalForceByLanguage($url, $baseurl, $params);
}

function routingfrontend_make_url_type_basic($url, $baseurl, $params)
{
    return RoutingFrontend::MakeURLTypeBasic($url, $baseurl, $params);
}

function routingfrontend_make_url_type_permalink($url, $baseurl, $params)
{
    return RoutingFrontend::MakeURLTypePermalink($url, $baseurl, $params);
}

function routingfrontend_make_url_type_permalink_html_ext($url, $baseurl, $params)
{
    return RoutingFrontend::MakeURLTypePermalinkHTMLExt($url, $baseurl, $params);
}

function routingfrontend_parserequest_type_basic($args=array())
{
    return RoutingFrontend::ParseRequestTypeBasic($args);
}

function routingfrontend_parserequest_type_permalink($args=array())
{
    return RoutingFrontend::ParseRequestTypePermalink($args);
}

function routingfrontend_parserequest_type_permalink_html_ext($args=array())
{
    return RoutingFrontend::ParseRequestTypePermalinkHTMLExt($args);
}