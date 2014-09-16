<?php

class TE_Input extends TaxonomyElements
{
    protected static $NAME = 'Input';
    protected $limit = 0;
    
    function __construct($args=array())
    {
        $this->limit = isset($args['limit']) ? $args['limit'] : 0;
        parent::__construct($args);
    }
    
    public function FormConfig()
    {
        ?>
        <p>
            <label for="limit-<?php print $this->GetAttrName(); ?>">Limit</label>
            <input type="text" class="input-block-level" name="limit-<?php print $this->GetAttrName(); ?>" id="limit-<?php print $this->GetAttrName(); ?>" value="<?php print_html_attr($this->limit); ?>" />
        </p>
        <?php
        parent::FormConfig();
    }
    
    public function SaveFormConfig($args = array())
    {
        $this->limit = isset($args['limit']) ? $args['limit'] : get_request_p('limit-'.$this->GetAttrName(), false);
        parent::SaveFormConfig($args);
    }
    
    public function FormEdit($page) {
        $limit = (int)$this->limit;
        ?>
        <input type="text" class="input-block-level" name="input-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" id="input-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" value="<?php print_html_attr($page->GetMeta($this->GetAttrName())); ?>" <?php echo $limit>0 ? 'maxlength="'.$limit.'"' : ''; ?> />
        <?php
        parent::FormEdit($page);
    }
    
    public function GetFormEdit($args=array())
    {
        return isset($args[$this->GetAttrName()]) ? $args[$this->GetAttrName()] : get_request_p('input-'.$this->GetAttrName().'-'.$this->GetTaxonomy(), '', true);
    }
    
    public function SaveFormEdit($page_id, $args=array(), $lang=null)
    {
        return Page::InsertUpdateMeta($page_id, $this->GetAttrName(), $this->GetFormEdit($args), $lang);
    }
    
    
    
    static public function CleanPrintString($str)
    {
        $str = str_replace('%','', $str);
        return $str;
    }
}
