<?php

class TableObjTaxonomy extends TableBackend
{
    
    public static $DEFAULT_ARGS = array(
        'type' => 'object_taxonomy'
    );
    
    public $default_language;
    
    public function getColumns()
    {
        return array(
            'order' => _T('Order'),
            'name' => _T('Name'),
            'actions' => ''
        );
    }
    
    
    public function loadItems()
    {
        $this->items = get_objtaxonomy_list($this->_args['type']);
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
        if ($this->_args['type'] === PageTaxonomy::$TAXONOMY_KEY)
        {
            ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item->GetID(), 'action'=>'config')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-wrench"></span></a>
            <?php
        }
        ?>
        <a href="<?php print get_admin_action_link(array('id'=>$item->GetID(), 'action'=>'edit')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
        <?php
        if ($item->IsLocked())
        {
            ?>
            <button class="btn btn-danger disabled"><span class="glyphicon glyphicon-trash"></span></button>
            <?php
        }
        else
        {
            ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item->GetID(), 'action'=>'remove')); ?>" class="btn btn-danger" data-confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><span class="glyphicon glyphicon-trash"></span></a>
            <?php
        }
    }
    
    
}
