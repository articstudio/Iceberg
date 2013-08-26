<?php

/** Include helpers widget file */
require_once ICEBERG_DIR_HELPERS . 'widget.php';

/**
 * Widget Base
 * 
 * Widget management
 *  
 * @package Widget
 * @author Marc Mascort Bou
 * @version 1.0
 */
class WidgetBase extends ObjectConfig
{
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'widget_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'default' => 'en_US',
        'languages' => array()
    );
}

class Widget extends WidgetBase
{
    
}
