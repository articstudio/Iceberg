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
    protected $permalink_comments;
    protected $text;
    protected $text_comments;
    protected $image;
    protected $image_comments;
    protected $childs_allowed;
    protected $user_relation;
    protected $user_role;
    protected $templates;
    protected $elements;
    
    public function __construct($args=array()) {
        $this->UsePermalink(isset($args['permalink']) ? $args['permalink'] : false);
        $this->PermalinkComments(isset($args['permalink-comments']) ? $args['permalink-comments'] : '');
        $this->UseText(isset($args['text']) ? $args['text'] : false);
        $this->TextComments(isset($args['text-comments']) ? $args['text-comments'] : '');
        $this->UseImage(isset($args['image']) ? $args['image'] : false);
        $this->ImageComments(isset($args['image-comments']) ? $args['image-comments'] : '');
        $this->ChildsAllowed(isset($args['childs']) ? $args['childs'] : false);
        $this->UserRelation(isset($args['user_relation']) ? $args['user_relation'] : false);
        $this->UserRole(isset($args['user_role']) ? $args['user_role'] : -1);
        $this->SetTemplates(isset($args['templates']) ? $args['templates'] : array());
        $this->SetElements(isset($args['elements']) ? $args['elements'] : array());
        parent::__construct($args);
    }
    
    public function UsePermalink($use=null)
    {
        if (is_null($use))
        {
            return (bool)$this->permalink;
        }
        else
        {
            return $this->permalink = $use;
        }
    }
    
    public function PermalinkComments($comments=null)
    {
        if (is_null($comments))
        {
            return $this->permalink_comments;
        }
        else
        {
            return $this->permalink_comments = $comments;
        }
    }
    
    public function UseText($use=null)
    {
        if (is_null($use))
        {
            return (bool)$this->text;
        }
        else
        {
            return $this->text = $use;
        }
    }
    
    public function TextComments($comments=null)
    {
        if (is_null($comments))
        {
            return $this->text_comments;
        }
        else
        {
            return $this->text_comments = $comments;
        }
    }
    
    public function UseImage($use=null)
    {
        if (is_null($use))
        {
            return (bool)$this->image;
        }
        else
        {
            return $this->image = $use;
        }
    }
    
    public function ImageComments($comments=null)
    {
        if (is_null($comments))
        {
            return $this->image_comments;
        }
        else
        {
            return $this->image_comments = $comments;
        }
    }
    
    public function ChildsAllowed($allowed=null)
    {
        if (is_null($allowed))
        {
            return (bool)$this->childs_allowed;
        }
        else
        {
            return $this->childs_allowed = $allowed;
        }
    }
    
    public function UserRelation($relation=null)
    {
        if (is_null($relation))
        {
            return (bool)$this->user_relation;
        }
        else
        {
            return $this->user_relation = $relation;
        }
    }
    
    public function UserRole($role=null)
    {
        if (is_null($role))
        {
            return (int)$this->user_role;
        }
        else
        {
            return $this->user_role = $role;
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
        foreach ($this->elements AS $k => $element)
        {
            $element->SetParentTaxonomy($this->GetID());
            $this->elements[$k] = $element;
        }
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
                            'name' => $element['name'],
                            'taxonomy' => $this->GetID()
                        );
                        $element = new $class($args_element);
                        $buffer[$element->GetAttrName()] = $element;
                    }
                }
            }
        }
        return $this->elements = $buffer;
    }
    
    public function Configure($args=array())
    {
        foreach ($this->elements AS $element)
        {
            $element->SetParentTaxonomy($this->GetID());
            $name = $element->GetAttrName();
            $element->SaveFormConfig(isset($args[$name]) ? $args[$name] : array());
        }
        parent::Configure($args);
    }
}
