<?php
/**
 * CUDControllerAbstract.php
 */

namespace SoftnCMS\controllers;

use SoftnCMS\util\Arrays;

/**
 * Class CUDControllerAbstract
 * @author Nicolás Marulanda P.
 */
abstract class CUDControllerAbstract extends ControllerAbstract {
    
    public abstract function create();
    
    public abstract function update($id);
    
    public function delete($id){
        $isCallAJAX   = Arrays::get($_POST, 'deleteAJAX');
        
        if (empty($isCallAJAX)) {
            $this->index();
        } else {
            ViewController::singleViewByDirectory('messages');
        }
    }
    
    protected abstract function form();
    
    protected abstract function filterInputs();
}
