<?php

/** Include helpers menubar file */
require_once ICEBERG_DIR_HELPERS . 'menubar.php';

/**
 * Maintenace
 * 
 * Manage Iceberg maintenance
 *  
 * @package Iceberg
 * @subpackage Menubar
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0 
 */
class Menubar extends ObjectConfig
{
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'menubar_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'links' => array(),
        'separator' => '&middot;',
        'show_separator' => false,
        'id' => 'menubar-nav',
        'css_item_active' => 'active',
        'css_nav_class' => ''
    );
    
    public static function Save($config)
    {
        return static::SaveConfig($config);
    }
    
    public static function SaveLinks($links, $lang=null)
    {
        return static::SaveConfigValue('links', $links, $lang);
    }
    
    
    public static function GetLinks($formatted=false)
    {
        $links = static::GetConfigValue('links', array());
        if ($formatted)
        {
            foreach ($links AS $k => $link)
            {
                if ($link['type'] === 'page')
                {
                    $page = get_page($link['page']);
                    $links[$k] = array(
                        'title' => !empty($link['name']) ? $link['name'] : $page->GetTitle(),
                        'url' => $page->GetLink()
                    );
                }
                else
                {
                    $links[$k] = array(
                        'title' => $link['name'],
                        'url' => $link['url']
                    );
                }
            }
        }
        return $links;
    }
}




