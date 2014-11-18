<?php

$id = get_request_id();
$obj = ObjectTaxonomy::Get($id);
$obj->Configure();
if (ObjectTaxonomy::Update($id, $obj))
{
    register_alert('Taxonomy item configured', 'success');
}
else
{
    register_alert('Failed to configure taxonomy item', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
