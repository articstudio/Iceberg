<?php

/** Include helpers Exception file */
require_once ICEBERG_DIR_HELPERS . 'security.php';

/**
 * Description of IcebergSecurity
 *
 * @author Marc
 */
class IcebergSecurity extends IcebergSingleton
{
    
    const REQUEST_KEY_NONCE = 'nonce';
    
    public static function MakeNonce($name)
    {
        $nonce_key = $name . ICEBERG_NONCE . $name;
        $nonce = md5($nonce_key);
        $nonce = apply_filters('make_nonce', $nonce, $name);
        return $nonce;
    }
    
    public static function CheckNonce($name, $check_nonce)
    {
        $nonce = static::MakeNonce($name);
        return ($nonce === $check_nonce);
    }
    
}
