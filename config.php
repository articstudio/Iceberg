<?php

/**#@+
 * Session constants
 */
define('ICEBERG_SESSION_NAME',          'ICEBERGID');
define('ICEBERG_SESSION_TIME',          86400);
/**#@-*/

/**#@+
 *  CMS Directories
 */
define('ICEBERG_DIR',                   dirname( __FILE__ ) . DIRECTORY_SEPARATOR);
define('ICEBERG_DIR_ADMIN',             ICEBERG_DIR . 'admin' . DIRECTORY_SEPARATOR);
define('ICEBERG_DIR_INCLUDES',          ICEBERG_DIR . 'includes' . DIRECTORY_SEPARATOR);
define('ICEBERG_DIR_CONTENT',           ICEBERG_DIR . 'content' . DIRECTORY_SEPARATOR);
define('ICEBERG_DIR_API',               ICEBERG_DIR . 'api' . DIRECTORY_SEPARATOR);
/**#@-*/

/** Debug mode */
define('ICEBERG_DEBUG_MODE',            1);

/** Default language */
define('ICEBERG_DEFAULT_LANGUAGE',      'ca_ES');

/** DB tables configuration and file */
define('ICEBERG_DB_FILE',               ICEBERG_DIR . 'db.php');


