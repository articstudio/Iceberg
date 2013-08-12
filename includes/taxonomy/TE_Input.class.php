<?php

class TE_Input extends TaxonomyElements
{
    protected static $NAME = 'Input';
    
    function __construct($args=array())
    {
        parent::__construct($args);
    }
    
    public function FormConfig()
    {
        parent::FormConfig();
    }
    
    public function SaveFormConfig($args = array())
    {
        parent::SaveFormConfig($args);
    }
    
    public function FormEdit($page) {
        ?>
        <input type="text" class="input-block-level" name="input-<?php print $this->GetAttrName(); ?>" id="input-<?php print $this->GetAttrName(); ?>" value="<?php print_html_attr($page->GetMeta($this->GetAttrName())); ?>" />
        <?php
        parent::FormEdit($page);
    }
    
    public function GetFormEdit($args=array())
    {
        return isset($args[$this->GetAttrName()]) ? $args[$this->GetAttrName()] : get_request_p('input-'.$this->GetAttrName(), '', true);
    }
    
    public function SaveFormEdit($page, $args=array())
    {
        $page->SaveMeta($this->GetAttrName(), $this->GetFormEdit($args));
    }
    
    
    
    static public function CleanPrintString($str)
    {
        $str = str_replace('%','', $str);
        return $str;
    }
}
