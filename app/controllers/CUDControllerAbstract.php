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
    
    public abstract function update($id);
    
    public abstract function delete($id);
    
    protected abstract function form();
    
    protected abstract function filterInputs();
}
