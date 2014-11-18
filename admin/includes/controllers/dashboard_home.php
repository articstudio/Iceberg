<?php

function get_actions_dashboard_home($actions)
{
    $defaults = array(
        'resume' => array(
            'template' => 'dashboard_home_resume.php',
            'name' => _T('Resume')
        )
    );
    $actions = array_merge($actions, $defaults);
    return $actions;
}
add_filter('get_actions_dashboard_home', 'get_actions_dashboard_home', 5);
