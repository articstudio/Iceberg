<?php

/**
 * Returns domain ID
 * 
 * @uses Domains::GetID()
 * @return int Domain ID
 */
function get_domain_id()
{
    return Domains::GetID();
}

/**
 * Returns domain request ID
 * 
 * @uses Domains::GetRequestID()
 * @return int Domain request ID
 */
function get_domain_request_id()
{
    return Domains::GetRequestID();
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
    return Domains::SetRequestID($id);
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
    return Domains::SetRequestID($id);
}

function get_domain_canonical()
{
    return Domains::GetCanonical();
}

function get_domain_name()
{
    return Domains::GetName();
}

function get_domains_canonicals()
{
    return Domains::GetCanonicals();
}

function get_domains_alias($id=null)
{
    return Domains::GetAlias($id);
}

function get_domains_tree()
{
    return Domains::GetTree();
}