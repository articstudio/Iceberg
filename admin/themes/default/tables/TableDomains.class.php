<?php

class TableDomains extends TableBackend
{
    public $default_domain_id;
    
    public function getColumns()
    {
        return array(
            'name' => _T('Name'),
            'alias' => _T('Alias'),
            'actions' => ''
        );
    }
    
    
    public function loadItems()
    {
        $this->items = get_domains_canonicals();
        $this->default_domain_id = get_domain_id();
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
    
    
    
    public function column_alias($item, $n)
    {
        $childs = get_domains_by_parent($item->id);
        foreach ($childs AS $child)
        {
            echo $child->name . '<br>';
        }
    }
    
    public function column_actions($item, $n)
    {
        if ($item->id === $this->default_domain_id)
        {
            ?>
            <a href="<?php echo get_admin_action_link(array('id'=>$item->id, 'action'=>'edit')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <button class="btn btn-danger disabled"><span class="glyphicon glyphicon-trash"></span></button>
            <?php
        }
        else
        {
            ?>
            <a href="<?php echo get_admin_action_link(array('id'=>$item->id, 'action'=>'edit')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="<?php echo get_admin_action_link(array('id'=>$item->id, 'action'=>'remove')); ?>" class="btn btn-danger" data-confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><span class="glyphicon glyphicon-trash"></span></a>
            <?php
        }
    }
    
    
}
