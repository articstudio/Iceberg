<?php

$id = get_request_id(); 
if (Page::Active($id))
{
    register_alert('Page actived', 'success');
}
else
{
    register_alert('Failed to activate the page', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
