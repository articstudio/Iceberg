<?php

$lang = array(
    'name' => get_request_gp('name', '', true),
    'locale' => get_request_gp('locale', '', true),
    'iso' => get_request_gp('iso', '', true),
    'flag' => get_request_gp('flag', '', true)
);
if (I18N::Insert($lang))
{
    register_alert('Language inserted', 'success');
}
else
{
    register_alert('Failed to insert language', 'error');
}

locate(get_admin_action_link(array('action'=>'list')));
