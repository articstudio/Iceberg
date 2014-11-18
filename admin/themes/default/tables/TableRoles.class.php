<?php

class TableRoles extends TableBackend
{
    public $default_role_id;
    public $root_role_id;
    
    public function getColumns()
    {
        return array(
            'name' => _T('Name'),
            'actions' => ''
        );
    }
    
    
    public function loadItems()
    {
        $this->items = get_user_roles();
        $this->default_role_id = get_default_user_role();
        $this->root_role_id = get_root_user_role();
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
    
    
    
    
    public function column_actions($item, $n)
    {
        if ($item->GetID() === $this->root_role_id)
        {
            ?>
            <button class="btn btn-default disabled"><span class="glyphicon glyphicon-star"></span></button>
            <button class="btn btn-default disabled"><span class="glyphicon glyphicon-pencil"></span></button>
            <button class="btn btn-danger disabled"><span class="glyphicon glyphicon-trash"></span></button>
            <?php
        }
        else if ($item->GetID() === $this->default_role_id)
        {
            ?>
            <button class="btn btn-success disabled"><span class="glyphicon glyphicon-star"></span></button>
            <a href="<?php echo get_admin_action_link(array('id'=>$item->GetID(), 'action'=>'edit')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <button class="btn btn-danger disabled"><span class="glyphicon glyphicon-trash"></span></button>
            <?php
        }
        else
        {
            ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item->GetID(),'action'=>'default')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-star"></span></a>
            <a href="<?php echo get_admin_action_link(array('id'=>$item->GetID(), 'action'=>'edit')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="<?php echo get_admin_action_link(array('id'=>$item->GetID(), 'action'=>'remove')); ?>" class="btn btn-danger" data-confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><span class="glyphicon glyphicon-trash"></span></a>
            <?php
        }
    }
    
    
}
