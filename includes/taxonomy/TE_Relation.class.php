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
        $this->IsMultiple(isset($args['multiple']) ? (bool)$args['multiple'] : false);
        parent::__construct($args);
    }
    
    public function FormConfig()
    {
        parent::FormConfig();
        $groups = get_pagegroups();
        $types = get_pagetypes();
        $taxonomies = get_pagetaxonomies();
        ?>
        <div class="row">
            <div class="col-xs-4">
                <p class="control-group">
                    <label for="multiple-<?php print $this->GetAttrName(); ?>" class="control-label"><?php print_text('Multiple'); ?></label>
                    <select name="multiple-<?php print $this->GetAttrName(); ?>" id="multiple-<?php print $this->GetAttrName(); ?>" class="form-control">
                        <option value="0"><?php print_text('No'); ?></option>
                        <option value="1" <?php print $this->IsMultiple() ? 'selected' : ''; ?>><?php print_text('Yes'); ?></option>
                    </select>
                </p>
            </div>
        </div>
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
    }
    
    public function FormEditMultipleTaxonomy($page, $taxonomy_id)
    {
        $possibles = get_pages(array(
            'group' => $this->group,
            'type' => $this->type,
            'taxonomy' => $taxonomy_id,
            'order' => 'name'
        ));
        $possibles_keys = array_keys($possibles);
        $taxonomy = get_pagetaxonomy($taxonomy_id);
        ?>
        
        <h4><?php echo $taxonomy->GetName(); ?></h4>
        <div class="row" data-select="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $taxonomy->GetID(); ?>-list">
            <div class="col-md-6">
                <p class="control-group">
                    <label for="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $taxonomy->GetID(); ?>-choices" class="control-label"><?php print_text('Choices'); ?></label>
                    <select id="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $taxonomy->GetID(); ?>-choices" class="form-control" multiple="multiple" data-list="<?php echo implode(',',$possibles_keys); ?>">
                        <?php foreach ($possibles AS $possible): ?>
                        <?php if (!$this->IsRelation($page, $possible->id)): ?>
                        <option value="<?php echo $possible->id; ?>"><?php echo $possible->GetTitle(); ?></option>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p class="control-group">
                    <a href="#" data-add="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $taxonomy->GetID(); ?>-choices" title="<?php print_html_attr( _T('ADD') ); ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span> <?php print_text( 'ADD' ); ?></a>
                </p>
            </div>
            <div class="col-md-6">
                <p class="control-group">
                    <label for="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $taxonomy->GetID(); ?>-list" class="control-label"><?php print_text('Selecteds'); ?></label>
                    <select id="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $taxonomy->GetID(); ?>-list" class="form-control" multiple="multiple" data-hidden-destionation="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>">
                        <?php foreach ($possibles AS $possible): ?>
                        <?php if ($this->IsRelation($page, $possible->id)): ?>
                        <option value="<?php echo $possible->id; ?>"><?php echo $possible->GetTitle(); ?></option>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <?php foreach ($possibles AS $possible): ?>
                    <?php if ($this->IsRelation($page, $possible->id)): ?>
                    <input type="hidden" name="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>[]" id="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php echo $possible->id; ?>" value="<?php echo $possible->id; ?>">
                    <?php endif; ?>
                    <?php endforeach; ?>
                </p>
                <p class="control-group">
                    <a href="#" data-remove="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>-<?php print $taxonomy->GetID(); ?>-list" title="<?php print_html_attr( _T('REMOVE') ); ?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-minus"></span> <?php print_text( 'REMOVE' ); ?></a>
                </p>

            </div>
        </div>
        
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
        //list($pages) = do_action('filter_form_edit_te_relation', $pages, null, $this, $page);
        ?>
        <div class="form-group">
            <label for="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" class="control-label"><?php echo $this->GetTitle(); ?></label>
            <select class="form-control" id="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>" name="select-<?php print $this->GetAttrName(); ?>-<?php print $this->GetTaxonomy(); ?>">
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
        Page::DB_DeleteParentRelation($page_id, Page::RELATION_KEY_PAGE, $this->GetAttrName(), $lang);
        foreach ($ids AS $k => $parent)
        {
            Page::DB_InsertParentRelation($page_id, Page::RELATION_KEY_PAGE, $this->GetAttrName(), $parent, $lang, $k);
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
