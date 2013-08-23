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
    protected $childs_allowed;
    protected $templates;
    protected $elements;
    
    public function __construct($args=array()) {
        $this->UsePermalink(isset($args['permalink']) ? $args['permalink'] : false);
        $this->UseText(isset($args['text']) ? $args['text'] : false);
        $this->UseImage(isset($args['image']) ? $args['image'] : false);
        $this->ChildsAllowed(isset($args['childs']) ? $args['childs'] : false);
        $this->SetTemplates(isset($args['templates']) ? $args['templates'] : array());
        $this->SetElements(isset($args['elements']) ? $args['elements'] : array());
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
    
    public function ChildsAllowed($allowed=null)
    {
        if (is_null($allowed))
        {
            return $this->childs_allowed;
        }
        else
        {
            return $this->childs_allowed = $allowed;
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
    
    public function GetElements()
    {
        return $this->elements;
    }
    
    public function SetElements($elements)
    {
        $buffer = array();
        if (isset($elements) && is_array($elements))
        {
            foreach ($elements AS $element)
            {
                if (!empty($element['name']) && !empty($element['type']) && class_exists($element['type']))
                {
                    if (isset($this->elements[$element['name']]) && is_a($this->elements[$element['name']], $element['type']))
                    {
                        $buffer[$element['name']] = $this->elements[$element['name']];
                    }
                    else
                    {
                        $class = $element['type'];
                        $args_element = array(
                            'name' => $element['name']
                        );
                        $element = new $class($args_element);
                        $buffer[$element->GetAttrName()] = $element;
                    }
                }
            }
        }
        return $this->elements = $buffer;
    }
    
    public function Configure() {
        foreach ($this->elements AS $element)
        {
            $element->SaveFormConfig();
        }
        parent::Configure();
    }
}
