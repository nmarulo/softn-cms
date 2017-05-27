<?php
/**
 * ControllerAbstract.php
 */

namespace SoftnCMS\controllers;

/**
 * Class ControllerAbstract
 * @author Nicolás Marulanda P.
 */
abstract class ControllerAbstract {
    
    public abstract function index();
    
    protected abstract function read();
    
}
