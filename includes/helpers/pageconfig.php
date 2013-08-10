<?php

function get_default_pagetaxnomy()
{
    return PageConfig::GetDefaultTaxonomy();
}

function get_default_pagegroup()
{
    return PageConfig::GetDefaultGroup();
}

function get_page_pagetype()
{
    return PageConfig::GetPageType();
}
