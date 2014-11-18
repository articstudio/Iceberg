<?php

$id = get_request_id();
$lang = array(
    'name' => get_request_gp('name', '', true),
    'locale' => get_request_gp('locale', '', true),
    'iso' => get_request_gp('iso', '', true),
    'flag' => get_request_gp('flag', '', true)
);
if (I18N::Update($id, $lang))
{
    register_alert('Language updated', 'success');
}
else
{
    register_alert('Failed to update language', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
