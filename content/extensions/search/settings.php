<?php

define('SEARCH_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR);
define('SEARCH_DIR_ADMIN', SEARCH_DIR . 'admin' . DIRECTORY_SEPARATOR);
define('SEARCH_DIR_ADMIN_CONTROLLERS', SEARCH_DIR_ADMIN . 'controllers' . DIRECTORY_SEPARATOR);

define('SEARCH_META', 'search-text');

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


function clean_serach_text2($str)
{
    $str = trim($str);
    $str = @strip_tags($str);
    $str = @stripslashes($str);
    
    $str = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|caron|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $str), ENT_QUOTES, 'UTF-8');
    $string = strtolower(trim(preg_replace('~[^0-9a-z]+~i', ' ', $str), ' '));
    
    //$str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    $str = str_replace("\n", '', $str);
    $str = trim($str);
    return $str;
}
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

function get_admin_modes_configuration_search($args)
{
    list($array) = $args;
    $array['search'] = array(
        'template' => SEARCH_DIR_ADMIN . 'search.php',
        'name' => 'Search',
        'level' => 500
    );
    return array($array);
}
add_action('get_admin_modes_configuration', 'get_admin_modes_configuration_search', 10, 1);


function iceberg_backend_generate_search($args)
{
    $template = get_mode('template');
    $template = (strpos($template, SEARCH_DIR_ADMIN) !== false) ? str_replace(SEARCH_DIR_ADMIN, SEARCH_DIR_ADMIN_CONTROLLERS, $template) : '';
    if (is_file($template) && is_readable($template))
    {
        include $template;
    }
    return $args;
}
add_action('iceberg_backend_generate', 'iceberg_backend_generate_search', 10, 0);

function search_generate_page_meta($page, $fields, $lang)
{
    $search_text = array();
    foreach ($fields AS $field)
    {
        $field_value = $page->GetMeta($field, '', $lang);
        if (is_string($field_value))
        {
            array_push($search_text, $field_value);
        }
    }
    $search_text = array_filter($search_text);
    $search_text = implode(' ', $search_text);

    $search_text = clean_search_text($search_text); //var_dump($search_text);

    $args = array('value'=>  mysql_escape($search_text));
    $where = array('name'=>SEARCH_META);
    $relations = array(PageMeta::RELATION_KEY_PAGE=>$page->id);
    PageMeta::DB_InsertUpdate($args, $where, $relations, $lang);
}

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

function content_publish_insert_edit_translate_search($args)
{
    list ($pagegroup_id, $page_id, $lang) = $args;
    
    $config = get_search_config();
    $page = get_page($page_id);
    if (in_array($page->taxonomy, $config['taxonomies']))
    {
        $fields = (isset($config['fields']) && isset($config['fields'][$page->taxonomy])) ? $config['fields'][$page->taxonomy] : array();
        search_generate_page_meta($page, $fields, $lang);
    }
    
    return $args;
}
add_action('content_publish_insert', 'content_publish_insert_edit_translate_search', 999, 3);
add_action('content_publish_translate', 'content_publish_insert_edit_translate_search', 999, 3);
add_action('content_publish_edit', 'content_publish_insert_edit_translate_search', 999, 3);