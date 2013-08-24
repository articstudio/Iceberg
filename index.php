<?php

/**
 * First require the Bootstrap library
 */
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'bootstrap.php' ;

/**
 * Execute Bootstrap
 */
Bootstrap::Initialize(
    array(
        'root'  => getcwd()
    )
);
