<?php

$id = get_request_id();
$mode = get_mode('mode');
$obj = ObjectTaxonomy::Get($id);
$obj->SetName(get_request_p('name', '', true));
if ($mode === PageGroup::$TAXONOMY_KEY)
{
    $obj->SetType(get_request_p('type', array())); 
}
else if ($mode === PageType::$TAXONOMY_KEY)
{
    $obj->SetTaxonomy(get_request_p('taxonomy', array()));
}
else if ($mode === PageTaxonomy::$TAXONOMY_KEY)
{
    $obj->UsePermalink(get_request_p('permalink', false));
    $obj->PermalinkComments(get_request_p('comments-permalink', '', true));
    $obj->UseText(get_request_p('text', false));
    $obj->TextComments(get_request_p('comments-text', '', true));
    $obj->UseImage(get_request_p('image', false));
    $obj->ImageComments(get_request_p('comments-image', '', true));
    $obj->ChildsAllowed(get_request_p('childs', false));
    $obj->UserRelation(get_request_p('user_relation', false));
    $obj->UserRole(get_request_p('user_role', get_default_user_role()));
    $templates = get_request_p('templates', array());
    $obj->SetTemplates($templates);
    $e_names = get_request_p('element_name', array());
    $e_types = get_request_p('element_type', array());
    $elements = array();
    foreach ($e_names AS $k => $e_name) {
        if (!empty($e_name) && isset($e_types[$k])) {
            $e_type = $e_types[$k];
            array_push($elements, array('name' => $e_name, 'type' => $e_type));
        }
    }
    $obj->SetElements($elements);
}
if (ObjectTaxonomy::Update($id, $obj))
{
    register_alert('Taxonomy item updated', 'success');
}
else
{
    register_alert('Failed to update taxonomy item', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
