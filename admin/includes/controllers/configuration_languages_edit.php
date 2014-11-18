<?php

function get_action_configuration_languages_edit($action, $key)
{
    $id = get_request_id();
    $language = I18N::GetLanguageInfo($id);
    $default = array(
        'name' => _T('Edit') . ': ' . $language['name']
    );
    $action = array_merge($action, $default);
    return $action;
}
add_filter('get_action_configuration_languages_edit', 'get_action_configuration_languages_edit', 5, 2);
