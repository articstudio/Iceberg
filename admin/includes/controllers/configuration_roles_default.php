<?php

$id = get_request_id();
if (UserConfig::SaveDefaultRole($id))
{
    register_alert('Default user role saved', 'success');
}
else
{
    register_alert('Failed to set default user role', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
