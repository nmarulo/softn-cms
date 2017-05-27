<?php
/**
 * PostController.phproller.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;

/**
 * Class PostController
 * @author Nicolás Marulanda P.
 */
class PostController extends CUDControllerAbstract {
    
    public function create() {
        ViewController::view('form');
    }
    
    public function update() {
        ViewController::view('form');
    }
    
    public function delete() {
        $this->index();
    }
    
    public function index() {
        ViewController::view('index');
    }
    
    protected function read() {
        // TODO: Implement read() method.
    }
    
    protected function form() {
        // TODO: Implement form() method.
    }
    
}
