<?php

/**
 * Default session name
 */
define('ICEBERG_SESSION_NAME',          'ICEBERGID');

/**
 * Default session time (seconds)
 */
define('ICEBERG_SESSION_TIME',          86400);

/**
 *  Iceberg directory
 */
define('ICEBERG_DIR',                   dirname( __FILE__ ) . DIRECTORY_SEPARATOR);

/**
 *  Iceberg admin directory (Backend)
 */
define('ICEBERG_DIR_ADMIN',             ICEBERG_DIR . 'admin' . DIRECTORY_SEPARATOR);

/**
 *  Iceberg includes directory
 */
define('ICEBERG_DIR_INCLUDES',          ICEBERG_DIR . 'includes' . DIRECTORY_SEPARATOR);

/**
 *  Iceberg content directory
 */
define('ICEBERG_DIR_CONTENT',           ICEBERG_DIR . 'content' . DIRECTORY_SEPARATOR);

/**
 *  Iceberg API directory
 */
define('ICEBERG_DIR_API',               ICEBERG_DIR . 'api' . DIRECTORY_SEPARATOR);

/**
 * Debug mode (Boolean)
 */
define('ICEBERG_DEBUG_MODE',            FALSE);

/**
 * Default language
 */
define('ICEBERG_DEFAULT_LANGUAGE',      'ca_ES');

/**
 * NONCE key
 */
define('ICEBERG_NONCE',                 '');

/**
 * DB tables configuration and file
 */
define('ICEBERG_DB_FILE',               ICEBERG_DIR . 'db.php');


