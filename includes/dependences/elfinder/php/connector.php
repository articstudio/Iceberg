<?php

error_reporting(0); // Set E_ALL for debuging

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';


function elFinderGetRootDirectory()
{
    $dir = ICEBERG_DIR_VOID;
    if (User::HasCapability('media_edit') || User::HasCapability('media_read'))
    {
        $dir = ICEBERG_DIR_UPLOADS;
    }
    else if (User::HasOwnCapability('media_read') || User::HasOwnCapability('media_edit'))
    {
        //$sanitized = preg_replace('/[^a-zA-Z0-9-_\.]/','', get_user_name());
        $path = ICEBERG_DIR_UPLOADS . 'USERS' . DIRECTORY_SEPARATOR . get_user_id() . DIRECTORY_SEPARATOR;
        if (!is_dir($path))
        {
            if (@mkdir($path, 0755, true))
            {
                $dir = $path;
            }
        }
        else
        {
            $dir = $path;
        }
    }
    return $dir;
}


function elFinderFileAccessControl($attr, $path, $data, $volume)
{
    $path .= is_dir($path) ? DIRECTORY_SEPARATOR : '';
    $dir = elFinderGetRootDirectory();
    if (!User::HasCapability('admin_login'))
    {
        return false;
    }
    if (strpos(basename($path), '.') === 0)
    {
        return !($attr === 'read' || $attr === 'write');
    }
    if ($attr === 'read')
    {
        return (
            User::HasCapability('media_read')
            || User::HasCapability('media_edit')
            || (
                (User::HasOwnCapability('media_edit') || User::HasOwnCapability('media_read'))
                && strpos($path, $dir)===0
            )
        );
    }
    if ($attr === 'write')
    {
        return (
            User::HasCapability('media_edit')
            || (
                User::HasOwnCapability('media_edit')
                && strpos($path, $dir)===0
            )
        );
    }
    return null;
}

$dir = elFinderGetRootDirectory();
$url = File::GetURL(ICEBERG_DIR_UPLOADS, ICEBERG_DIR, get_base_url());

$opts = array(
    'debug' => ICEBERG_DEBUG_MODE,
    'locale' => I18N::GetLanguage(),
	'roots' => array(
		array(
			'driver'        => 'LocalFileSystem',                                   // driver for accessing file system (REQUIRED)
			'path'          => $dir,                                                // path to files (REQUIRED)
			'URL'           => File::GetURL($dir, ICEBERG_DIR, get_base_url()),     // URL to files (REQUIRED)
            'alias'         => _T('MEDIA'),
            'tmbPath'       => ICEBERG_DIR_TEMP,
            'tmbURL'        => File::GetURL(ICEBERG_DIR_TEMP, ICEBERG_DIR, get_base_url()),
			'accessControl' => 'elFinderFileAccessControl'                           // disable and hide dot starting files (OPTIONAL)
		)
	)
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

