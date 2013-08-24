<?php

/**
 * First require the Bootstrap library
 */
require_once dirname( getcwd() ) . DIRECTORY_SEPARATOR . 'bootstrap.php' ;

/**
 * Execute Bootstrap
 */
Bootstrap::Initialize(
    array(
        'admin' => true,
        'root'  => dirname( getcwd() )
    )
);

