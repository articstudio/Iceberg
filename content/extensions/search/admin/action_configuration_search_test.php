<?php

function theme_backend_print_page_configuration_search_test($template)
{
    return SEARCH_DIR_ADMIN . 'action_configuration_search_test_template.php';
}
add_filter('theme_backend_print_page', 'theme_backend_print_page_configuration_search_test', 10, 1);

/**************************************************************/




/**************************************************************/

register_alert('Test success', 'success');
locate(get_admin_action_link(array('action'=>'panel')));

