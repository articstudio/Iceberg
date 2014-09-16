<?php

$action = get_request_action();

if ($action === 'clean')
{
    $ids = get_request_p('users', array());
    if (is_array($ids))
    {
        foreach ($ids AS $uid)
        {
            User::Remove($uid);
        }
        add_alert('Users DB cleaned', 'success');
    }
}
