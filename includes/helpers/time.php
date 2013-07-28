<?php

/**
 * Returns Time Zones list
 * @uses Time::GetTimeZonesList()
 * @return array 
 */
function get_timezones()
{
    return Time::GetTimeZonesList();
}

function get_date_format()
{
    return Time::GetDateFormat();
}

function get_time_format()
{
    return Time::GetTimeFormat();
}

function get_datetime_format()
{
    return Time::GetDateTimeFormat();
}

function get_date($timestamp=null)
{
    return Time::GetDate($timestamp);
}

function get_time($timestamp=null)
{
    return Time::GetTime($timestamp);
}

function get_datetime($timestamp=null)
{
    return Time::GetDateTime($timestamp);
}
