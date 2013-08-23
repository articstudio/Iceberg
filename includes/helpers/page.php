<?php

function get_pages($args=array(), $lang=null)
{
    return Page::GetList($args, $lang);
}

function get_page($id, $lang=null)
{
    return Page::GetPage($id, $lang);
}

function page_sort_by_name($a, $b)
{
    $an = $a->GetTitle();
    $bn = $b->GetTitle();
    if ($an == $bn) {
        return 0;
    }
    return ($an < $bn) ? -1 : 1;
}
