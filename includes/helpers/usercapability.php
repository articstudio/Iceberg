<?php

function get_user_capabilities($args=array(), $lang=null)
{
    return UserCapability::GetList($args, $lang);
}

function get_user_capability($id)
{
    return UserCapability::Get($id);
}
