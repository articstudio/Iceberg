<?php

/**************************************************************************
 * CONSTANTS
 **************************************************************************/


/**#@+
 * Versions of system and required for PHP
 */
define('ICEBERG_VERSION',                   '1.0');
define('ICEBERG_PHP_VERSION_REQUIRED',      '5.3.0');
/**#@-*/

/**#@+
 * Directories of system
 */
define('ICEBERG_DIR_HELPERS',               ICEBERG_DIR_INCLUDES . 'helpers/');
//define('ICEBERG_DIR_CONTROLLERS',           ICEBERG_DIR_INCLUDES . 'controllers/');
define('ICEBERG_DIR_INSTALL',               ICEBERG_DIR_INCLUDES . 'install/');
define('ICEBERG_DIR_TAXONOMY_ELEMENTS',     ICEBERG_DIR_INCLUDES . 'taxonomy_elements/');
define('ICEBERG_DIR_WIDGETS',               ICEBERG_DIR_INCLUDES . 'widgets/');

define('ICEBERG_DIR_THEMES',                ICEBERG_DIR_CONTENT . 'themes/');
define('ICEBERG_DIR_LANGUAGES',             ICEBERG_DIR_CONTENT . 'languages/');
define('ICEBERG_DIR_LANGUAGES_FLAGS',       ICEBERG_DIR_CONTENT . 'languages/flags/');
define('ICEBERG_DIR_EXTENSIONS',            ICEBERG_DIR_CONTENT . 'extensions/');
define('ICEBERG_DIR_UPLOADS',               ICEBERG_DIR_CONTENT . 'uploads/');
define('ICEBERG_DIR_TEMP',                  ICEBERG_DIR_CONTENT . 'temp/');

define('ICEBERG_DIR_API_INCLUDES',        ICEBERG_DIR_API . 'includes/');

define('ICEBERG_DIR_ADMIN_INCLUDES',        ICEBERG_DIR_ADMIN . 'includes/');
define('ICEBERG_DIR_ADMIN_CONTROLLERS',     ICEBERG_DIR_ADMIN_INCLUDES . 'controllers/');
//define('ICEBERG_DIR_ADMIN_HELPERS',         ICEBERG_DIR_ADMIN_INCLUDES . 'helpers/');
define('ICEBERG_DIR_ADMIN_THEMES',          ICEBERG_DIR_ADMIN . 'themes/');
/**#@-*/

/**#@+
 * DB tables configuration
 */
$db_prefix = defined('ICEBERG_DB_PREFIX') ? constant('ICEBERG_DB_PREFIX') : '';
define('ICEBERG_DB_RELATIONS',              $db_prefix . 'relations');
define('ICEBERG_DB_DOMAINS',                $db_prefix . 'domains');
define('ICEBERG_DB_USERS',                  $db_prefix . 'users');
define('ICEBERG_DB_USERS_METAS',            $db_prefix . 'users_metas');
define('ICEBERG_DB_USERS2DOMAINS',          $db_prefix . 'users2domains');
define('ICEBERG_DB_CONFIG',                 $db_prefix . 'config');
define('ICEBERG_DB_TAXONOMY',               $db_prefix . 'taxonomy');

define('ICEBERG_DB_PAGES',                  $db_prefix . 'pages');
define('ICEBERG_DB_PAGES_METAS',            $db_prefix . 'pages_metas');
define('ICEBERG_DB_ACTIONS',                $db_prefix . 'actions');
/**#@-*/

/**#@+
 * MySQL
 */
define('MYSQL_QUERY_ALL_CONNECTIONS',   'all');
define('MYSQL_ROW_AS_ARRAY',            0);
define('MYSQL_ROW_AS_OBJECT',           1);
/**#@-*/


/**************************************************************************
 * VARIABLES
 **************************************************************************/


/**#@+
 * Iceberg
 */
$__ICEBERG = null;
$__ICEBERG_INITIALIZED = false;
$__ICEBERG_BOOTSTRAP = array();
$__ICEBERG_ADMIN = false;
$__ICEBERG_API = false;
/**#@-*/

/**#@+
 * Languages
 */
$__ICEBERG_LANGUAGES = array('ca_ES', 'en_US', 'es_ES');
$__LANGUAGES = array();
$__LANGUAGE = null;
$__I18N_TEXT = array();
/**#@-*/

/**#@+
 * MySQL
 */
$__MYSQL_QUERY = null;
$__MYSQL_LINK = array();
$__MYSQL_CONFIG = array();
$__MYSQL_ROW_METHOD = MYSQL_ROW_AS_OBJECT;
$__MYSQL_ERROR_DEBUG_LOG = true;
$__MYSQL_ERROR_DEBUG_LOG_FILE = '';
$__MYSQL_ERROR_DEBUG_SHOW = true;
$__MYSQL_QUERY_LIST = array();
/**#@-*/

/**#@+
 * Domains
 */
$__DOMAIN_ID = null;
$__DOMAIN_CANONICAL = null;
/**#@-*/

/**#@+
 * Session
 */
$__SESSION_ID = null;
/**#@-*/

/**#@+
 * Configuration
 */
$__CONFIG = array();
$__CONFIG_ROWS = array();
/**#@-*/

/**#@+
 * User
 */
$__USER_LOGIN = false;
$__USER_LOGGED = false;
$__USER = false;
/**#@-*/

/**#@+
 * Actions
 */
$__ACTIONS = array();
/**#@-*/


/**#@+
 * Taxonomy elements
 */
$__TAXONOMY_ELEMENTS = array(
    'TE_Text',
    /*
    'te_input',
    'te_images',
    'te_files',
    'te_links',
    'te_geolocation',
    'te_videos',
    'te_relationship',
    'te_date'
    'te_list'*/
);
/**#@-*/











/**#@+
 * Hooks
 */
$__HOOKS = array();
/**#@-*/








