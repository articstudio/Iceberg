<?php

/** Include helpers taxonomy file */
require_once ICEBERG_DIR_HELPERS . 'taxonomy_elements.php';

abstract class TaxonomyElementsBase extends ObjectConfig
{
    
    /**
     * Configuration use language
     * @var boolean
     */
    public static $CONFIG_USE_LANGUAGE = false;
    
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'taxonomyelements_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'iceberg' => array(
            'TE_Input',
            'TE_Text',
            'TE_Check',
            'TE_Images',
            'TE_Geolocation',
            'TE_Relation',
            'TE_Dependence',
            //'TE_Relation_User'
        ),
        'dynamic' => array()
    );
    
    public static function GetList()
    {
        $config = static::GetConfig();
        $elements = array();
        foreach ($config['iceberg'] AS $element)
        {
            $elements[ICEBERG_DIR_TAXONOMY . $element . '.class.php'] = $element;
        }
        foreach ($config['dynamic'] AS $k => $element)
        {
            $elements[ICEBERG_DIR . $k] = $element;
        }
        return $elements;
    }
    
    public static function Load()
    {
        $list = static::GetList();
        foreach ($list AS $file => $class) {
            if (is_file($file) && is_readable($file)) {
                require_once $file;
            }
        }
    }
    
}

interface TaxonomyElementsInterface {
    
    public function FormConfig();
    public function SaveFormConfig($args=array());
    public function FormEdit($page);
    public function GetFormEdit($args=array());
    public function SaveFormEdit($page_id, $args=array(), $lang=null);
    /*
    public function SetEdit();*/
}

/**
 * Taxonomy elements
 * 
 * Taxonomy elements management
 *  
 * @package Iceberg
 * @author Marc Mascort Bou
 * @version 1.0
 */

abstract class TaxonomyElements extends TaxonomyElementsBase implements TaxonomyElementsInterface
{
    protected static $NAME = '';
    protected $attribute_name;
    protected $title;
    protected $comments;
    protected $parent_taxonomy;
    
    public function __construct($args=array()) {
        $this->SetAttrName(isset($args['name']) ? $args['name'] : uniqid());
        $this->SetComments(isset($args['comments']) ? $args['comments'] : '');
        $this->SetParentTaxonomy(isset($args['taxonomy']) ? $args['taxonomy'] : -1);
    }
    
    public function GetName()
    {
        return static::Name();
    }
    
    public function GetAttrName()
    {
        return $this->attribute_name;
    }
    
    public function SetAttrName($name)
    {
        return $this->attribute_name = $name;
    }
    
    public function GetType()
    {
        return get_called_class();
    }
    
    public function GetTitle()
    {
        return $this->title;
    }
    
    public function SetTitle($title)
    {
        return $this->title = $title;
    }
    
    public function GetTaxonomy()
    {
        return $this->parent_taxonomy;
    }
    
    public function SetParentTaxonomy($taxonomy)
    {
        return $this->parent_taxonomy = $taxonomy;
    }
    
    public function GetComments()
    {
        return $this->comments;
    }
    
    public function SetComments($comments)
    {
        return $this->comments = $comments;
    }
    
    public function Merge($obj)
    {
        foreach ($obj AS $k => $v)
        {
            $this->$k = $v;
        }
        return true;
    }
    
    public function FormConfig()
    {
        ?>
        <p class="form-group">
            <label for="title-<?php print $this->GetAttrName(); ?>" class="control-label"><?php print_text('Title'); ?></label>
            <input type="text" class="form-control" name="title-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" id="title-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" value="<?php print_html_attr($this->GetTitle()); ?>">
        </p>
        <p class="form-group">
            <label for="comments-<?php print $this->GetAttrName(); ?>" class="control-label"><?php print_text('Comments'); ?></label>
            <textarea class="form-control" name="comments-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" id="comments-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>"><?php print $this->GetComments(); ?></textarea>
        </p>
        <?php
    }
    
    public function SaveFormConfig($args=array())
    {
        $this->SetTitle(isset($args['title']) ? $args['title'] : get_request_p('title-'.$this->GetAttrName().'-'.$this->GetTaxonomy(), '', true));
        $this->SetComments(isset($args['comments']) ? $args['comments'] : get_request_p('comments-'.$this->GetAttrName().'-'.$this->GetTaxonomy(), '', true));
    }
    
    public function FormEdit($page)
    {
        ?>
        <p class="help-block"><?php print $this->GetComments(); ?></p>
        <?php
    }
    
    
    public static function Name()
    {
        return static::$NAME;
    }
}
