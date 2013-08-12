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
        ?>
        <p>
            <label for="alternative-text-<?php print $this->GetAttrName(); ?>" class="checkbox">
                <input type="checkbox" name="alternative-text-<?php print $this->GetAttrName(); ?>" id="alternative-text-<?php print $this->GetAttrName(); ?>" value="1" <?php print $this->UseAlternativeText() ? 'checked' : ''; ?> /> <?php print_text('Use alternative text'); ?>
            </label>
        </p>
        <p>
            <label for="limit-<?php print $this->GetAttrName(); ?>"></label>
            <input type="text" class="input-block-level" name="limit-<?php print $this->GetAttrName(); ?>" id="limit-<?php print $this->GetAttrName(); ?>" value="<?php print_html_attr($this->limit); ?>" />
        </p>
        <?php
        parent::FormConfig();
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
        <p>
            <button type="button" id="images-button-<?php print $this->GetAttrName(); ?>" class="btn btn-inverse" data-images="images-<?php print $this->GetAttrName(); ?>"><?php print_text('Browse'); ?></button>
        </p>
        <ul id="images-<?php print $this->GetAttrName(); ?>" class="inline gallery" data-images-limit="<?php print $this->limit; ?>" data-sortable="revert">
            <?php foreach ($images AS $image): ?>
            <li class="widget">
                <div class="btn-toolbar header"><a href="#" class="btn btn-danger btn-mini" btn-action="remove"><i class="icon-trash"></i></a></div>
                <img src="<?php print_html_attr($image['image']); ?>" />
                <input type="hidden" name="images-<?php print $this->GetAttrName(); ?>[]" value="<?php print_html_attr($image['image']); ?>" />
                <input type="text" class="input-block-level" name="'images-<?php print $this->GetAttrName(); ?>-alt[]" value="<?php print_html_attr($image['alt']); ?>" />
            </li>
            <?php endforeach; ?>
        </ul>
        <?php
        parent::FormEdit($page);
    }
    
    public function GetFormEdit($args=array())
    {
        $list = array();
        $images = isset($args[$this->GetAttrName()]) ? $args[$this->GetAttrName()] : get_request_p('images-'.$this->GetAttrName(), array(), true);
        $alts = isset($args[$this->GetAttrName()]) ? $args[$this->GetAttrName()] : get_request_p('images-'.$this->GetAttrName().'-alt', array(), true);
        foreach ($images AS $k => $image)
        {
            $list[$k] = array(
                'image' => $image,
                'alt' => isset($alts[$k]) ? $alts[$k] : ''
            );
        }
        return $list;
    }
    
    public function SaveFormEdit($page, $args=array())
    {
        $images = $this->GetFormEdit($args);
        $page->SaveMeta($this->GetAttrName(), $images);
    }
    
    public function GetImages($page)
    {
        return $page->GetMeta($this->GetAttrName());
    }
}
