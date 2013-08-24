<?php

$action = get_request_action();

if ($action == 'backup')
{
    header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename="DB_Backup_Iceberg_' . ICEBERG_VERSION . '_' . get_domain_name() . '_' . time() . '.sql"');
    $dump  = MySQL::Dump();
    echo $dump;
    die();
}


