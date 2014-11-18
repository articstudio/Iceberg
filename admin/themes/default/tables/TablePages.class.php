<?php

class TablePages extends TableBackend
{
    
    public static $DEFAULT_ARGS = array(
        'pagegroup' => false
    );
    
    protected $_user_parent_page_id;
    
    public function __construct($args=array())
    {
        if (!user_has_full_capability(get_mode('capability')) && user_has_own_capability(get_mode('capability')))
        {
            $group = get_pagegroup($args['pagegroup']);
            $user_page  = get_page(User::GetParentPageID());
            if ((int)$user_page->group === (int)$group->GetID() && isset($args['attrs']['data-new']))
            {
                $args['attrs']['data-new'] = null;
                unset($args['attrs']['data-new']);
            }
        }
        parent::__construct($args);
    }
    
    
    public function getColumns()
    {
        $columns = array(
            'name' => _T('Name')
        );
        
        $group = get_pagegroup($this->_args['pagegroup']);
        if (count($group->GetType())>1)
        {
            $columns['type'] = _T('Type');
        }
        if (count($group->GetTaxonomy())>1)
        {
            $columns['taxonomy'] = _T('Taxonomy');
        }
        
        $columns['actions'] = '';
        return $columns;
    }
    
    
    public function loadItems()
    {
        $this->_user_parent_page_id = (int)User::GetParentPageID();
        $this->items = array();
        
        if (!isset($this->_args['attrs']['data-ajax']) || $this->useAjax())
        {
            $group = get_pagegroup($this->_args['pagegroup']);
            $this->_items_args = array(
                'group' => $group->GetID(),
                'type' => $group->GetType(),
                'taxonomy' => $group->GetTaxonomy()
            );
            if ($this->_args['items'])
            {
                $this->_items_args['items'] = intval($this->_args['items']);
                $this->_items_args['items_start'] = $this->_args['items_start'] ? intval($this->_args['items_start']) : 0;
            }

            if ($this->useAjax())
            {
                $this->_items_count = get_pages_count($this->_items_args, null, true);
            }

            if (!user_has_full_capability(get_mode('capability')) && user_has_own_capability(get_mode('capability')))
            {
                $args_my = array_merge($this->_items_args, array('id'=>User::GetParentPageID()));
                $my = get_pages(
                    $args_my,
                    null,
                    true,
                    array(
                        PageMeta::META_TITLE
                    )
                );
                $args_dependence = array_merge($this->_items_args, array('dependences'=> array(User::GetParentPageID())));
                $dependence = get_pages(
                    $args_dependence,
                    null,
                    true,
                    array(
                        PageMeta::META_TITLE
                    )
                );
                $this->items = array_merge($my, $dependence);
            }
            else
            {
                $this->items = get_pages(
                    $this->_items_args,
                    null,
                    true,
                    array(
                        PageMeta::META_TITLE
                    )
                );
            }
        }
    }
    
    public function column_default($item, $column_key, $n)
    {
        echo $item->$column_key;
    }
    
    public function get_column_default($item, $column_key, $n)
    {
        return $item->$column_key;
    }
    
    public function item_id($item)
    {
        return $item->id;
    }
    
    public function get_column_name($item, $n)
    {
        return $item->GetTitle();
    }
    
    public function column_name($item, $n)
    {
        echo $this->get_column_name($item, $n);
    }
    
    public function get_column_type($item, $n)
    {
        $taxonomy = get_pagetype($item->type);
        return $taxonomy->GetName();
    }
    
    public function column_type($item, $n)
    {
        echo $this->get_column_type($item, $n);
    }
    
    public function get_column_taxonomy($item, $n)
    {
        $taxonomy = get_pagetaxonomy($item->taxonomy);
        return $taxonomy->GetName();
    }
    
    public function column_taxonomy($item, $n)
    {
        echo $this->get_column_taxonomy($item, $n);
    }
    
    
    
    
    public function get_column_actions($item, $n)
    {
        $return = array('type'=>'actions');
        $languages = get_active_langs();
        if (count($languages) > 1)
        {
            $return['languages'] = array();
            $language = get_language_info();
            foreach ($languages AS $locale => $lang)
            {
                if ($locale !== $language['locale'])
                {
                    $return['languages'][$locale] = array(
                        'id' => $item->id,
                        'name' => $lang['name'],
                        'flag' => get_base_url() . $lang['flag'],
                        'translated' => $item->IsTranslated($locale),
                        'edit' => get_admin_action_link(array('id'=>$item->id, 'action'=>'edit', 'tlang'=>$locale)),
                        'duplicate' => get_admin_action_link(array('id'=>$item->id, 'action'=>'new', 'tlang'=>$locale, 'duplicate'=>1)),
                        'translate' => get_admin_action_link(array('id'=>$item->id, 'action'=>'new', 'tlang'=>$locale)),
                    );
                }
            }
        }
        $return['status'] = array(
            'can' => true,
            'status' => $item->status,
            'url' => get_admin_action_link(array('id'=>$item->id, 'action'=>$item->status?'unactive':'active'))
        );
        $return['edit'] = array(
            'can' => true,
            'url' => get_admin_action_link(array('id'=>$item->id, 'action'=>'edit'))
        );
        $return['remove'] = array(
            'can' => ($this->_user_parent_page_id !== (int)$item->id),
            'confirm' => _T('Are you sure to remove this item?'),
            'url' => get_admin_action_link(array('id'=>$item->id, 'action'=>'remove'))
        );
        return $return;
    }
    
    public function column_actions($item, $n)
    {
        $language = get_language_info();
        $languages = get_active_langs();
        ?>
        
        
        <?php if (count($languages) > 1): ?>
        <?php foreach ($languages AS $locale => $lang): ?>
        <?php if ($locale !== $language['locale']): ?>
        <?php if ($item->IsTranslated($locale)): ?>
        <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'edit', 'tlang'=>$locale)); ?>" class="btn btn-default">
            <img src="<?php print get_base_url() . $lang['flag']; ?>" alt="<?php print_html_attr($lang['name']); ?>" class="flag">
        </a>
        <?php else: ?>
        <div class="btn-group">
            <button class="btn btn-default dropdown-toggle" id="lang-<?php echo $locale; ?>-<?php echo $item->id; ?>" data-toggle="dropdown" href="#">
                <img src="<?php print get_base_url() . $lang['flag']; ?>" alt="<?php print_html_attr($lang['name']); ?>" class="flag">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="<?php echo $locale; ?>-<?php echo $item->id; ?>">
                <li role="presentation">
                    <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'new', 'tlang'=>$locale, 'duplicate'=>1)); ?>" role="menuitem" tabindex="-1">
                        <span class="glyphicon glyphicon-transfer"></span>
                        <?php print_text('Duplicate'); ?>
                    </a>
                </li>
                <li role="presentation">
                    <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'new', 'tlang'=>$locale)); ?>" role="menuitem" tabindex="-1">
                        <span class="glyphicon glyphicon-globe"></span>
                        <?php print_text('Translate'); ?>
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
        
        
        
        <?php if ($item->status): ?>
        <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'unactive')); ?>" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></a>
        <?php else: ?>
        <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'active')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span></a>
        <?php endif; ?>
        <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'edit')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
        
        <?php if ($this->_user_parent_page_id !== (int)$item->id): ?>
        <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'remove')); ?>" class="btn btn-danger" data-confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><span class="glyphicon glyphicon-trash"></span></a>
        <?php else: ?>
        <button class="btn btn-danger disabled"><span class="glyphicon glyphicon-trash"></span></button>
        <?php endif; ?>

        <?php
    }
    
    
}
