<?php

class TableUsers extends TableBackend
{
    
    
    public function getColumns()
    {
        return array(
            'id' => _T('ID'),
            'username' => _T('Username'),
            'email' => _T('E-mail'),
            'role' => _T('Role'),
            'actions' => ''
        );
    }
    
    
    public function loadItems()
    {
        $this->items = get_user_list();
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
    
    
    
    public function column_role($item, $n)
    {
        $role = UserRole::Get($item->role);
        print_text($role->GetName());
    }
    
    public function column_actions($item, $n)
    {
        if ($item->status)
        {
            ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'unactive')); ?>" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></a>
            <?php
        }
        else
        {
            ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'active')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span></a>
            <?php
        }
        ?>
        <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'edit')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
        <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'remove')); ?>" class="btn btn-danger" data-confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><span class="glyphicon glyphicon-trash"></span></a>
        <?php
    }
    
    
}
