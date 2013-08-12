<?php

/** Include helpers taxonomy file */
require_once ICEBERG_DIR_HELPERS . 'taxonomy_elements.php';

abstract class TaxonomyElementsBase extends ObjectConfig
{
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
            'TE_Text',
            /*
            'te_input',
            'te_images',
            'te_files',
            'te_links',
            'te_geolocation',
            'te_videos',
            'te_relationship',
            'te_date'
            'te_list'*/
        ),
        'dynamic' => array()
    );
    
    public static function GetList()
    {
        $config = static::GetConfig();
        $elements = array();
        foreach ($config['iceberg'] AS $element)
        {
            $elements[ICEBERG_DIR_TAXONOMY_ELEMENTS . $element . '.class.php'] = $element;
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
    public function SaveFormEdit($page, $args=array());
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
    protected $comments;
    
    public function __construct($args=array()) {
        $this->SetAttrName(isset($args['name']) ? $args['name'] : uniqid());
        $this->SetComments(isset($args['comments']) ? $args['comments'] : '');
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
        <p>
            <label for="comments-<?php print $this->GetAttrName(); ?>"><?php print_text('Comments'); ?></label>
            <textarea class="input-block-level" name="comments-<?php print $this->GetAttrName(); ?>" id="comments-<?php print $this->GetAttrName(); ?>"><?php print $this->GetComments(); ?></textarea>
        </p>
        <?php
    }
    
    public function SaveFormConfig($args=array())
    {
        $this->SetComments(isset($args['comments']) ? $args['comments'] : get_request_p('comments-'.$this->GetAttrName(), '', true));
    }
    
    public function FormEdit($page)
    {
        ?>
        <p><small><?php print $this->GetComments(); ?></small></p>
        <?php
    }
    
    
    public static function Name()
    {
        return static::$NAME;
    }
}
