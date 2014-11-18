<?php

class TE_Relation_User extends TaxonomyElements
{
    protected static $NAME = 'User Relation';
    protected $levels = array();
    protected $multiple = false;
    
    function __construct($args=array())
    {
        $group = isset($args['group']) ? $args['group'] : array();
        $this->SetGroup($group);
        $type = isset($args['type']) ? $args['type'] : array();
        $this->SetType($type);
        $taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : array();
        $this->SetTaxonomy($taxonomy);
        parent::__construct($args);
    }
    
    public function FormConfig()
    {
        $groups = get_pagegroups();
        $types = get_pagetypes();
        $taxonomies = get_pagetaxonomies();
        ?>
        <h5><?php print_text('Multiple'); ?></h5>
        <div class="row-fluid">
            <div class="span6">
                <p>
                    <select name="multiple-<?php print $this->GetAttrName(); ?>" id="multiple-<?php print $this->GetAttrName(); ?>">
                        <option value="0"><?php print_text('No'); ?></option>
                        <option value="1" <?php print $this->IsMultiple() ? 'selected' : ''; ?>><?php print_text('Yes'); ?></option>
                    </select>
                </p>
            </div>
        </div>
        <?php
        parent::FormConfig();
    }
    
    public function SaveFormConfig($args = array())
    {
        $multiple= isset($args['multiple']) ? $args['multiple'] : get_request_p('multiple-'.$this->GetAttrName(), false);
        $this->IsMultiple($multiple);
        parent::SaveFormConfig($args);
    }
    
    public function FormEdit($page) {
        if ($this->IsMultiple())
        {
            $this->FormEditMultiple($page);
        }
        else
        {
            $this->FormEditSimple($page);
        }
        parent::FormEdit($page);
    }
    
    public function FormEditMultiple($page)
    {
        $users = get_user_list(array(
            'order' => username
        ));
        ?>
        <div class="row-fluid">
            <?php $i=1; foreach ($users AS $id => $user): ?>
            <div class="span3">
                <label class="checkbox" for="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $id; ?>">
                    <input type="checkbox" name="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>[]" value="<?php print $id; ?>" id="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $id; ?>" <?php print $this->IsRelation($page, $id) ? 'checked' : ''; ?>>
                    <?php print $user->GetUsername(); ?> (<?php print $id; ?>)
                </label>
            </div>
            <?php if ($i%4==0): ?>
        </div>
        <div class="row-fluid">
            <?php endif; ?>
            <?php $i++; endforeach; ?>
        </div>
        
        
        
        <?php
    }
    
    public function FormEditSimple($page)
    {
        $users = get_user_list(array(
            'order' => username
        ));
        ?>
        <select class="input-block-level" id="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" name="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>">
            <option value="NULL"></option>
            <?php foreach ($users AS $id => $user): ?>
            <option value="<?php print $id; ?>" <?php print $this->IsRelation($page, $id) ? 'selected' : ''; ?>>
                <?php print $user->GetUsername(); ?> (<?php print $id; ?>)
            </option>
            <?php endforeach; ?>
        </select>
        <?php
    }
    
    public function GetFormEdit($args=array())
    {
        return isset($args[$this->GetAttrName()]) ? $args[$this->GetAttrName()] : get_request_p('select-'.$this->GetAttrName().'-'.$this->GetTaxonomy(), array());
    }
    
    public function SaveFormEdit($page_id, $args=array(), $lang=null)
    {
        $ids = $this->GetFormEdit($args);
        $ids = is_array($ids) ? $ids : array($ids);
        Page::DB_DeleteParentRelation($page_id, Page::RELATION_KEY_USER_RELATED, $this->GetAttrName(), $lang);
        foreach ($ids AS $k => $parent)
        {
            Page::DB_InsertParentRelation($page_id, Page::RELATION_KEY_USER_RELATED, $this->GetAttrName(), $parent, $lang, $k);
        }
        return Page::InsertUpdateMeta($page_id, $this->GetAttrName(), $ids, $lang);
    }
    
    
    
    public function GetRelations($page)
    {
        $ids = $page->GetMeta($this->GetAttrName());
        return is_array($ids) ? $ids : array($ids);
    }
    
    public function IsRelation($page, $id)
    {
        $ids = $this->GetRelations($page);
        return in_array($id, $ids);
    }
    
    public function IsMultiple($is=null)
    {
        if (is_null($is))
        {
            return (bool)$this->multiple;
        }
        else
        {
            return $this->multiple = (bool)$is;
        }
    }
}
