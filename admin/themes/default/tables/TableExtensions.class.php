<?php

class TableExtensions extends TableBackend
{
    
    public function getColumns()
    {
        return array(
            'name' => _T('Name'),
            'description' => _T('Description'),
            'version' => _T('Version'),
            'author' => _T('Author'),
            'dirname' => _T('Directory'),
            'actions' => ''
        );
    }
    
    
    public function loadItems()
    {
        $this->items = get_extensions_list();
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
        return $item['dirname'];
    }
    
    public function column_author($item, $n)
    {
        if (empty($item['url']))
        {
            echo $item['author'];
        }
        else
        {
            ?>
            <a href="<?php print $item['url']; ?>" target="_blank">
                <?php print $item['author']; ?>
            </a>
            <?php
        }
    }
    
    
    
    
    public function column_actions($item, $n)
    {
        if ($item['active'])
        {
            ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item['dirname'], 'action'=>'unactive')); ?>" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></a>
            <?php
        }
        else
        {
            ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item['dirname'], 'action'=>'active')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span></a>
            <?php
        }
    }
    
    
}
