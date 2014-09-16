<?php

function NTOOLS_searchAndReplaceString($search, $replace, $value)
{
    if (is_object($value))
    {
        foreach ($value AS $k => $v)
        {
            $value->$k = NTOOLS_searchAndReplaceString($search, $replace, $v);
        }
    }
    else if (is_array($value))
    {
        foreach ($value AS $k => $v)
        {
            $value[$k] = NTOOLS_searchAndReplaceString($search, $replace, $v);
        }
    }
    if (is_string($value))
    {
        $value = str_replace($search, $replace, $value);
    }
    return $value;
}
function Slug($string, $slug = '-', $extra = null)
{
        return strtolower(trim(preg_replace('~[^0-9a-z' . preg_quote($extra, '~') . ']+~i', $slug, self::Unaccent($string)), $slug));
}
function NTOOLS_SanitizeStringForURL($string, $slug='-')
{
    //$string = filter_var($string, FILTER_SANITIZE_URL);
    $string = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|caron|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $string), ENT_QUOTES, 'UTF-8');
    $string = strtolower(trim(preg_replace('~[^0-9a-z]+~i', $slug, $string), $slug));
    return $string;
}

$action = get_request_action();

if ($action === 'nptr')
{
    $taxs = get_pagetaxonomies();
    $lang = get_lang();
    $langs = get_active_locales();
    foreach ($taxs AS $tax_id => $tax)
    {
        $elements_relation = array();
        $elements = $tax->GetElements();
        foreach ($elements AS $element_id => $element)
        {
            $element_type = $element->GetType();
            if ($element_type === 'TE_Relation')
            {
                array_push($elements_relation, $element_id);
            }
        }
        if (!empty($elements_relation))
        {
            $args = array(
                'taxonomy' => $tax_id
            );
            $pages = get_pages($args, $lang);
            $n_pages = count($pages);
            if ($n_pages > 0)
            {
                foreach ($pages AS $page_id => $page)
                {
                    $trans = $page->GetTranslations();
                    foreach ($trans AS $trans_lang)
                    {
                        if ($lang !== $trans_lang)
                        {
                            $page->LoadMetas($trans_lang);
                        }
                    }
                    foreach ($elements_relation AS $element_relation)
                    {
                        foreach ($langs AS $a_lang)
                        {
                            $meta = $page->GetMeta($element_relation, array(), $a_lang);
                            
                            Page::DeleteRelation($page_id, Page::RELATION_KEY_PAGE . '#' . $element_relation, $a_lang);
                            foreach ($meta AS $k => $parent)
                            {
                                Page::InsertRelation($page_id, Page::RELATION_KEY_PAGE . '#' . $element_relation, $parent, $a_lang, $k);
                            }
                        }
                        
                    }
                }
            }
        }
    }
    
    add_alert('Pages taxonomy relations normalized', 'success');
    //add_alert('Failed to save the maintenance configuration', 'error');
}
else if ($action === 'spp')
{
    $taxs = get_pagetaxonomies();
    $lang = get_lang();
    $langs = get_active_locales();
    foreach ($taxs AS $tax_id => $tax)
    {
        if ($tax->UsePermalink())
        {
            $args = array(
                'taxonomy' => $tax_id
            );
            $pages = get_pages($args, $lang);
            $n_pages = count($pages);
            if ($n_pages > 0)
            {
                foreach ($pages AS $page_id => $page)
                {
                    $name = $page->GetTitle($lang);
                    $permalink = $page->GetPermalink($lang);
                    if (!empty($name) && empty($permalink))
                    {
                        $new_permalink = NTOOLS_SanitizeStringForURL($name);
                        
                        $get_exists = false;
                        $exists = true;
                        $n = 0;
                        while ($exists)
                        {
                            $fields = array(
                                'id'
                            );
                            $where = array(
                                'name' => PageMeta::META_PERMALINK,
                                'value' => $new_permalink
                            );
                            $relations = array(
                                PageMeta::RELATION_KEY_PAGE => PageMeta::DB_RELATION_NOT_NULL
                            );
                            $metas = PageMeta::DB_Select($fields, $where, array(), array(), $relations);
                            if (count($metas)>0)
                            {
                                if ($n === 0)
                                {
                                    $new_permalink .= '-1';
                                    $get_exists = true;
                                }
                                else
                                {
                                    $new_permalink = explode('-', $new_permalink);
                                    $int = (int)$new_permalink[count($new_permalink)-1];
                                    ++$int;
                                    $new_permalink[count($new_permalink)-1] = $int;
                                    $new_permalink = implode('-', $new_permalink);
                                }
                            }
                            else
                            {
                                $exists = false;
                                break;
                            }
                            $n++;
                        }
                        
                        $args = array('value'=>$new_permalink);
                        $where = array('name'=>PageMeta::META_PERMALINK);
                        $relations = array(PageMeta::RELATION_KEY_PAGE=>$page_id);
                        PageMeta::DB_UpdateWhere(array('value'=>$new_permalink), $where, $relations, $lang);
                        //echo $page_id . ' - ' . $name . ' => ' . $new_permalink . ' ('.$permalink.')' . " <br/>\n";
                    }
                }
            }
        }
    }
    add_alert('Pages permalinks sanitized', 'success');
}
else if ($action === 'search-replace-metas')
{
    set_time_limit(0);
    $table = PageMeta::DB_GetTableName();
    $search = get_request_gp('txt_search', '', true);
    $replace = get_request_gp('txt_replace', '', true);
    $query = new Query();
    $update = new Query();
    $query->Query("SELECT id,value FROM $table WHERE 1 ORDER BY id ASC");
    if ($query->numrows() > 0 && !empty($search))
    {
        
        while ($row = $query->next())
        {
            $value = ObjectDB::DB_DecodeFieldValue($row->value);
            //$type = gettype($value);
            $value = NTOOLS_searchAndReplaceString($search, $replace, $value);
            $value = ObjectDB::DB_EncodeFieldValue($value);
            
            //echo $row->id . ' => (' . $type . ') "' . htmlentities($row->value) . '"<br>' . "\n";
            //echo $row->id . ' => (' . $type . ') "' . htmlentities($value) . '"<br><br>' . "\n\n";
            
            $update->Query("UPDATE $table SET value='" . mysql_escape($value) . "' WHERE id=".$row->id);
        }
    }
    add_alert('Succes search and replace', 'success');
}
