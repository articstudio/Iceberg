<?php

$id = get_request_id(); 
if (Page::Unactive($id))
{
    register_alert('Page unactived', 'success');
}
else
{
    register_alert('Failed to unactivate the page', 'error');
}

locate(get_admin_action_link(array('action'=>'list')), 302);
