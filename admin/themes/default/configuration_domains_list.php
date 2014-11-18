<?php

$args = array(
    'id' => 'domains-list',
    'classes' => array('data-filter'),
    'attrs' => array(
        //'data-new' => get_admin_action_link(array('module'=>'configuration','mode'=>'domains','action'=>'new'))
    )
);

$table = new TableDomains($args);
$table->loadItems();
$table->show();
