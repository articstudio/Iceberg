<?php

class TableCapabilities extends TableBackend
{
    public $default_role_id;
    public $root_role_id;
    
    public function getColumns()
    {
        return array(
            'name' => _T('Name'),
            'capability' => _T('Capability'),
            'actions' => ''
        );
    }
    
    
    public function loadItems()
    {
        $this->items = get_user_capabilities();
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
        return $item->GetID();
    }
    
    public function column_name($item, $n)
    {
        echo $item->GetName();
    }
    
    public function column_capability($item, $n)
    {
        echo $item->GetCapability();
    }
    
    
    
    
    public function column_actions($item, $n)
    {
        ?>
        <a href="<?php echo get_admin_action_link(array('id'=>$item->GetID(), 'action'=>'edit')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
        <a href="<?php echo get_admin_action_link(array('id'=>$item->GetID(), 'action'=>'remove')); ?>" class="btn btn-danger" data-confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><span class="glyphicon glyphicon-trash"></span></a>
        <?php
    }
    
    
}
