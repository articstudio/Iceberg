<?php


class PageMeta extends ObjectDBRelations
{
    /**
     * DB table name
     * @var string
     */
    public static $DB_TABLE = 'ICEBERG_DB_PAGES_METAS';
    
    /**
     * List of fields
     * @var array
     */
    public static $DB_FIELDS = array(
        'name' => array(
            'name' => 'META NAME',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => array(),
            'index' => true
        ),
        'value' => array(
            'name' => 'META VALUE',
            'type' => 'LONGTEXT',
            'length' => null,
            'flags' => array(
                'NOT NULL'
            ),
            'index' => false
        )
    );
    
    /**
     * Parents relation
     * @var array 
     */
    public static $DB_PARENTS = array(
        'page2meta' => array(
            'object' => 'Page',
            'force' => true,
            'function' => 'get_page_id',
            'language' => true
        )
    );
    
    const RELATION_KEY_PAGE = 'page2meta';
    
    /**
     * Title META
     */
    const META_TITLE = 'title';
    
    /**
     * Permalink META
     */
    const META_PERMALINK = 'permalink';
    
    /**
     * Text META
     */
    const META_TEXT = 'text';
    
    /**
     * Image META
     */
    const META_IMAGE = 'image';
    
    /**
     * Image META
     */
    const META_TEMPLATE = 'template';
    
    
    
    public static function GetDefaultMetas()
    {
        return array(
            static::META_TITLE,
            static::META_PERMALINK,
            static::META_TEXT,
            static::META_IMAGE,
            static::META_TEMPLATE
        );
    }
    
    
    public static function Normalize($metas)
    {
        $metas[static::META_TITLE] = isset($metas[static::META_TITLE]) ? $metas[static::META_TITLE] : '';
        $metas[static::META_PERMALINK] = isset($metas[static::META_PERMALINK]) ? $metas[static::META_PERMALINK] : '';
        $metas[static::META_TEXT] = isset($metas[static::META_TEXT]) ? $metas[static::META_TEXT] : '';
        $metas[static::META_IMAGE] = isset($metas[static::META_IMAGE]) ? $metas[static::META_IMAGE] : '';
        $metas[static::META_TEMPLATE] = isset($metas[static::META_TEMPLATE]) ? $metas[static::META_TEMPLATE] : '';
        return $metas;
    }
    
    
    public static function GetParentID($name, $value, $lang)
    {
        $fields = array(
            PageMeta::RELATION_KEY_PAGE => array(
                array(DBRelation::GetParentField(), 'pid')
            )
        );
        $where = array(
            'name' => $name,
            'value' => $value
        );
        $orderby = array();
        $limit = array();
        $relations = array(
            PageMeta::RELATION_KEY_PAGE => static::DB_RELATION_NOT_NULL
        );
        $metas = static::DB_Select($fields, $where, $orderby, $limit, $relations, $lang);
        if (is_array($metas) && !empty($metas))
        {
            foreach ($metas AS $meta)
            {
                if (!is_null($meta->pid))
                {
                    return $meta->pid;
                }
            }
        }
        return -1;
    }
}