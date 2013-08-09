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
    
    
    public function __construct($args=array()) {
        parent::__construct($args);
    }
}
