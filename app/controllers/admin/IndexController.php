<?php
/**
 * IndexController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\ViewController;

/**
 * Class IndexController
 * @author Nicolás Marulanda P.
 */
class IndexController extends ControllerAbstract {
    
    public function index() {
        ViewController::view('index');
    }
    
    protected function read() {
        // TODO: Implement read() method.
    }
    
}
