<?php

error_reporting(0); // Set E_ALL for debuging

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
// Required for FTP connector support
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function elFinderFileAccessControl($attr, $path, $data, $volume) {
	//return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
	//	? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
	//	:  null;                                    // else elFinder decide it itself
    $allowed = strpos(basename($path), '.') === 0 ? !($attr == 'read' || $attr == 'write') :  null;
    return User::IsAdmin() ? $allowed : false;
}

$opts = array(
    'debug' => ICEBERG_DEBUG_MODE,
    'locale' => I18N::GetLanguage(),
    'roots' => array(
        array(
            'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
            'path'          => ICEBERG_DIR_UPLOADS,         // path to files (REQUIRED)
            'URL'           => File::GetURL(ICEBERG_DIR_UPLOADS, ICEBERG_DIR, get_base_url()), // URL to files (REQUIRED)
            'accessControl' => 'elFinderFileAccessControl'             // disable and hide dot starting files (OPTIONAL)
        )
    )
);

/* TRANSLATORS */
if (User::IsLogged())
{
    $user = User::GetUser();
    $user_level = $user->level;
    if ($user_level == Session::GetAdminLevel())
    {
        $done = true;
        $path = ICEBERG_DIR_UPLOADS . 'USERS' . DIRECTORY_SEPARATOR . get_user_id() . DIRECTORY_SEPARATOR;
        if (!is_dir($path))
        {
            $done = @mkdir($path, 0755, true);
            $txt .= ($done ? 'Created' : 'Creation error') . "\n";
            
        }
        $opts['roots'][0]['path'] = $path;
        $opts['roots'][0]['URL'] = File::GetURL($path, ICEBERG_DIR, get_base_url());
    }
}
/* ---------------- */
// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

