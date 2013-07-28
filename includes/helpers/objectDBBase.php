<?php

function db_encode($value)
{
    return ObjectDB::DB_EncodeFieldValue($value);
}

function db_decode($value)
{
    return ObjectDB::DB_DecodeFieldValue($value);
}
