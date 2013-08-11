<?php

/** Include helpers taxonomy file */
require_once ICEBERG_DIR_HELPERS . 'pagetaxonomy.php';

class PageTaxonomy extends ObjectTaxonomy
{
    /**
     * Configuration key
     * @var string
     */
    public static $TAXONOMY_KEY = 'pagetaxonomy';
    
    protected $permalink;
    protected $text;
    protected $image;
    protected $templates;
    protected $elements;
    
    public function __construct($args=array()) {
        $this->permalink = isset($args['permalink']) ? $args['permalink'] : true;
        $this->text = isset($args['text']) ? $args['text'] : true;
        $this->image = isset($args['image']) ? $args['image'] : true;
        $this->templates = isset($args['templates']) ? $args['templates'] : array();
        $this->templates = is_array($this->templates) ? $this->templates : explode(',', $this->templates);
        parent::__construct($args);
    }
    
    public function UsePermalink($use=null)
    {
        if (is_null($use))
        {
            return $this->permalink;
        }
        else
        {
            return $this->permalink = $use;
        }
    }
    
    public function UseText($use=null)
    {
        if (is_null($use))
        {
            return $this->text;
        }
        else
        {
            return $this->text = $use;
        }
    }
    
    public function UseImage($use=null)
    {
        if (is_null($use))
        {
            return $this->image;
        }
        else
        {
            return $this->image = $use;
        }
    }
    
    public function SetTemplates($templates=array())
    {
        $this->templates = is_array($templates) ? $templates : explode(',', $templates);
    }
    
    public function GetTemplates()
    {
        return $this->templates;
    }
    
    public function AcceptedTemplate($id)
    {
        return in_array($id, $this->templates);
    }
}
