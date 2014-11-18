<?php

$id = get_request_id();
if (I18N::Active($id))
{
    register_alert('Language actived', 'success');
}
else
{
    register_alert('Failed to activate the language', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
