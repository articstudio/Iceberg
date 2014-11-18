<?php

/* REQUEST / VARIABLES */
$items_start = intval(get_request_gp('start', false));
$items = intval(get_request_gp('items', false));
$returnHTML = boolval(get_request_gp('html', 1));

$mode = explode('-', get_mode('mode'));
$pagegroup_id = isset($mode[1]) ? (int)$mode[1] : -1;

$args = array(
    'ajax' => true,
    'pagegroup' => $pagegroup_id,
    'html' => $returnHTML,
    'items' => $items,
    'items_start' => $items_start,
);
$table = new TablePages($args);
$table->loadItems();
$table->show();

///$iceberg = Iceberg::GetIceberg(); $iceberg->PrintLog();

die();
