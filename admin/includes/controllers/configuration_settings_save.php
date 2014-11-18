<?php

$metatag = array(
    'title' => get_request_gp('metatag_title', Metatag::$CONFIG_DEFAULTS['title'], true),
    'description' => get_request_gp('metatag_description', Metatag::$CONFIG_DEFAULTS['description'], true),
    'keywords' => get_request_gp('metatag_keywords', Metatag::$CONFIG_DEFAULTS['keywords'], true)
);
$session = array(
    'name' => get_request_gp('session_name', Session::$CONFIG_DEFAULTS['name'], true),
    'time' => get_request_gp('session_time', Session::$CONFIG_DEFAULTS['time'], true),
);
$time = array(
    'timezone' => get_request_gp('time_timezone', Time::$CONFIG_DEFAULTS['timezone'], true),
    'time_format' => get_request_gp('time_time_format', Time::$CONFIG_DEFAULTS['time_format'], true),
    'date_format' => get_request_gp('time_date_format', Time::$CONFIG_DEFAULTS['date_format'], true),
    'datetime_format' => get_request_gp('time_datetime_format', Time::$CONFIG_DEFAULTS['datetime_format'], true)
);
$number = array(
    'decimals' => (int)get_request_gp('number_decimals', Number::$CONFIG_DEFAULTS['decimals'], true),
    'decimals_point' => get_request_gp('number_decimals_point', Number::$CONFIG_DEFAULTS['decimals_point'], true),
    'thousands_point' => get_request_gp('number_thousands_point', Number::$CONFIG_DEFAULTS['thousands_point'], true)
);

if (Metatag::Save($metatag) && Session::Save($session) && Time::Save($time) && Number::Save($number))
{
    register_alert('Settings saved', 'success');
}
else
{
    register_alert('Failed to save the settings', 'error');
}

locate(get_admin_action_link(array('action'=>'edit')));