<?php

/**************************************************************************
 * CONSTANTS
 **************************************************************************/


/**
 * Iceberg version
 */
define('ICEBERG_VERSION',                   '1.0');

/**
 * PHP required version for this Iceberg version
 */
define('ICEBERG_PHP_VERSION_REQUIRED',      '5.3.0');

/**
 * Iceberg helpers directory
 */
define('ICEBERG_DIR_HELPERS',               ICEBERG_DIR_INCLUDES . 'helpers' . DIRECTORY_SEPARATOR);

/**
 * Iceberg install directory
 */
define('ICEBERG_DIR_INSTALL',               ICEBERG_DIR_INCLUDES . 'install' . DIRECTORY_SEPARATOR);

/**
 * Iceberg taxonomy directory
 */
define('ICEBERG_DIR_TAXONOMY',     ICEBERG_DIR_INCLUDES . 'taxonomy' . DIRECTORY_SEPARATOR);

/**
 * Iceberg widgets directory
 */
define('ICEBERG_DIR_WIDGETS',               ICEBERG_DIR_INCLUDES . 'widgets' . DIRECTORY_SEPARATOR);

/**
 * Iceberg themes directory
 */
define('ICEBERG_DIR_THEMES',                ICEBERG_DIR_CONTENT . 'themes' . DIRECTORY_SEPARATOR);

/**
 * Iceberg languages directory
 */
define('ICEBERG_DIR_LANGUAGES',             ICEBERG_DIR_CONTENT . 'languages' . DIRECTORY_SEPARATOR);

/**
 * Iceberg language flags directory
 */
define('ICEBERG_DIR_LANGUAGES_FLAGS',       ICEBERG_DIR_LANGUAGES . 'flags' . DIRECTORY_SEPARATOR);

/**
 * Iceberg ectensions directory
 */
define('ICEBERG_DIR_EXTENSIONS',            ICEBERG_DIR_CONTENT . 'extensions' . DIRECTORY_SEPARATOR);

/**
 * Iceberg uploads content directory
 */
define('ICEBERG_DIR_UPLOADS',               ICEBERG_DIR_CONTENT . 'uploads' . DIRECTORY_SEPARATOR);

/**
 * Iceberg temp content directory
 */
define('ICEBERG_DIR_TEMP',                  ICEBERG_DIR_CONTENT . 'temp' . DIRECTORY_SEPARATOR);

/**
 * Iceberg api includes directory
 */
define('ICEBERG_DIR_API_INCLUDES',          ICEBERG_DIR_API . 'includes' . DIRECTORY_SEPARATOR);

/**
 * Iceberg admin includes directory
 */
define('ICEBERG_DIR_ADMIN_INCLUDES',        ICEBERG_DIR_ADMIN . 'includes' . DIRECTORY_SEPARATOR);

/**
 * Iceberg admin controllers directory
 */
define('ICEBERG_DIR_ADMIN_CONTROLLERS',     ICEBERG_DIR_ADMIN_INCLUDES . 'controllers' . DIRECTORY_SEPARATOR);

/**
 * Iceberg admin themes directory
 */
define('ICEBERG_DIR_ADMIN_THEMES',          ICEBERG_DIR_ADMIN . 'themes' . DIRECTORY_SEPARATOR);

/**
 * Iceberg database prefix
 * var String 
 */
$__ICEBERG_DB_PREFIX = defined('ICEBERG_DB_PREFIX') ? constant('ICEBERG_DB_PREFIX') : '';


define('ICEBERG_DB_RELATIONS',              $__ICEBERG_DB_PREFIX . 'relations');
define('ICEBERG_DB_DOMAINS',                $__ICEBERG_DB_PREFIX . 'domains');
define('ICEBERG_DB_USERS',                  $__ICEBERG_DB_PREFIX . 'users');
define('ICEBERG_DB_USERS_METAS',            $__ICEBERG_DB_PREFIX . 'users_metas');
define('ICEBERG_DB_USERS2DOMAINS',          $__ICEBERG_DB_PREFIX . 'users2domains');
define('ICEBERG_DB_CONFIG',                 $__ICEBERG_DB_PREFIX . 'config');
define('ICEBERG_DB_TAXONOMY',               $__ICEBERG_DB_PREFIX . 'taxonomy');
define('ICEBERG_DB_PAGES',                  $__ICEBERG_DB_PREFIX . 'pages');
define('ICEBERG_DB_PAGES_METAS',            $__ICEBERG_DB_PREFIX . 'pages_metas');
define('ICEBERG_DB_ACTIONS',                $__ICEBERG_DB_PREFIX . 'actions');

/**#@+
 * MySQL
 */
define('MYSQL_QUERY_ALL_CONNECTIONS',       '_ICEBERG_MYSQL_USE_ALL_CONNECTIONS_');
define('MYSQL_ROW_AS_ARRAY',                0);
define('MYSQL_ROW_AS_OBJECT',               1);


/**************************************************************************
 * VARIABLES
 **************************************************************************/


/**#@+
 * Iceberg
 */
$__ICEBERG                      = null;
$__ICEBERG_INITIALIZED          = false;
$__ICEBERG_BOOTSTRAP            = array();
$__ICEBERG_ADMIN                = false;
$__ICEBERG_API                  = false;
/**#@-*/

/**#@+
 * Languages
 */
$__ICEBERG_LANGUAGES            = array('ca_ES', 'en_US', 'es_ES');
$__LANGUAGES                    = array();
$__LANGUAGE                     = null;
$__I18N_TEXT                    = array();
/**#@-*/

/**#@+
 * MySQL
 */
$__MYSQL_QUERY                  = null;
$__MYSQL_LINK                   = array();
$__MYSQL_CONFIG                 = array();
$__MYSQL_ROW_METHOD             = MYSQL_ROW_AS_OBJECT;
$__MYSQL_ERROR_DEBUG_LOG        = true;
$__MYSQL_ERROR_DEBUG_LOG_FILE   = '';
$__MYSQL_ERROR_DEBUG_SHOW       = true;
$__MYSQL_QUERY_DEBUG             = array();
/**#@-*/

/**#@+
 * Domains
 */
$__DOMAIN_ID                    = null;
$__DOMAIN_CANONICAL             = null;
/**#@-*/

/**#@+
 * Session
 */
$__SESSION_ID                   = null;
/**#@-*/

/**#@+
 * Configuration
 */
$__CONFIG                       = array();
$__CONFIG_ROWS                  = array();
/**#@-*/

/**#@+
 * CACHE
 */
$__CACHE_OBJECTS                = array();
$__CACHE_DEBUG                  = array();
/**#@-*/

/**#@+
 * User
 */
$__USER_LOGIN                   = false;
$__USER_LOGGED                  = false;
$__USER                         = false;
/**#@-*/

/**#@+
 * Actions
 */
$__ACTIONS                      = array();
/**#@-*/








/**#@+
 * Hooks
 */
$__HOOKS = array();
/**#@-*/








