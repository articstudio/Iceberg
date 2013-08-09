<?php

function add_cache_object($id, $object)
{
    return IcebergCache::AddObject($id, $object);
}

function get_cache_object($id, $class)
{
    return IcebergCache::GetObject($id, $class);
}

function remove_cache_object($id, $class)
{
    return IcebergCache::RemoveObject($id, $class);
}

