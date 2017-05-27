<?php
/**
 * CUDControllerAbstract.php
 */

namespace SoftnCMS\controllers;

/**
 * Class CUDControllerAbstract
 * @author Nicolás Marulanda P.
 */
abstract class CUDControllerAbstract extends ControllerAbstract {
    
    public abstract function create();
    
    public abstract function update();
    
    public abstract function delete();
    
    protected abstract function form();
}
