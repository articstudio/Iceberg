<?php

$id = get_request_id();
if (I18N::Remove($id))
{
    register_alert('Language removed', 'success');
}
else
{
    register_alert('Failed to remove language', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
