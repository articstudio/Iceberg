<?php

/** Include helpers Exception file */
require_once ICEBERG_DIR_HELPERS . 'exception.php';

/**
 * Iceberg Exception Base Interfice
 * 
 * Iceberg Exception Base Interfice
 *  
 * @package Iceberg
 * @subpackage Exception
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 * @todo ErrorException
 */
interface IcebergExceptionBaseInterface
{
    //static public function Handler();
}


/**
 * Iceberg Exception Base
 * 
 * Iceberg Exception Base
 *  
 * @package Iceberg
 * @subpackage IcebergException
 * @extends Exception
 * @implements IcebergExceptionBaseInterface
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 */
abstract class IcebergExceptionBase extends Exception implements IcebergExceptionBaseInterface
{
    const STRICT = 'E_STRICT';
    const NOTICE = 'E_NOTICE';
    const WARNING = 'E_WARNING';
    const ERROR = 'E_ERROR';
    
    static public function Handler($exception)
    {
        echo "Uncaught exception: " , $exception->getMessage(), "\n";
    }
}


/**
 * Iceberg Exception
 * 
 * Iceberg Exception
 *  
 * @package Iceberg
 * @subpackage IcebergException
 * @extends IcebergExceptionBase
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 */
class IcebergException extends IcebergExceptionBase
{
    /*
    function __construct($message = '', $code = 0, $previous = null) {
        //parent::__construct($message, $code, $previous);
        
        //print $this->__toString();
        
    }
    */
}