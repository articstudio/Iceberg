<?php

$id = get_request_id();
if (Extension::Unactive($id))
{
    register_alert('Extension unactived', 'success');
}
else
{
    register_alert('Failed to unactivate extension', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
