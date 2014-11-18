<?php

function get_root_user_role()
{
    return UserConfig::GetRootRole();
}

function get_default_user_role()
{
    return UserConfig::GetDefaultRole();
}
