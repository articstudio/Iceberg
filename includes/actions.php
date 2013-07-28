<?php
/**
 * cms_configure
 * request_get_protocol => $protocol
 * request_get_domain => $domain
 * request_get_url => $url, $withProtocol
 * request_get_baseurl => $url, $withProtocol
 * request_get_baseurladmin => $url, $withProtocol
 * request_get_baseurlapi => $url, $withProtocol
 * request_get_value_s => $value, $key, $default, $stripSlashes
 * request_get_value_g => $value, $key, $default, $stripSlashes
 * request_get_value_p => $value, $key, $default, $stripSlashes
 * request_get_value_gp => $value, $key, $default, $stripSlashes
 * request_get_value_sgp => $value, $key, $default, $stripSlashes
 * request_isset_s => $isset, $key
 * request_isset_g => $isset, $key
 * request_isset_p => $isset, $key
 * request_isset_gp => $isset, $key
 * request_isset_sgp => $isset, $key
 * request_get_language => $language
 * request_stripslashes => $var
 * request_addmagicquotes => $var
 * request_makeurl => $url, $baseurl, $params
 * request_makeurldir => $url, $dir, $params
 * config_preload => $__CONFIG, $__CONFIG_JSON_ROWS
 * config_postload => $__CONFIG, $__CONFIG_JSON_ROWS
 * config_set => $keyname, $value
 * config_unset => $keyname
 * config_get => $return, $keyname, $default
 * session_start => $session_name, $session_time
 * session_stop => $drop
 * session_set => $keyname, $value
 * session_unset => $keyname
 * user_get_config => $value, $key, $default
 * user_get_id => $id
 * user_get_name => $name
 * user_get_surname => $surname
 * user_get_fullname => $fullname, $name, $surname
 * user_get_email => $email
 * user_get_level => $level
 * user_get_level_name => $name, $level
 * user_get_registerdate => $registerDate
 * user_get_params => $params
 * user_get_list => $list, $status, $level, $pid
 * user_get_by_email => $user, $email
 * user_get_info => $user, $id
 * user_islogged => $logged
 * user_islogin => $login
 * user_islogout => $logout
 * user_isadmin => $isadmin
 * user_enctryptpass => $pass_encrypted, $pass
 * template_generate_content => $content, $template
 * theme_add_helper => $theme_helpers_extended, $done, $helper, $type
 * theme_get_template_file_basic => $file, $theme_files, $key
 * theme_get_template_file => $file, $theme_files, $key
 * theme_print_helper => $result, $helper, $type
 * theme_print_header
 * theme_print_head
 * i18n_ini => $__CMS_LANGUAGES, $__LANGUAGES
 * i18n_load_dinamics => $__LANGUAGES, $languages
 * i18n_load_default => $__LANGUAGE
 * i18n_load_lang => $_TEXT, $lang
 * app_config_routing => $routing_extension
 * maintenance_start
 * maintenance_postconfig
 * maintenance_stop
 */

/**
 * admin_modes => $array
 * admin_mode => $array, $key 
 * admin_breadcrumb => $array
 * admin_reverse => $array
 * admin_configuration_modes => $array
 * admin_configuration_mode => $array, $key 
 * admin_configuration_breadcrumb => $array
 * admin_extensions_modes => $array
 * admin_extensions_mode => $array, $key 
 * admin_extensions_breadcrumb => $array
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
    if (is_string($event) && !empty($event) && is_string($function) && !empty($function)) {
        $__ACTIONS[$event][$priority][$function] = $accepted_arguments;
        return true;
    }
    else { return false; }
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
    if (is_string($event) && !empty($event) && is_string($function) && !empty($function)) {
        if (isset($__ACTIONS[$event][$priority][$function])) {
            unset( $__ACTIONS[$event][$priority][$function] );
            if (empty($__ACTIONS[$event][$priority])) { unset($__ACTIONS[$event][$priority]); }
            if (empty($__ACTIONS[$event])) { unset( $__ACTIONS[$event] ); }
        }
        return true;
    }
    else { return false; }
}

/**
 * Execute a event action
 * 
 * @global array $__ACTIONS
 * @param string $event Event name
 * @return array 
 */
