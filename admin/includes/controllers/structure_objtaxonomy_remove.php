<?php

$id = get_request_id();
if (ObjectTaxonomy::Remove($id))
{
    register_alert('Taxonomy item removed', 'success');
}
else
{
    register_alert('Failed to remove taxonomy item', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
