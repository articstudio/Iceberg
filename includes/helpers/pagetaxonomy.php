<?php

function get_pagetaxonomies()
{
    return PageTaxonomy::GetList();
}

function get_pagetaxonomy($id=null)
{
    return PageTaxonomy::Get($id);
}
