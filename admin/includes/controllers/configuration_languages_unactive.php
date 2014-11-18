<?php

$id = get_request_id();
if (I18N::Unactive($id))
{
    register_alert('Language unactived', 'success');
}
else
{
    register_alert('Failed to unactivate the language', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