function action_event($event)
{
    global $__ACTIONS;
    $all_args = func_get_args();
    $return = array_slice($all_args, 1, (int) count($all_args));
    if (is_string($event) && !empty($event)) {
        if (isset($__ACTIONS[$event]) && is_array($__ACTIONS[$event])) {
            if (!empty($__ACTIONS[$event])) {
                ksort($__ACTIONS[$event]);
                foreach ($__ACTIONS[$event] AS $priority=>$functions_arr) {
                    if (!empty($functions_arr)) {
                        foreach ($functions_arr AS $function=>$accepted_arguments) {
                            $return = do_action($function, $return);
                        }
                    }
                }
            }
        }
    }
    return $return;
}

/**
 * Doing a action
 * 
 * @param string $function Function name
 * @return array 
 */
function do_action($function)
{
    $all_args = func_get_args();
    $return = array_slice($all_args, 1, (int) count($all_args));
    if (is_string($function) && !empty($function)) {
        if (function_exists($function)) {
            $return = call_user_func_array($function, array_slice($all_args, 1, (int) count($all_args)));
        }
    }
    return $return;
}


/*
function load_registred_actions()
{
    $query = new Query();
    $query->select(CMS_DB_PREFIX . CMS_DB_ACTIONS, 'event,function,priority,arguments', "WHERE did='" . mysql_escape(get_domain_id()) . "'");
    if ($query->numrows()>0) {
        while($row=$query->next()) {
            add_action($row->event, $row->function, $row->priority, $row->arguments);
        }
    }
    return true;
}

function unload_registred_actions()
{
    $query = new Query();
    $query->select(CMS_DB_PREFIX . CMS_DB_ACTIONS, 'event,function,priority,arguments', "WHERE did='" . mysql_escape(get_domain_id()) . "'");
    if ($query->numrows()>0) {
        while($row=$query->next()) {
            remove_action($row->event, $row->function, $row->priority, $row->arguments);
        }
    }
    return true;
}

function register_action($event, $function, $priority=10, $accepted_arguments=1)
{
    $query = new Query();
    $query->select(CMS_DB_PREFIX . CMS_DB_ACTIONS, 'id', "WHERE did='" . mysql_escape(get_domain_id()) . "' AND event='" . mysql_escape($event) . "' AND function='" . mysql_escape($function) . "' AND priotity='" . mysql_escape($priority) . "'");
    if ($query->numrows>0) {
        $query->update(CMS_DB_PREFIX . CMS_DB_ACTIONS, array('arguments'=>$accepted_arguments), "WHERE did='" . mysql_escape(get_domain_id()) . "' AND event='" . mysql_escape($event) . "' AND function='" . mysql_escape($function) . "' AND priotity='" . mysql_escape($priority) . "'");
    }
    else {
        $fields = array('did','event','function','priority','arguments');
        $values = array(get_domain_id(),$event,$function,$priority,$accepted_arguments);
        $query->insert(CMS_DB_PREFIX . CMS_DB_ACTIONS, $fields, $values);
    }
    add_action($event, $function, $priority, $accepted_arguments);
    return true;
}

function unregister_action($event, $function, $priority=10, $accepted_arguments=1)
{
    $query = new Query();
    $query->delete(CMS_DB_PREFIX . CMS_DB_ACTIONS, "WHERE did='" . mysql_escape(get_domain_id()) . "' AND event='" . mysql_escape($event) . "' AND function='" . mysql_escape($function) . "' AND priotity='" . mysql_escape($priority) . "'");
    remove_action($event, $function, $priority, $accepted_arguments);
}
*/
