<?php

$mode = get_mode('mode');
$obj = false;
$args = array(
    'name' => get_request_p('name', '', true)
);
if ($mode === PageGroup::$TAXONOMY_KEY)
{
    $args['type'] = get_request_p('type', array());
    $obj = new PageGroup($args);
}
else if ($mode === PageType::$TAXONOMY_KEY)
{
    $args['taxonomy'] = get_request_p('taxonomy', array());
    $obj = new PageType($args);
}
else if ($mode === PageTaxonomy::$TAXONOMY_KEY)
{
    $e_names = get_request_p('element_name', array());
    $e_types = get_request_p('element_type', array());
    $args['permalink'] = get_request_p('permalink', false);
    $args['permalink-comments'] = get_request_p('comments-permalink', '', true);
    $args['text'] = get_request_p('text', false);
    $args['text-comments'] = get_request_p('comments-text', '', true);
    $args['image'] = get_request_p('image', false);
    $args['image-comments'] = get_request_p('comments-image', '', true);
    $args['childs'] = get_request_p('childs', false);
    $args['user_relation'] = get_request_p('user_relation', false);
    $args['user_role'] = get_request_p('user_role', get_default_user_role());
    $args['templates'] = get_request_p('templates', array());
    $args['elements'] = array();
    foreach ($e_names AS $k => $e_name) {
        if (!empty($e_name) && isset($e_types[$k])) {
            $e_type = $e_types[$k];
            array_push($args['elements'], array('name' => $e_name, 'type' => $e_type));
        }
    }
    $obj = new PageTaxonomy($args);
}
if ($obj && ObjectTaxonomy::Insert($obj))
{
    register_alert('Taxonomy item inserted', 'success');
}
else
{
    register_alert('Failed to insert taxonomy item', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
