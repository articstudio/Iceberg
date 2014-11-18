<?php

$args = array(
    'filename' => 'DB_Backup_Iceberg_' . ICEBERG_VERSION . '_' . get_domain_name() . '_' . time()
);
$file  = MySQL::Dump($args);
if ($file && is_file($file))
{
    $info = pathinfo($file); //var_dump($info); die();
    $filename = $info['filename'] . '.' . $info['extension'];
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . filesize( $file ));
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $done = readfile( $file );
    unlink( $file );
    exit;
}
die('ERROR');
