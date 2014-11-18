<?php

class TE_Images extends TaxonomyElements
{
    protected static $NAME = 'Images';
    protected $limit = 0;
    protected $alternative_text = true;
    
    function __construct($args=array()) {
        $this->limit = isset($args['limit']) ? $args['limit'] : 0;
        $this->alternative_text = isset($args['alternative_text']) ? $args['alternative_text'] : true;
        parent::__construct($args);
    }
    
    public function UseAlternativeText($use=null)
    {
        if (is_null($use))
        {
            return $this->alternative_text;
        }
        else
        {
            return $this->alternative_text = $use;
        }
    }
    
    public function FormConfig()
    {
        parent::FormConfig();
        ?>
        <p class="radio">
            <label for="alternative-text-<?php print $this->GetAttrName(); ?>" class="checkbox">
                <input type="checkbox" name="alternative-text-<?php print $this->GetAttrName(); ?>" id="alternative-text-<?php print $this->GetAttrName(); ?>" value="1" <?php print $this->UseAlternativeText() ? 'checked' : ''; ?> /> <?php print_text('Use alternative text'); ?>
            </label>
        </p>
        <p class="form-group">
            <label for="limit-<?php print $this->GetAttrName(); ?>" class="control-label"><?php print_text('Limit'); ?></label>
            <input type="text" class="form-control" name="limit-<?php print $this->GetAttrName(); ?>" id="limit-<?php print $this->GetAttrName(); ?>" value="<?php print_html_attr($this->limit); ?>" />
        </p>
        <?php
    }
    
    public function SaveFormConfig($args = array())
    {
        $this->UseAlternativeText(isset($args['alternative_text']) ? $args['alternative_text'] : get_request_p('alternative-text-'.$this->GetAttrName(), false));
        $this->limit = isset($args['limit']) ? $args['limit'] : get_request_p('limit-'.$this->GetAttrName(), false);
        parent::SaveFormConfig($args);
    }
    
    public function FormEdit($page) {
        $images = $this->GetImages($page);
        $images = is_array($images) ? $images : array();
        ?>
        <div class="form-group">
            <label class="control-label"><?php echo $this->GetTitle(); ?></label><br>
            <button class="btn btn-default" data-elfinder-callback="callbackGalleryFile" data-elfinder-callback-attr="images-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>"><span class="glyphicon glyphicon-picture"></span> <?php print_text('Browse'); ?></button>
            <?php if ((int)$this->limit > 0): ?>
            <span class="help-inline">(<?php print_text('Limit'); ?>: <?php echo (int)$this->limit; ?>)</span>
            <?php endif; ?>
            <?php parent::FormEdit($page); ?>
            <ul id="images-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" class="well-gallery" data-images-limit="<?php print $this->limit; ?>" data-sortable="revert">
            <?php foreach ($images AS $image): ?>
                <li class="well widget">
                    <div class="btn-toolbar header"><a href="#" class="btn btn-danger btn-xs" btn-action="remove"><span class="glyphicon glyphicon-trash"></span></a></div>
                    <span class="thumbnail"><img src="<?php print_html_attr($image['image']); ?>"></span>
                    <input type="hidden" name="images-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>[]" value="<?php print_html_attr($image['image']); ?>">
                    <input type="text" class="input-block-level" name="images-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-alt[]" value="<?php print_html_attr($image['alt']); ?>">
                </li>
                <?php endforeach; ?>
            </ul>
            
        </div>
        
        <?php
        
    }
    
    public function GetFormEdit($args=array())
    {
        $list = array();
        $images = isset($args[$this->GetAttrName()]) ? $args[$this->GetAttrName()] : get_request_p('images-'.$this->GetAttrName().'-'.$this->GetTaxonomy(), array(), true);
        $alts = isset($args[$this->GetAttrName() . '-alt']) ? $args[$this->GetAttrName() . '-alt'] : get_request_p('images-'.$this->GetAttrName().'-'.$this->GetTaxonomy().'-alt', array(), true);
        foreach ($images AS $k => $image)
        {
            $list[$k] = array(
                'image' => $image,
                'alt' => isset($alts[$k]) ? $alts[$k] : ''
            );
        }
        return $list;
    }
    
    public function SaveFormEdit($page_id, $args=array(), $lang=null)
    {
        return Page::InsertUpdateMeta($page_id, $this->GetAttrName(), $this->GetFormEdit($args), $lang);
    }
    
    public function GetImages($page)
    {
        return $page->GetMeta($this->GetAttrName());
    }
}
