<?php

/** Include helpers taxonomy file */
require_once ICEBERG_DIR_HELPERS . 'pagetype.php';

class PageType extends ObjectTaxonomy
{
    /**
     * Configuration key
     * @var string
     */
    public static $TAXONOMY_KEY = 'pagetype';
    
    protected $taxonomy;
    
    public function __construct($args=array()) {
        $this->taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : array();
        $this->taxonomy = is_array($this->taxonomy) ? $this->taxonomy : explode(',', $this->taxonomy);
        parent::__construct($args);
    }
    
    public function GetTaxonomy()
    {
        return $this->taxonomy;
    }
    
    public function SetTaxonomy($taxonomy)
    {
        $this->taxonomy = is_array($taxonomy) ? $taxonomy : explode(',', $taxonomy);
    }
    
    public function AcceptedTaxonomy($id)
    {
        return in_array($id, $this->taxonomy);
    }
    
    public function GetTaxonomyObject()
    {
        $args = array(
            'id' => $this->GetTaxonomy()
        );
        return PageTaxonomy::GetList($args);
    }
    
    public function GetTemplates($args=array())
    {
        $templates = array();
        $taxonomies = $this->GetTaxonomyObject($args);
        foreach ($taxonomies AS $taxonomy)
        {
            $buffer = $taxonomy->GetTemplates();
            $templates = array_merge($templates, $buffer);
        }
        return $templates;
    }
}
