<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace SoftnCMS\controllers\themes;
/**
 * Description of IndexController
 *
 * @author MaruloPC-Desk
 */
class IndexController {
    
    
    public function index(){
        return ['data' => $this->dataIndex()];
    }
    
    private function dataIndex(){
        return ['index' => 'theme index'];
    }
}
