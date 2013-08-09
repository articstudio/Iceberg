<?php

function get_pagetypes()
{
    return PageType::GetList();
}

function get_pagetype($id=null)
{
    return PageType::Get($id);
}
