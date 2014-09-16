<?php

function cleanStringHTML($str)
{
    $str = br2nl($str);
    $str = strip_tags($str);
    return $str;
}

function br2nl($string)
{
    return preg_replace('#<br\s*?/?>#i', "\n", $string);
}
