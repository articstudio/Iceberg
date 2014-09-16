<?php

$action = get_request_action();
$permalink = get_request_gp('permalink', false, true);
$exclude = (int)get_request_gp('exclude', 0);

if ($action === 'exists')
{
    $return = array(
        'exists' => false,
        'permalink' => $permalink
    );
    if ($permalink)
    {
        $get_exists = false;
        $exists = true;
        $n = 0;
        
        if ($exclude > 0)
        {
            $page = get_page($exclude);
            if ($page->GetPermalink() == $permalink)
            {
                $exists = false;
            }
        }
        
        while ($exists)
        {
            $fields = array(
                'id'
            );
            $where = array(
                'name' => PageMeta::META_PERMALINK,
                'value' => $permalink
            );
            $relations = array(
                PageMeta::RELATION_KEY_PAGE => PageMeta::DB_RELATION_NOT_NULL
            );
            $metas = PageMeta::DB_Select($fields, $where, array(), array(), $relations);
            if (count($metas)>0)
            {
                if ($n === 0)
                {
                    $permalink .= '-1';
                    $get_exists = true;
                }
                else
                {
                    $permalink = explode('-', $permalink);
                    $int = (int)$permalink[count($permalink)-1];
                    ++$int;
                    $permalink[count($permalink)-1] = $int;
                    $permalink = implode('-', $permalink);
                }
            }
            else
            {
                $exists = false;
                break;
            }
            $n++;
        }
        $return = array(
            'exists' => $get_exists,
            'permalink' => $permalink
        );
    }
    echo json_encode($return);
    die();
}









