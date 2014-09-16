<?php

function file_write($file, $content, $type='w', $reading=false)
{
    return File::Write($file, $content, $type, $reading);
}

function file_upload($tmp, $name)
{
    return File::Upload($tmp, $name);
}

function get_file_extension($filepath)
{
    return File::GetExtension($filepath);
}

function is_file_image_extension($filepath)
{
    return File::IsImageExtension($filepath);
}

function get_file_name($filepath)
{
    return File::GetName($filepath);
}

function get_file_url($path, $dir, $url)
{
    return File::GetURL($path, $dir, $url);
}
