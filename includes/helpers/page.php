<?php

function get_pages($args=array(), $lang=null, $cache=true, $metas=true)
{
    return Page::GetList($args, $lang, $cache, $metas);
}

function get_page_id()
{
    return Page::GetPageID();
}

function get_page($id=null, $lang=null, $cache=true)
{
    return Page::GetPage($id, $lang, $cache);
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

function has_page_meta($key, $id=null, $lang=null)
{
    $id = is_null($id) ? get_request_page() : $id;
    $page = Page::GetPage($id, $lang);
    return $page->HasMeta($key, $lang);
}

function get_page_meta($key, $id=null, $lang=null)
{
    $id = is_null($id) ? get_request_page() : $id;
    $page = Page::GetPage($id, $lang);
    return $page->GetMeta($key, false, $lang);
}
