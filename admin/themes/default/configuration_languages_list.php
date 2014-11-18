<?php

$args = array(
    'id' => 'languages-list',
    'attrs' => array(
        'data-new' => get_admin_action_link(array('module'=>'configuration','mode'=>'languages','action'=>'new')),
        'data-order' => get_admin_api_action_link(array('module'=>'configuration','mode'=>'languages','action'=>'order'))
    )
);

$table = new TableLanguages($args);
$table->loadItems();
$table->show();
