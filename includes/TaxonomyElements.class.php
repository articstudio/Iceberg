<?php

/** Include helpers taxonomy file */
require_once ICEBERG_DIR_HELPERS . 'taxonomy_elements.php';

interface TaxonomyElementsInterface {
    
    public function FormConfig();
    public function SetConfig($args=array());
    public function FormEdit();
    public function SetEdit();
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

abstract class TaxonomyElements
{
    protected static $NAME = '';
    protected static $_sleep_vars = array('_id', '_name', '_type', '_translatable', '_comments', '_element');
    
    
    public function merge($obj)
    {
        foreach ($obj AS $k => $v)
        {
            $this->$k = $v;
        }
        return true;
    }
    
    public function __sleep()
    {
        return self::$_sleep_vars;
    }
    
    public function __wakeup()
    {}
    
    public static function GetList()
    {
        global $__TAXONOMY_ELEMENTS;
        return $__TAXONOMY_ELEMENTS;
    }
    
    public static function Load()
    {
        $list = static::GetList();
        foreach ($list AS $value) {
            $file = ICEBERG_DIR_TAXONOMY_ELEMENTS . $value . '.class.php';
            if (is_file($file) && is_readable($file)) {
                require_once $file;
            }
        }
    }
}

