<?php


/**
 * First require the Bootstrap library
 */
require_once dirname( getcwd() ) . '/bootstrap.php' ;

/**
 * Execute Bootstrap
 */
Bootstrap::Initialize(
    array(
        'api'=>true,
        'root' => dirname( getcwd() )
    )
);

