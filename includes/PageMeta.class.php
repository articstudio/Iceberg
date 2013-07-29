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
        'Page' => array(
            'name' => 'page-meta',
            'force' => true,
            'function' => 'get_page_id',
            'language' => true
        )
    );
    
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
    
    
    
    public static function Normalize($metas)
    {
        $metas[static::META_TITLE] = isset($metas[static::META_TITLE]) ? $metas[static::META_TITLE] : '';
        $metas[static::META_PERMALINK] = isset($metas[static::META_PERMALINK]) ? $metas[static::META_PERMALINK] : '';
        $metas[static::META_TEXT] = isset($metas[static::META_TEXT]) ? $metas[static::META_TEXT] : '';
        $metas[static::META_IMAGE] = isset($metas[static::META_IMAGE]) ? $metas[static::META_IMAGE] : '';
        return $metas;
    }
    
}