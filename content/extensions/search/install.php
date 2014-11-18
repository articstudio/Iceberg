<?php

$found = false;
$table = PageMeta::DB_GetTableName();

$query = new Query();
$query->show_indexes($table);
while ($row = $query->next(MYSQL_ROW_AS_OBJECT))
{
    if ($row->Column_name === 'value' && $row->Index_type === 'FULLTEXT')
    {
        $found= true;
        break;
    }
}

if (!$found)
{
    $sql = "ALTER TABLE " . $table ." ADD FULLTEXT (value)";
    $done = $query->Query($sql);
    if (!$done)
    {
        register_alert('Error creating full text index', 'error');
    }
}
