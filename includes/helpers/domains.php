<?php

/**
 * Returns domain ID
 * 
 * @uses Domain::GetID()
 * @return int Domain ID
 */
function get_domain_id()
{
    return Domain::GetID();
}

/**
 * Returns domain request ID
 * 
 * @uses Domain::GetRequestID()
 * @return int Domain request ID
 */
function get_domain_request_id()
{
    return Domain::GetRequestID();
}

/**
 * Set domain request ID
 * 
 * @uses Domain::SetRequestID()
 * @param int $id
 * @return bool
 */
function set_domain_request_id($id)
{
    return Domain::SetRequestID($id);
}

/**
 * Set domain ID
 * 
 * @uses Domain::SetDomainID()
 * @param int $id
 * @return bool
 */
function set_domain_id($id)
{
    return Domain::SetDomainID($id);
}

function get_domain_canonical()
{
    return Domain::GetCanonical();
}

function get_domain_name()
{
    return Domain::GetName();
}

function get_domains_canonicals($cache=true)
{
    return Domain::GetCanonicals($cache);
}

function get_domains_by_parent($id, $cache=true)
{
    return Domain::GetDomainsByParent($id, $cache);
}

function get_domains($cache=true)
{
    return Domain::GetDomains($cache);
}

function get_domain($id=null, $cache=true)
{
    return Domain::GetDomain($id, $cache);
}