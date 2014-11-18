<?php

function get_user_roles($args=array(), $lang=null)
{
    return UserRole::GetList($args, $lang);
}

function get_user_role($id)
{
    return UserRole::Get($id);
}
