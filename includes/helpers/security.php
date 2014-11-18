<?php

function nonce_make($name)
{
    return IcebergSecurity::MakeNonce($name);
}

function nonce_check($name, $check_nonce)
{
    return IcebergSecurity::CheckNonce($name, $check_nonce);
}
