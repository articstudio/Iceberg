<?php

$action = get_request_action();
$id = get_request_id();

if ($action == 'active')
{
    if (Extension::Active($id))
    {
        add_alert('Extension actived', 'success');
    }
    else
    {
        add_alert('Failed to activate extension', 'error');
    }
}
else if ($action == 'unactive')
{
    if (Extension::Unactive($id))
    {
        add_alert('Extension unactived', 'success');
    }
    else
    {
        add_alert('Failed to unactivate extension', 'error');
    }
}