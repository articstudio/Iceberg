<?php

function get_objtaxonomy_list($key)
{
    $args = array(
        'name' => $key
    );
    return ObjectTaxonomy::GetList($args);
}

function get_objtaxonomy($id=null)
{
    return ObjectTaxonomy::Get($id);
}
