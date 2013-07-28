<?php

$action = get_request_action();

if ($action == 'save')
{
    $metatag = array(
        'title' => get_request_gp('metatag_title', Metatag::$CONFIG_DEFAULTS['title'], true),
        'description' => get_request_gp('metatag_description', Metatag::$CONFIG_DEFAULTS['description'], true),
        'keywords' => get_request_gp('metatag_keywords', Metatag::$CONFIG_DEFAULTS['keywords'], true)
    );
    $number = array(
        'decimals' => (int)get_request_gp('number_decimals', Number::$CONFIG_DEFAULTS['decimals'], true),
        'decimals_point' => get_request_gp('number_decimals_point', Number::$CONFIG_DEFAULTS['decimals_point'], true),
        'thousands_point' => get_request_gp('number_thousands_point', Number::$CONFIG_DEFAULTS['thousands_point'], true)
    );
    $time = array(
        'timezone' => get_request_gp('time_timezone', Time::$CONFIG_DEFAULTS['timezone'], true),
        'time_format' => get_request_gp('time_time_format', Time::$CONFIG_DEFAULTS['time_format'], true),
        'date_format' => get_request_gp('time_date_format', Time::$CONFIG_DEFAULTS['date_format'], true),
        'datetime_format' => get_request_gp('time_datetime_format', Time::$CONFIG_DEFAULTS['datetime_format'], true)
    );
    if (Metatag::Save($metatag) && Number::Save($number) && Time::Save($time))
    {
        add_alert('Settings saved', 'success');
    }
    else
    {
        add_alert('Failed to save the settings', 'error');
    }
}


