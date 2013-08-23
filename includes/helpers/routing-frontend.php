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
