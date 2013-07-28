<?php

/**
 * Get extensions list
 * 
 * @uses Extension::GetExtensionsList()
 * @return array 
 */
function get_extensions_list()
{
    return Extension::GetExtensionsList();
}

/**
 * Active extension
 * 
 * @uses Extension::Active()
 * @param string $dirname
 * @return boolean 
 */
function active_extension($dirname)
{
    return Extension::Active($dirname);
}

/**
 * Unactive extension
 * 
 * @uses Extension::Unactive()
 * @param string $dirname
 * @return boolean 
 */
function unactive_extension($dirname)
{
    return Extension::Unactive($dirname);
}
