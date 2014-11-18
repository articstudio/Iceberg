<?php

$mode = explode('-', get_mode('mode'));
$pagegroup_id = isset($mode[1]) ? (int)$mode[1] : -1;

$args = array(
    'id' => 'pages-list',
    'pagegroup' => $pagegroup_id,
    'attrs' => array(
        //'data-ajax-slice' => 200,
        'data-ajax-callback' => 'callbackAjaxTableList',
        'data-ajax' => get_admin_api_action_link(array('action'=>'list-ajax','html'=>false)),
        'data-new' => get_admin_action_link(array('action'=>'new')),
        'data-paginate' => 20
    ),
    'classes' => array('data-sort', 'data-filter', 'column_actions')
);
$table = new TablePages($args);
$table->loadItems();
$table->show();
