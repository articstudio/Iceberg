<?php

class TE_Relation extends TaxonomyElements
{
    protected static $NAME = 'Relation';
    protected $group = array();
    protected $type = array();
    protected $taxonomy = array();
    
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
        <h5><?php print_text('Groups'); ?></h5>
        <div class="row-fluid">
            <?php $i=1; foreach ($groups AS $id => $group): ?>
            <div class="span3">
                <label class="checkbox" for="cgroup-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>">
                    <input type="checkbox" name="group-<?php print $this->GetAttrName(); ?>[]" value="<?php print $id; ?>" id="cgroup-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>" <?php print $this->AcceptedGroup($id) ? 'checked' : ''; ?>>
                    <?php print $group->GetName(); ?>
                </label>
            </div>
            <?php if ($i%4==0): ?>
        </div>
        <div class="row-fluid">
            <?php endif; ?>
            <?php $i++; endforeach; ?>
        </div>
        
        <h5><?php print_text('Types'); ?></h5>
        <div class="row-fluid">
            <?php $i=1; foreach ($types AS $id => $type): ?>
            <div class="span3">
                <label class="checkbox" for="ctype-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>">
                    <input type="checkbox" name="type-<?php print $this->GetAttrName(); ?>[]" value="<?php print $id; ?>" id="ctype-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>" <?php print $this->AcceptedType($id) ? 'checked' : ''; ?>>
                    <?php print $type->GetName(); ?>
                </label>
            </div>
            <?php if ($i%4==0): ?>
        </div>
        <div class="row-fluid">
            <?php endif; ?>
            <?php $i++; endforeach; ?>
        </div>
        
        <h5><?php print_text('Taxonomies'); ?></h5>
        <div class="row-fluid">
            <?php $i=1; foreach ($taxonomies AS $id => $taxonomy): ?>
            <div class="span3">
                <label class="checkbox" for="ctax-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>">
                    <input type="checkbox" name="taxonomy-<?php print $this->GetAttrName(); ?>[]" value="<?php print $id; ?>" id="ctax-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>" <?php print $this->AcceptedTaxonomy($id) ? 'checked' : ''; ?>>
                    <?php print $taxonomy->GetName(); ?>
                </label>
            </div>
            <?php if ($i%4==0): ?>
        </div>
        <div class="row-fluid">
            <?php endif; ?>
            <?php $i++; endforeach; ?>
        </div>
        <?php
        parent::FormConfig();
    }
    
    public function SaveFormConfig($args = array())
    {
        $group = isset($args['group']) ? $args['group'] : get_request_p('group-'.$this->GetAttrName(), array());
        $this->SetGroup($group);
        $type = isset($args['type']) ? $args['type'] : get_request_p('type-'.$this->GetAttrName(), array());
        $this->SetType($type);
        $taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : get_request_p('taxonomy-'.$this->GetAttrName(), array());
        $this->SetTaxonomy($taxonomy);
        parent::SaveFormConfig($args);
    }
    
    public function FormEdit($page) {
        $pages = get_pages(array(
            'group' => $this->group,
            'type' => $this->type,
            'taxonomy' => $this->taxonomy
        ));
        $page_id = $page->GetMeta($this->GetAttrName());
        ?>
        <select class="input-block-level" id="select-<?php print $this->GetAttrName(); ?>" name="select-<?php print $this->GetAttrName(); ?>">
            <option value="NULL"></option>
            <?php foreach ($pages AS $id => $page): ?>
            <?php if ($page->id != $id): ?>
            <option value="<?php print $id; ?>" <?php print $page_id == $id ? 'selected' : ''; ?>><?php print $page->GetTitle(); ?></option>
            <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <?php
        parent::FormEdit($page);
    }
    
    public function GetFormEdit($args=array())
    {
        return isset($args[$this->GetAttrName()]) ? $args[$this->GetAttrName()] : get_request_p('select-'.$this->GetAttrName(), null);
    }
    
    public function SaveFormEdit($page, $args=array())
    {
        $page->SaveMeta($this->GetAttrName(), $this->GetFormEdit($args));
    }
    
    
    
    
    public function AcceptedGroup($id)
    {
        return in_array($id, $this->group);
    }
    
    public function SetGroup($group)
    {
        $this->group = is_array($group) ? $group : explode(',', $group);
    }
    
    public function AcceptedType($id)
    {
        return in_array($id, $this->type);
    }
    
    public function SetType($type)
    {
        $this->type = is_array($type) ? $type : explode(',', $type);
    }
    
    public function AcceptedTaxonomy($id)
    {
        return in_array($id, $this->taxonomy);
    }
    
    public function SetTaxonomy($taxonomy)
    {
        $this->taxonomy = is_array($taxonomy) ? $taxonomy : explode(',', $taxonomy);
    }
}
