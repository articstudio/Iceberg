<?php

$id = get_request_id(); 
if (Page::Remove($id))
{
    register_alert('Page removed', 'success');
}
else
{
    register_alert('Failed to remove page', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
