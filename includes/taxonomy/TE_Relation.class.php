<?php

class TE_Relation extends TaxonomyElements
{
    protected static $NAME = 'Relation';
    protected $group = array();
    protected $type = array();
    protected $taxonomy = array();
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
    
    public function FormEditMultipleTaxonomy($page, $taxonomy)
    {
        $pages = get_pages(array(
            'group' => $this->group,
            'type' => $this->type,
            'taxonomy' => $taxonomy,
            'order' => 'name'
        ));
        $parents = array();
        foreach ($pages AS $ppage)
        {
            array_push($parents, $ppage->parent);
        }
        $categs = get_pages(array('id'=>$parents));
        list($pages, $categs) = action_event('filter_form_edit_te_relation', $pages, $categs, $this, $page);
        ?>
        
        <?php if (count($categs) > 0 && count($taxonomy)==1): ?>
        
        <?php foreach ($categs AS $categ): ?>
        <h5><?php print $categ->GetTitle(); ?></h5>
        <div class="row-fluid">
            <?php $i=1; foreach ($pages AS $id => $rpage): $parent=get_page($rpage->parent); ?>
            <?php if ($rpage->parent == $categ->id): ?>
            <div class="span3">
                <label class="checkbox" for="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $id; ?>">
                    <input type="checkbox" name="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>[]" value="<?php print $id; ?>" id="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $id; ?>" <?php print $this->IsRelation($page, $id) ? 'checked' : ''; ?>>
                    <?php print $rpage->GetTitle(); ?>
                </label>
            </div>
            <?php if ($i%4==0): ?>
        </div>
        <div class="row-fluid">
            <?php endif; ?>
            <?php $i++; endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
        
        <?php else: ?>
        
        <?php
        $taxonomy_object = get_pagetaxonomy($taxonomy);
        ?>
        <h5><?php print $taxonomy_object->GetName(); ?></h5>
        <div class="row-fluid">
            <?php $i=1; foreach ($pages AS $id => $rpage): $parent=get_page($rpage->parent); ?>
            <div class="span3">
                <label class="checkbox" for="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $id; ?>">
                    <input type="checkbox" name="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>[]" value="<?php print $id; ?>" id="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $id; ?>" <?php print $this->IsRelation($page, $id) ? 'checked' : ''; ?>>
                    <?php print $rpage->GetTitle(); ?>
                    <?php if ($parent->id != -1): ?>
                    <br/><small>(<?php print $parent->GetTitle(); ?>)</small>
                    <?php endif; ?>
                </label>
            </div>
            <?php if ($i%4==0): ?>
        </div>
        <div class="row-fluid">
            <?php endif; ?>
            <?php $i++; ?>
            <?php endforeach; ?>
        </div>
        
        <?php endif; ?>
        
        <?php
    }
    
    public function FormEditMultiple($page)
    {
        foreach ($this->taxonomy AS $taxonomy) {
            $this->FormEditMultipleTaxonomy($page, $taxonomy);
        }
    }
    
    public function FormEditSimple($page)
    {
        $pages = get_pages(array(
            'group' => $this->group,
            'type' => $this->type,
            'taxonomy' => $this->taxonomy,
            'order' => 'name'
        ));
        list($pages) = action_event('filter_form_edit_te_relation', $pages, null, $this, $page);
        ?>
        <select class="input-block-level" id="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" name="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>">
            <option value="NULL"></option>
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
        Page::DeleteRelation($page_id, Page::RELATION_KEY_PAGE . '#' . $this->GetAttrName(), $lang);
        foreach ($ids AS $k => $parent)
        {
            Page::InsertRelation($page_id, Page::RELATION_KEY_PAGE . '#' . $this->GetAttrName(), $parent, $lang, $k);
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
