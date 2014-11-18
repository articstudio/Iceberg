<?php

$from = get_request_gp('fromPosition');
$to = get_request_gp('toPosition');
if ($from!==false && $to!==false)
{
    $from = (int)$from ;
    $to = (int)$to;
    if (I18N::ReOrder($from, $to))
    {
        add_env_alert('Items reordered', 'success');
    }
    else
    {
        add_env_alert('Failed to reorder items', 'error');
    }
}
else
{
    add_env_alert('Failed to reorder items', 'error');
}
