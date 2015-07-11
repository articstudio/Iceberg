<?php

$id = get_request_id();
if (I18N::Invisible($id))
{
    register_alert('Language is invisible', 'success');
}
else
{
    register_alert('Failed to set language to invisible', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
