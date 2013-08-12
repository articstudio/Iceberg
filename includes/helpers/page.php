<?php

function get_pages($args=array(), $lang=null)
{
    return Page::GetList($args, $lang);
}

function get_page($id, $lang=null)
{
    return Page::GetPage($id, $lang);
}

