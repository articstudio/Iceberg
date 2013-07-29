<?php

function get_pagetypes()
{
    return PageGroup::GetList();
}

function get_pagetype($id=null)
{
    return PageType::Get($id);
}
