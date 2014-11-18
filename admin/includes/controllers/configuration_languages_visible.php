<?php

$id = get_request_id();
if (I18N::Visible($id))
{
    register_alert('Language is visible', 'success');
}
else
{
    register_alert('Failed to set language to visible', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
