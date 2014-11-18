<?php

function register_alert($txt, $type='info', $permanent=false)
{
    return Alerts::Register(array('name'=>$txt,'type'=>$type,'permanent'=>$permanent));
}

function get_alerts()
{
    return Alerts::GetList();
}
