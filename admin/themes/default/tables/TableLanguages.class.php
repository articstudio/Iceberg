<?php

class TableLanguages extends TableBackend
{
    public $default_language;
    
    public function getColumns()
    {
        return array(
            'order' => _T('Order'),
            'name' => _T('Name'),
            'locale' => _T('Locale'),
            'iso' => _T('ISO'),
            'flag' => _T('Flag'),
            'actions' => ''
        );
    }
    
    
    public function loadItems()
    {
        $this->items = get_langs();
        $this->default_language = get_language_default();
    }
    
    public function column_default($item, $column_key, $n)
    {
        echo $item[$column_key];
    }
    
    public function get_column_default($item, $column_key, $n)
    {
        return $item[$column_key];
    }
    
    public function item_id($item)
    {
        return $item['locale'];
    }
    
    
    
    
    public function column_flag($item, $n)
    {
        ?>
        <img src="<?php echo get_flag_url($item['flag']); ?>" alt="<?php echo $item['locale']; ?>" />
        <?php
    }
    
    public function column_actions($item, $n)
    {
        if ($this->default_language === $item['locale'])
        {
            ?>
            <button class="btn btn-success disabled"><span class="glyphicon glyphicon-star"></span></button>
            <button class="btn btn-success disabled"><span class="glyphicon glyphicon-eye-open"></span></button>
            <button class="btn btn-success disabled"><span class="glyphicon glyphicon-ok"></span></button>
            <a href="<?php print get_admin_action_link(array('id'=>$item['locale'],'action'=>'edit')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <button class="btn btn-danger disabled"><span class="glyphicon glyphicon-trash"></span></button>
            <?php
        }
        else if ($item['active'])
        {
            ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item['locale'],'action'=>'default')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-star"></span></a>
            <?php if ($item['visible']): ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item['locale'],'action'=>'invisible')); ?>" class="btn btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>
            <?php else: ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item['locale'],'action'=>'visible')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-eye-close"></span></a>
            <?php endif; ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item['locale'],'action'=>'unactive')); ?>" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></a>
            <a href="<?php print get_admin_action_link(array('id'=>$item['locale'],'action'=>'edit')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="<?php print get_admin_action_link(array('id'=>$item['locale'],'action'=>'remove')); ?>" class="btn btn-danger" data-confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><span class="glyphicon glyphicon-trash"></span></a>
            <?php
        }
        else
        {
            ?>
            <button class="btn btn-default disabled"><span class="glyphicon glyphicon-star"></span></button>
            <button class="btn btn-default disabled"><span class="glyphicon glyphicon-eye-open"></span></button>
            <a href="<?php print get_admin_action_link(array('id'=>$item['locale'], 'action'=>'active')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span></a>
            <a href="<?php print get_admin_action_link(array('id'=>$item['locale'], 'action'=>'edit')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="<?php print get_admin_action_link(array('id'=>$item['locale'], 'action'=>'remove')); ?>" class="btn btn-danger" data-confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><span class="glyphicon glyphicon-trash"></span></a>
            <?php
        }
    }
    
    
}
