<?php

$id = get_request_id();
if (Extension::Active($id))
{
    register_alert('Extension actived', 'success');
}
else
{
    register_alert('Failed to activate extension', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
