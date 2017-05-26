<?php

//http://php.net/manual/es/spl.exceptions.php
/**
 * Excepción base.
 */

namespace SoftnCMS\controllers\exceptions;

/**
 * Clase que reprecenta una excepción generica para todos los propositos.
 *
 * @author Nicolás Marulanda P.
 */
class ExceptionBase extends \Exception {

    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
