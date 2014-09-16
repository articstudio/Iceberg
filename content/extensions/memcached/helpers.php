<?php

function iceberg_loaded_memcached($args)
{
    IcebergMemcached::PrintLog();
    return $args;
}
