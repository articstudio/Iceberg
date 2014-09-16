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

function routing_get_canonicals_fontend($args=array())
{
    return RoutingFrontend::GetFrontendCanonicals($args);
}

function routing_get_types_frontend($args=array())
{
    return RoutingFrontend::GetFrontendTypes($args);
}

function routingfrontend_make_url_canonical_not_force($args=array())
{
    return $args;
}

function routingfrontend_make_url_canonical_force($args=array())
{
    return RoutingFrontend::MakeURLCanonicalForce($args);
}

function routingfrontend_make_url_canonical_force_by_language($args=array())
{
    return RoutingFrontend::MakeURLCanonicalForceByLanguage($args);
}

function routingfrontend_make_url_type_basic($args=array())
{
    return RoutingFrontend::MakeURLTypeBasic($args);
}

function routingfrontend_make_url_type_permalink($args=array())
{
    return RoutingFrontend::MakeURLTypePermalink($args);
}

function routingfrontend_make_url_type_permalink_html_ext($args=array())
{
    return RoutingFrontend::MakeURLTypePermalinkHTMLExt($args);
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