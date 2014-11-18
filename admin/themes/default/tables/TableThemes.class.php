<?php

class TableThemes extends TableBackend
{
    public $theme_frontend;
    
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
        $this->items = get_frontend_themes();
        $this->theme_frontend = Theme::GetFrontendTheme();
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
    
    
    
    
    public function column_actions($item, $n)
    {
        if ($this->theme_frontend === $item['dirname'])
        {
            ?>
            <button class="btn btn-success disabled"><span class="glyphicon glyphicon-ok"></span></button>
            <?php
        }
        else
        {
            ?>
            <a href="<?php print get_admin_action_link(array('id'=>$item['dirname'], 'type'=>'frontend', 'action'=>'active')); ?>" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span></a>
            <?php
        }
    }
    
    
}
