<?php

function get_metatag($key, $default='')
{
    return Metatag::GetMetatag($key, $default);
}

function get_metatag_title()
{
    return Metatag::GetTitle();
}

function get_metatag_description()
{
    return Metatag::GetDescription();
}

function get_metatag_keywords()
{
    return Metatag::GetKeywords();
}
