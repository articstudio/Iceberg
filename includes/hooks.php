<?php

/**
 * Add a hook
 * 
 * @global array $__HOOKS
 * @param string $event Event name
 * @param string $name Hook name
 * @param string $description Hook description
 * @return boolean 
 */
function add_hook($event, $name, $description='')
{
    global $__HOOKS;
    return $__HOOKS[$event] = array('name'=>$name, 'description'=>$description);
}

/**
 * Remove a hook
 * 
 * @global array $__HOOKS
 * @param string $event Event name
 * @return boolean 
 */
function remove_hook($event)
{
    global $__HOOKS;
    if (isset($__HOOKS[$event])) {
        unset($__HOOKS[$event]);
    }
    return true;
}

/**
 * Return all hooks
 * 
 * @global array $__HOOKS
 * @return array List of hooks
 */
function get_hooks()
{
    global $__HOOKS;
    if (!is_array($__HOOKS)) { $__HOOKS=array(); }
    return $__HOOKS;
}

/**
 * Execute hook
 * @param string $event Event name
 */
function hook($event)
{
    $widgets = get_registered_hooks($event);
    foreach ($widgets AS $id => $widget) {
        $widget->Show();
    }
}

/**
 * Register hook
 * 
 * @param string $event Event name
 * @param string $widget Widget name
 * @return type
 */
function register_hook($event, $widget)
{
    $count = 0;
    $widget = new $widget();
    $query = new Query();
    $query->select(ICEBERG_DB_CONFIG, 'id, count', "WHERE did='" . mysql_escape(get_domain_id()) . "' AND name='" . mysql_escape('hook_'.$event) . "'", 'count ASC', '0,1');
    if ($query->numrows()) {
        $row=$query->next();
        $count=$row->count + 1;
    }
    $done = $query->insert(ICEBERG_DB_CONFIG, array('did','name','value','count'), array(get_domain_id(),'hook_'.$event,serialize($widget),$count));
    return $done ? $query->getInsertId() : false;
}

/**
 * Unregister hook
 * 
 * @param string $id
 * @return type
 */
function unregister_hook($id)
{
    $query = new Query();
    return $query->delete(ICEBERG_DB_CONFIG, "WHERE did='" . mysql_escape(get_domain_id()) . "' AND id='" . mysql_escape($id) . "'");
}

function order_register_hook($ids) {
    $query = new Query();
    foreach ($ids AS $count => $id) {
        $query->update(ICEBERG_DB_CONFIG, array('count'=>$count), "WHERE did='" . mysql_escape(get_domain_id()) . "' AND id='" . mysql_escape($id) . "'");
    }
    return true;
}

function get_registered_hooks($event)
{
    $return = array();
    $query = new Query();
    $query->select(ICEBERG_DB_CONFIG, 'id, value', "WHERE did='" . mysql_escape(get_domain_id()) . "' AND name='" . mysql_escape('hook_'.$event) . "'",'count ASC');
    if ($query->numrows()>0) {
        while ($row=$query->next()) {
            $return[$row->id] = unserialize($row->value);
        }
    }
    return $return;
}

function get_registered_hook($id)
{
    $return = null;
    $query = new Query();
    $query->select(ICEBERG_DB_CONFIG, 'id, value', "WHERE did='" . mysql_escape(get_domain_id()) . "' AND id='" . mysql_escape($id) . "'");
    if ($query->numrows()>0) {
        $row=$query->next();
        $return = unserialize($row->value);
    }
    return $return;
}

function set_registered_hook($id, $widget)
{
    $query = new Query();
    return $query->update(ICEBERG_DB_CONFIG, array('value'=>serialize($widget)), "WHERE id='" . mysql_escape($id) . "'");
}
