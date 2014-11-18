<?php

function search_get_actions_configuration_search($arr)
{
    $array = array(
        'panel' => array(
            'template' => SEARCH_DIR_ADMIN . 'action_configuration_search_panel.php',
            'name' => _T('Panel')
        ),
        'save' => array(
            'template' => SEARCH_DIR_ADMIN . 'action_configuration_search_save.php',
            'name' => _T('Save')
        ),
        'generate' => array(
            'template' => SEARCH_DIR_ADMIN . 'action_configuration_search_generate.php',
            'name' => _T('Generate')
        ),
        'test' => array(
            'template' => SEARCH_DIR_ADMIN . 'action_configuration_search_test.php',
            'name' => _T('Generate')
        )
    );
    $arr = array_merge($arr, $array);
    return $arr;
}
add_filter('get_actions_configuration_search', 'search_get_actions_configuration_search', 10, 1);
