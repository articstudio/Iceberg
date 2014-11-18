<?php

$args = array(
    'id' => 'themes-list',
    'classes' => array('data-sort', 'data-filter')
);

$table = new TableThemes($args);
$table->loadItems();
$table->show();
