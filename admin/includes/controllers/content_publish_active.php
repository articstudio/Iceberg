<?php

$id = get_request_id(); 
if (Page::Active($id))
{
    add_env_alert('Page actived', 'success');
}
else
{
    add_env_alert('Failed to activate the page', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
