<?php
/**
 * 
 */

/**
 * Add a action
 * 
 * @global array $__ACTIONS
 * @param string $event Event name
 * @param string $function Function name
 * @param int $priority Priority of function in event (Default: 10)
 * @param int $accepted_arguments Arguments of function (Default: 1)
 * @return boolean 
 */
function add_action($event, $function, $priority=10, $accepted_arguments=1)
{
    global $__ACTIONS;
    if (is_string($event) && !empty($event) && is_string($function) && !empty($function) && is_int($priority) && is_int($accepted_arguments))
    {
        if (!isset($__ACTIONS[$event]) || !is_array($__ACTIONS[$event]))
        {
            $__ACTIONS[$event] = array();
        }
        if (!isset($__ACTIONS[$event][$priority]) || !is_array($__ACTIONS[$event][$priority]))
        {
            $__ACTIONS[$event][$priority] = array();
        }
        $__ACTIONS[$event][$priority][$function] = $accepted_arguments;
        return true;
    }
    return false;
}

/**
 * Remove a action
 * 
 * @global array $__ACTIONS
 * @param string $event Event name
 * @param string $function Function name
 * @param int $priority Priority of function in event (Default: 10)
 * @return boolean 
 */
function remove_action($event, $function, $priority=10)
{
    global $__ACTIONS;
    if (is_string($event) && !empty($event) && is_string($function) && !empty($function) && is_int($priority))
    {
        if (isset($__ACTIONS[$event]) && isset($__ACTIONS[$event][$priority]) && isset($__ACTIONS[$event][$priority][$function]))
        {
            unset( $__ACTIONS[$event][$priority][$function] );
            if (empty($__ACTIONS[$event][$priority])) { unset($__ACTIONS[$event][$priority]); }
            if (empty($__ACTIONS[$event])) { unset( $__ACTIONS[$event] ); }
            return true;
        }
    }
    return false;
}

/**
 * Check if an action exists for an event
 * 
 * @global array $__ACTIONS
 * @param string $event
 * @param string $function
 * @return boolean|int
 */
function has_action($event, $function)
{
    global $__ACTIONS;
    if (is_string($event) && !empty($event) && is_string($function) && !empty($function) && is_int($priority))
    {
        if (isset($__ACTIONS[$event]) && !empty($__ACTIONS[$event]))
        {
            foreach ($__ACTIONS[$event] AS $priority => $functions)
            {
                if (is_array($functions) && !empty($functions) && isset($functions[$function]))
                {
                    return $priority;
                }
            }
        }
    }
    return false;
}

function get_hooks($event)
{
    global $__ACTIONS;
    if (isset($__ACTIONS[$event]) && !empty($__ACTIONS[$event]))
    {
        return $__ACTIONS[$event];
    }
    return array();
}

/**
 * Execute a event action
 * 
 * @global array $__ACTIONS
 * @param string $event Event name
 * @return array 
 */
function do_action($event)
{
    global $__ACTIONS;
    if (is_string($event) && !empty($event) && isset($__ACTIONS[$event]) && is_array($__ACTIONS[$event]) && !empty($__ACTIONS[$event]))
    {
        ksort($__ACTIONS[$event]);
        $all_args = func_get_args();
        $args = array_slice($all_args, 1);
        foreach ($__ACTIONS[$event] AS $priority => $functions)
        {
            if (is_array($functions) && !empty($functions))
            {
                foreach ($functions AS $function => $accepted_arguments)
                {
                    call_user_func_array($function, array_slice($args, 0, (int)$accepted_arguments));
                    //__exec_action($function, array_slice($args, 0, (int)$accepted_arguments));
                }
            }
        }
    }
}
/*function __exec_action($function)
{
    $all_args = func_get_args();
    $return = array_slice($all_args, 1, (int) count($all_args));
    if (is_string($function) && !empty($function)) {
        if (function_exists($function)) {
            $return = call_user_func_array($function, array_slice($all_args, 1, (int) count($all_args)));
        }
    }
    return $return;
}*/


