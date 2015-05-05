<?php

function register_alert($txt, $type='info', $permanent=false, $user_id=true)
{
    return Alerts::RegisterAlert($txt, $type, $permanent, $user_id);
}

function get_alerts($user_id=true)
{
    return Alerts::GetAlerts($user_id);
}
