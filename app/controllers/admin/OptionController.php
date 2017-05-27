<?php
/**
 * OptionController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\ViewController;

/**
 * Class OptionController
 * @author Nicolás Marulanda P.
 */
class OptionController extends ControllerAbstract {
    
    public function index() {
        ViewController::view('index');
    }
    
    protected function read() {
        // TODO: Implement read() method.
    }
    
}
