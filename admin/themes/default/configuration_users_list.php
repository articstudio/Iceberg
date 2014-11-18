<?php

$args = array(
    'id' => 'users-list',
    'classes' => array('data-sort', 'data-filter'),
    'attrs' => array(
        'data-new' => get_admin_action_link(array('action'=>'new')),
        'data-paginate' => 20
    )
);

$table = new TableUsers($args);
$table->loadItems();
$table->show();
