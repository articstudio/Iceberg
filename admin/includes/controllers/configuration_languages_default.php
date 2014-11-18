<?php

$id = get_request_id();
if (I18N::MakeDefault($id))
{
    register_alert('Language is default', 'success');
}
else
{
    register_alert('Failed to set language to default', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
