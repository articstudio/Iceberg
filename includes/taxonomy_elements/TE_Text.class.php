<?php

class TE_Text extends TaxonomyElements
{
    static public $NAME = 'Text';
    public $_wysiwing = true;
    
    function __construct($_name = '') {
        //parent::__construct($_name, 'te_text');
    }
    
    public function FormConfig()
    {
        
    }
    
    public function SetConfig($args=array())
    {
        
    }
    
    public function FormEdit()
    {
        
    }
    
    public function SetEdit()
    {
        
    }
    
    public function Show()
    {
        
    }
    
    public function __sleep()
    {
        return array_merge(self::$_sleep_vars, array('_wysiwing'));
    }
    
    
    
    
    static public function CleanPrintString($str)
    {
        $str = str_replace('%','', $str);
        return $str;
    }
}
