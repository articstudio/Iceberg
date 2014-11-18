<?php

function theme_backend_print_page_configuration_search_panel($template)
{
    return SEARCH_DIR_ADMIN . 'action_configuration_search_panel_template.php';
}
add_filter('theme_backend_print_page', 'theme_backend_print_page_configuration_search_panel', 10, 1);
