<?php
$mode = get_mode('mode');
$args = array(
    'id' => 'objtaxonomy-list',
    'type' => $mode,
    'attrs' => array(
        'data-new' => get_admin_action_link(array('action'=>'new')),
        'data-order' => get_admin_api_action_link(array('action'=>'order')),
        'data-paginate' => 20
    ),
    'classes' => array()
);
$table = new TableObjTaxonomy($args);
$table->loadItems();
$table->show();
