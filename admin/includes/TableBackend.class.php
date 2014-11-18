<?php

interface ITableBackend
{
    public function getColumns();
    public function loadItems();
    public function column_default($item, $column_key, $n);
    public function get_column_default($item, $column_key, $n);
    public function item_id($item);
}

abstract class TableBackend implements ITableBackend
{
    public $id;
    
    public $items;
    
    protected $_args;
    protected $_items_args;
    protected $_items_count;
    
    public static $DEFAULT_ARGS = array();
    
    
    public function __construct($args=array())
    {
        $args = array_merge(
            array(
                'id' => 'table_' . md5(microtime(true)),
                'ajax' => false,
                'attrs' => array(),
                'classes' => array(),
                'items' => false,
                'items_start' => false,
                'html' => true,
            ),
            static::$DEFAULT_ARGS,
            $args
        );
        $this->_args = apply_filters('table_' . strtolower(get_called_class()) . '_args', $args);
        $this->id = $this->_args['id'];
    }
    
    public function useAjax($use=null)
    {
        if ($use === null)
        {
            return (bool)$this->_args['ajax'];
        }
        return $this->_args['ajax'] = (bool)$use;
    }
    
    public function returnHTML($return=null)
    {
        if ($return === null)
        {
            return (bool)$this->_args['html'];
        }
        return $this->_args['html'] = (bool)$return;
    }
    
    public function has_items() {
		return !empty($this->items);
	}
    
    public function show_columns_header()
    {
        $columns = $this->getColumns();
        foreach($columns AS $key => $text)
        {
            ?>
            <th class="<?php echo get_html_attr($key); ?>"><?php echo $text; ?></th>
            <?php
        }
    }
    
    public function show_cell($item, $column_key, $n)
    {
        do_action('table_' . strtolower(get_called_class()) . '_' . $column_key . '_before', $item, $n);
        if ($column_key === 'order')
        {
            echo $n;
        }
        else if (method_exists($this, 'column_' . $column_key))
        {
            call_user_func(array($this, 'column_' . $column_key), $item, $n);
        }
        else
        {
            $this->column_default($item, $column_key, $n);
        }
        do_action('table_' . strtolower(get_called_class()) . '_' . $column_key . '_after', $item, $n);
    }
    
    public function get_cell($item, $column_key, $n)
    {
        $return = '';
        if ($column_key === 'order')
        {
            $return = $n;
        }
        else if (method_exists($this, 'get_column_' . $column_key))
        {
            $return = call_user_func(array($this, 'get_column_' . $column_key), $item, $n);
        }
        else
        {
            $return = $this->get_column_default($item, $column_key, $n);
        }
        return apply_filters('get_table_' . strtolower(get_called_class()) . '_' . $column_key, $return, $item, $n);
    }
    
    public function show_row($item, $n)
    {
        $columns = $this->getColumns();
        if ($this->useAjax())
        {
            $row = array();
            foreach($columns AS $column_key => $text)
            {
                $cell = '';
                if ($this->returnHTML())
                {
                    ob_start();
                    $this->show_cell($item, $column_key, $n);
                    $cell = ob_get_contents();
                    ob_end_clean();
                    $cell = str_replace(array("\n","\r"), '', $cell);
                    $cell = trim(preg_replace('/\s+/', ' ',$cell));
                }
                else
                {
                    $cell = $this->get_cell($item, $column_key, $n);
                }
                $row[] = $cell;
            }
            return $row;
        }
        foreach($columns AS $column_key => $text)
        {
            ?>
            <td class="<?php echo get_html_attr($column_key); ?>">
                <?php $this->show_cell($item, $column_key, $n); ?>
            </td>
            <?php
        }
    }
    
    public function show_rows()
    {
        if ($this->useAjax())
        {
            $n = 0;
            $rows = array();
            foreach($this->items AS $item)
            {
                $rows[] = $this->show_row($item, $n);
                ++$n;
            }
            return $rows;
        }
        if ($this->has_items())
        {
            $n = 0;
            foreach($this->items AS $item)
            {
                ?>
                <tr data-position="<?php print $n; ?>" id="<?php echo $this->item_id($item); ?>">
                    <?php $this->show_row($item, $n); ?>
                </tr>
                <?php
                ++$n;
            }
        }
    }
    
    public function getJSON()
    {
        $arr = array(
            'total'    => intval($this->_items_count),
            'data'            => $this->show_rows()
        );
        return json_encode($arr);
    }
    
    public function show()
    {
        if ($this->useAjax())
        {
            echo $this->getJSON();
        }
        else
        {
            $attrs = array();
            foreach ($this->_args['attrs'] AS $name => $value)
            {
                $attrs[] = $name . '="' . get_html_attr($value) . '"';
            }
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable <?php echo implode(' ', $this->_args['classes']); ?>" id="<?php echo $this->id; ?>" <?php echo implode(' ', $attrs); ?>>
                <thead>
                    <tr>
                        <?php $this->show_columns_header(); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $this->show_rows(); ?>
                </tbody>
            </table>
            <?php
        }
    }
    
    
    
    
    
    
    // ??
    public function __get( $name ) {
		return $this->$name;
	}

	public function __set( $name, $value ) {
		return $this->$name = $value;
	}

	public function __isset( $name ) {
		return isset( $this->$name );
	}

	public function __unset( $name ) {
		unset( $this->$name );
	}

	public function __call( $name, $arguments ) {
		return call_user_func_array( array( $this, $name ), $arguments );
	}
    
}
