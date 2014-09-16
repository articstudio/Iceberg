<?php

define('MEMCACHED_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR);
define('MEMCACHED_URL', get_file_url(MEMCACHED_DIR, ICEBERG_DIR, get_base_url()));

require_once MEMCACHED_DIR . 'Memcached.class.php';

IcebergMemcached::Initialize();
