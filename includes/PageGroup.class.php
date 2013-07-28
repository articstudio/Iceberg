<?php

/** Include helpers taxonomy file */
require_once ICEBERG_DIR_HELPERS . 'pagegroup.php';

class PageGroup extends ObjectTaxonomy
{
    /**
     * Configuration key
     * @var string
     */
    public static $TAXONOMY_KEY = 'pagegroup';
    
    protected $name = '';
    
    
    public function __construct($name='') {
        $this->SetName($name);
    }
    
    public function GetName()
    {
        return $this->name;
    }
    
    public function SetName($name)
    {
        return $this->name = $name;
    }
}
