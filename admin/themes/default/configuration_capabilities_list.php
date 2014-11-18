<?php

$args = array(
    'id' => 'capabilities-list',
    'classes' => array('data-sort', 'data-filter'),
    'attrs' => array(
        'data-new' => get_admin_action_link(array('action'=>'new')),
        'data-paginate' => 20
    )
);

$table = new TableCapabilities($args);
$table->loadItems();
$table->show();
