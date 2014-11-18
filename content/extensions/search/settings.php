<?php

define('SEARCH_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR);
define('SEARCH_DIR_ADMIN', SEARCH_DIR . 'admin' . DIRECTORY_SEPARATOR);
define('SEARCH_DIR_ADMIN_CONTROLLERS', SEARCH_DIR_ADMIN . 'controllers' . DIRECTORY_SEPARATOR);

define('SEARCH_META', 'search-text');

/* CONFIG */
function select_search_config()
{
    return select_config('search_config', array());
}
select_search_config();
function get_search_config()
{
    return get_config('search_config', array());
}
function save_search_config($config)
{
    return save_config('search_config', $config);
}

/* ADMIN */
function search_get_modes_configuration($arr)
{
    $arr['search'] = array(
        'template' => SEARCH_DIR_ADMIN . 'mode_configuration_search.php',
        'name' => _T('Search')
    );
    return $arr;
}
add_filter('get_modes_configuration', 'search_get_modes_configuration', 10 ,1);


/* GENERATE METAS */
function clean_search_text($string){ 
    $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'; 
    $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr'; 
    $string = @strip_tags($string);
    $string = html_entity_decode($string);
    $string = utf8_decode($string);     
    $string = strtr($string, utf8_decode($a), $b); 
    $string = strtolower($string); 
    $string = str_replace("\n", '', $string);
    $string = trim($string);
    return utf8_encode($string); 
}

function search_generate_page_meta($page, $fields, $lang=null)
{
    $page_taxonomy = get_pagetaxonomy($page->taxonomy);
    $page_elements = $page_taxonomy->GetElements();
    
    $search_text = array();
    foreach ($fields AS $field)
    {
        $field_value = $page->GetMeta($field, '', $lang);
        
        $field_value = apply_filters('search_generate_field_value', $field_value, $field, (isset($page_elements[$field]) ? $page_elements[$field] : false), $lang);
        
        if (is_string($field_value))
        {
            $search_text[] = $field_value;
        }
    }
    
    $dependences = Page::DB_SelectParentRelation($page->id, Page::RELATION_KEY_PAGE_DEPENDENCE);
    foreach ($dependences AS $dependence)
    {
        if (!$dependence->attribute || empty($dependence->attribute) || !in_array($dependence->attribute, $fields))
        {
            $dependence_page = get_page($dependence->pid, $lang);
            $search_text[] = $dependence_page->GetTitle();
            $search_text[] = $dependence_page->GetTitle();
        }
    }
    
    
    $search_text = array_filter($search_text);
    $search_text = implode(' ', $search_text);

    $search_text = clean_search_text($search_text); var_dump($search_text);
    
    return Page::InsertUpdateMeta($page->id, SEARCH_META, mysql_escape($search_text));
}

function search_generate_field_value_defaults($field_value, $field, $element, $lang)
{
    if ($element)
    {
        $element_type = get_class($element);
        
        if ($element_type === 'TE_Images')
        {
            $buffer = array();
            $field_value = is_array($field_value) ? $field_value : array();
            foreach ($field_value AS $image)
            {
                $buffer[] = $image['image'] . ' ' . $image['alt'];
            }
            return implode(' ', $buffer);
        }
        else if ($element_type === 'TE_Text' || $element_type === 'TE_Input')
        {
            return str_replace(array("\n","\t","\r",'  '), ' ', strip_tags($field_value));
        }
        else if ($element_type === 'TE_Dependence' || $element_type === 'TE_Relation')
        {
            $buffer = array();
            $field_value = is_array($field_value) ? $field_value : array();
            foreach ($field_value AS $item_id)
            {
                $item_page = get_page($item_id, $lang);
                $buffer[] = $item_page->GetTitle();
                if ($element_type === 'TE_Dependence')
                {
                    $buffer[] = $item_page->GetTitle();
                }
            }
            return implode(' ', $buffer);
        }
        
    }
    return $field_value;
}
add_filter('search_generate_field_value', 'search_generate_field_value_defaults', 10, 4);

function search_page_edit_generate_metas($page_id, $pagegroup_id)
{
    $config = get_search_config();
    $page = get_page($page_id);
    if (in_array($page->taxonomy, $config['taxonomies']))
    {
        $fields = (isset($config['fields']) && isset($config['fields'][$page->taxonomy])) ? $config['fields'][$page->taxonomy] : array();
        search_generate_page_meta($page, $fields);
    }
}
add_action('content_publish_insert', 'search_page_edit_generate_metas', 10, 2);
add_action('content_publish_update', 'search_page_edit_generate_metas', 10, 2);

/* SEARCH */
function get_search_ids($string, $min_score=0, $lang=null)
{
    $lang = is_null($lang) ? get_lang() : $lang;
    $result = array();
    $string = clean_search_text($string);
    if (strlen($string) > 2)
    {
        $string = '+' . implode(', +', explode(' ', $string));
        $sql = "SELECT t0.id AS mid, MAX(CASE WHEN t1.name='" . PageMeta::RELATION_KEY_PAGE . "'  AND t1.language='" . $lang . "' THEN t1.pid END) AS id, MATCH(t0.value) AGAINST('" . $string . "') AS score FROM " . PageMeta::DB_GetTableName() . " AS t0 INNER JOIN iceberg_relations AS t1 ON t1.name='" . PageMeta::RELATION_KEY_PAGE . "' AND t1.cid=t0.id  AND t1.language='" . $lang . "' WHERE t0.name='" . SEARCH_META . "' AND MATCH(t0.value) AGAINST('" . $string . "') GROUP BY pid HAVING score > " . $min_score . " ORDER BY score DESC ";
        db_query($sql);
        if (db_numrows() > 0)
        {
            while ($row = db_next())
            {
                $result[$row->id] = $row;
                //$result[] = abs($row->id);
            }
        }
    }
    else
    {
        $sql = "SELECT t0.id AS mid, MAX(CASE WHEN t1.name='" . PageMeta::RELATION_KEY_PAGE . "'  AND t1.language='" . $lang . "' THEN t1.pid END) AS id FROM " . PageMeta::DB_GetTableName() . " AS t0 INNER JOIN iceberg_relations AS t1 ON t1.name='" . PageMeta::RELATION_KEY_PAGE . "' AND t1.cid=t0.id  AND t1.language='" . $lang . "' WHERE t0.name='" . SEARCH_META . "' AND t0.value LIKE '%" . $string . "%' GROUP BY pid";
        db_query($sql);
        if (db_numrows() > 0)
        {
            while ($row = db_next())
            {
                $result[$row->id] = $row;
                $result[$row->id]->score = 1;
                //$result[] = abs($row->id);
            }
        }
    }
    return $result;
}

/* ORDER */
function order_search_by_score(&$results, $scores)
{
    foreach ($results AS $k => $v)
    {
        $results[$k]->search_score = (isset($scores[$k])) ? $scores[$k]->score : 0;
    }
    
    uasort($results, 'order_search_by_score_compare');
}
function order_search_by_score_compare($a, $b)
{
    //echo $a->search_score . ' / ' . $b->search_score . "\n";
    return ($a->search_score == $b->search_score) ? 0 : ( ($a->search_score < $b->search_score) ? 1 : -1);
}

/*
function search_get_page_meta($page_id, $lang=null)
{
    $lang = is_null($lang) ? get_lang() : $lang;
    $fields = array('value');
    $where = array('name'=>SEARCH_META);
    $orderby = $limit = array();
    $relations = array(PageMeta::RELATION_KEY_PAGE=>$page_id);
    $results = PageMeta::DB_Select($fields, $where, $orderby, $limit, $relations, $lang);
    if (!empty($results))
    {
        $result = reset($results);
        return $result->value;
    }
    return '';
}

*/