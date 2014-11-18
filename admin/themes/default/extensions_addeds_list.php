<?php

$args = array(
    'id' => 'extensions-list',
    'classes' => array('data-sort', 'data-filter'),
    'attrs' => array(
        'data-paginate' => 20
    )
);

$table = new TableExtensions($args);
$table->loadItems();
$table->show();
