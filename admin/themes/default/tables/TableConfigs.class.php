<?php

class TableConfigs extends TableBackend
{
    
    public static $DEFAULT_ARGS = array(
        'allconfig' => false
    );
    
    
    public function getColumns()
    {
        return array(
            'id' => _T('ID'),
            'name' => _T('Name'),
            //'value' => _T('Value'),
            'actions' => ''
        );
    }
    
    
    public function loadItems()
    {
        $this->items = select_all_config_objects($this->_args['allconfig'] ? false : null);
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
    
    
    
    public function column_value($item, $n)
    {
        echo cut_text($item->value, 90, true, '...', true);
    }
    
    public function column_actions($item, $n)
    {
        ?>
        <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'edit', 'allconfig'=>$this->_args['allconfig'])); ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
        <a href="<?php print get_admin_action_link(array('id'=>$item->id, 'action'=>'remove', 'allconfig'=>$this->_args['allconfig'])); ?>" class="btn btn-danger" data-confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><span class="glyphicon glyphicon-trash"></span></a>
        <?php
    }
    
    
}
