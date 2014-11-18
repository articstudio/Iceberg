<?php

class TE_Dependence extends TaxonomyElements
{
    protected static $NAME = 'Dependence';
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
        parent::FormConfig();
        $groups = get_pagegroups();
        $types = get_pagetypes();
        $taxonomies = get_pagetaxonomies();
        ?>
        <h4><?php print_text('Groups'); ?></h4>
        <div class="row">
            <?php $i=1; foreach ($groups AS $id => $group): ?>
            <div class="col-md-3 col-xs-4">
                <p class="radio">
                    <label class="checkbox" for="cgroup-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>">
                        <input type="checkbox" name="group-<?php print $this->GetAttrName(); ?>[]" value="<?php print $id; ?>" id="cgroup-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>" <?php print $this->AcceptedGroup($id) ? 'checked' : ''; ?>>
                        <?php print $group->GetName(); ?>
                    </label>
                </p>
            </div>
            <?php if ($i%4==0): ?>
        </div>
        <div class="row">
            <?php endif; ?>
            <?php $i++; endforeach; ?>
        </div>
        
        <h4><?php print_text('Types'); ?></h4>
        <div class="row">
            <?php $i=1; foreach ($types AS $id => $type): ?>
            <div class="col-md-3 col-xs-4">
                <p class="radio">
                    <label class="checkbox" for="ctype-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>">
                        <input type="checkbox" name="type-<?php print $this->GetAttrName(); ?>[]" value="<?php print $id; ?>" id="ctype-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>" <?php print $this->AcceptedType($id) ? 'checked' : ''; ?>>
                        <?php print $type->GetName(); ?>
                    </label>
                </p>
            </div>
            <?php if ($i%4==0): ?>
        </div>
        <div class="row">
            <?php endif; ?>
            <?php $i++; endforeach; ?>
        </div>
        
        <h4><?php print_text('Taxonomies'); ?></h4>
        <div class="row">
            <?php $i=1; foreach ($taxonomies AS $id => $taxonomy): ?>
            <div class="col-md-3 col-xs-4">
                <p class="radio">
                    <label class="checkbox" for="ctax-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>">
                        <input type="checkbox" name="taxonomy-<?php print $this->GetAttrName(); ?>[]" value="<?php print $id; ?>" id="ctax-<?php print $this->GetAttrName(); ?>-<?php print $id; ?>" <?php print $this->AcceptedTaxonomy($id) ? 'checked' : ''; ?>>
                        <?php print $taxonomy->GetName(); ?>
                    </label>
                </p>
            </div>
            <?php if ($i%4==0): ?>
        </div>
        <div class="row">
            <?php endif; ?>
            <?php $i++; endforeach; ?>
        </div>
        <?php
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
            'taxonomy' => $this->taxonomy,
            'order' => 'name'
        ));
        //list($pages) = do_action('filter_form_edit_te_relation', $pages, null, $this, $page);
        ?>
        <div class="form-group">
            <label for="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" class="control-label"><?php echo $this->GetTitle(); ?></label>
            <select class="form-control" id="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" name="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>">
                <option class="NULL"></option>
                <?php foreach ($pages AS $id => $rpage): $parent=get_page($rpage->parent); ?>
                <?php if ($page->id != $id): ?>
                <option value="<?php print $id; ?>" <?php print $this->IsRelation($page, $id) ? 'selected' : ''; ?>>
                    <?php print $rpage->GetTitle(); ?>
                    <?php if ($parent->id != -1): ?>
                    (<?php print $parent->GetTitle(); ?>)
                    <?php endif; ?>
                </option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <?php parent::FormEdit($page); ?>
        </div>
        
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
        Page::DB_DeleteParentRelation($page_id, Page::RELATION_KEY_PAGE_DEPENDENCE, $this->GetAttrName(), $lang);
        foreach ($ids AS $k => $parent)
        {
            Page::DB_InsertParentRelation($page_id, Page::RELATION_KEY_PAGE_DEPENDENCE, $this->GetAttrName(), $parent, $lang, $k);
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
