<?php

/**
 * Returns domain ID
 * 
 * @uses Domains::GetID()
 * @return int Domain ID
 */
function get_domain_id()
{
    return Domain::GetID();
}

/**
 * Returns domain request ID
 * 
 * @uses Domains::GetRequestID()
 * @return int Domain request ID
 */
function get_domain_request_id()
{
    return Domain::GetRequestID();
}

/**
 * Set domain request ID
 * 
 * @uses Domains::SetRequestID()
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
 * @uses Domains::SetDomainID()
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

function get_domains_canonicals()
{
    return Domain::GetCanonicals();
}

function get_domains_alias($id=null)
{
    return Domain::GetAlias($id);
}

function get_domains_tree()
{
    return Domain::GetTree();
}