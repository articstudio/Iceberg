<?php

$from = get_request_p('fromPosition');
$to = get_request_p('toPosition');
$class = 'ObjectTaxonomy';
if ($mode === PageGroup::$TAXONOMY_KEY)
{
    $class = 'PageGroup';
}
else if ($mode === PageType::$TAXONOMY_KEY)
{
    $class = 'PageType';
}
else if ($mode === PageTaxonomy::$TAXONOMY_KEY)
{
    $class = 'PageTaxonomy';
}

if ($from!==false && $to!==false)
{
    $from = (int)$from ;
    $to = (int)$to;
    if ($class::ReOrder($from, $to))
    {
        add_env_alert('Taxonomy item reordered', 'success');
    }
    else
    {
        add_env_alert('Failed to reorder taxonomy item', 'error');
    }
}
else
{
    add_env_alert('Failed to reorder taxonomy item', 'error');
}
