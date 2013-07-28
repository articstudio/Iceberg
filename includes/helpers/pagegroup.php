<?php

function get_pagegroups()
{
    return PageGroup::GetList();
}

function get_pagegroup($id=null)
{
    return PageGroup::Get($id);
}