/**
 * Add a filter
 * 
 * @global array $__FILTERS
 * @param string $event Event name
 * @param string $function Function name
 * @param int $priority Priority of function in event (Default: 10)
 * @param int $accepted_arguments Arguments of function (Default: 1)
 * @return boolean 
 */
function add_filter($event, $function, $priority=10, $accepted_arguments=1)
{
    global $__FILTERS;
    if (is_string($event) && !empty($event) && is_string($function) && !empty($function) && is_int($priority) && is_int($accepted_arguments))
    {
        if (!isset($__FILTERS[$event]) || !is_array($__FILTERS[$event]))
        {
            $__FILTERS[$event] = array();
        }
        if (!isset($__FILTERS[$event][$priority]) || !is_array($__FILTERS[$event][$priority]))
        {
            $__FILTERS[$event][$priority] = array();
        }
        $__FILTERS[$event][$priority][$function] = $accepted_arguments;
        return true;
    }
    return false;
}

/**
 * Remove a filter
 * 
 * @global array $__FILTERS
 * @param string $event Event name
 * @param string $function Function name
 * @param int $priority Priority of function in event (Default: 10)
 * @return boolean 
 */
function remove_filter($event, $function, $priority=10)
{
    global $__FILTERS;
    if (is_string($event) && !empty($event) && is_string($function) && !empty($function) && is_int($priority))
    {
        if (isset($__FILTERS[$event]) && isset($__FILTERS[$event][$priority]) && isset($__FILTERS[$event][$priority][$function]))
        {
            unset( $__FILTERS[$event][$priority][$function] );
            if (empty($__FILTERS[$event][$priority])) { unset($__FILTERS[$event][$priority]); }
            if (empty($__FILTERS[$event])) { unset( $__FILTERS[$event] ); }
            return true;
        }
    }
    return false;
}
/**
 * Check if an action exists for a filter
 * 
 * @global array $__FILTERS
 * @param string $event
 * @param string $function
 * @return boolean|int
 */
function has_filter($event, $function)
{
    global $__FILTERS;
    if (is_string($event) && !empty($event) && is_string($function) && !empty($function) && is_int($priority))
    {
        if (isset($__FILTERS[$event]) && !empty($__FILTERS[$event]))
        {
            foreach ($__FILTERS[$event] AS $priority => $functions)
            {
                if (is_array($functions) && !empty($functions) && isset($functions[$function]))
                {
                    return $priority;
                }
            }
        }
    }
    return false;
}

function get_filters($event)
{
    global $__FILTERS;
    if (isset($__FILTERS[$event]) && !empty($__FILTERS[$event]))
    {
        return $__FILTERS[$event];
    }
    return array();
}

/**
 * Execute a event filter
 * 
 * @global array $__FILTERS
 * @param string $event Event name
 * @return array 
 */
function apply_filters($event, $value)
{
    global $__FILTERS;
    if (is_string($event) && !empty($event) && isset($__FILTERS[$event]) && is_array($__FILTERS[$event]) && !empty($__FILTERS[$event]))
    {
        ksort($__FILTERS[$event]);
        $all_args = func_get_args();
        $args = array_slice($all_args, 2, (int)count($all_args));
        foreach ($__FILTERS[$event] AS $priority => $functions)
        {
            if (is_array($functions) && !empty($functions))
            {
                foreach ($functions AS $function => $accepted_arguments)
                {
                    $value = call_user_func_array($function, array_merge(array($value), array_slice($args, 0, (int)$accepted_arguments-1)));
                    //$value = __exec_filter($function, array_merge(array($value), array_slice($args, 0, (int)$accepted_arguments-1)));
                }
            }
        }
    }
    return $value;
}
/*function __exec_filter($function)
{
    $all_args = func_get_args();
    $return = array_slice($all_args, 1, (int) count($all_args));
    if (is_string($function) && !empty($function)) {
        if (function_exists($function)) {
            $return = call_user_func_array($function, array_slice($all_args, 1, (int) count($all_args)));
        }
    }
    return $return;
}*/

