<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers;
use SoftnCMS\models\Posts;

/**
 * Description of PostController
 *
 * @author Nicolás Marulanda P.
 */
class PostController {
    
    public function index(){
        $posts = new Posts();
        return ['posts' => $posts->getPosts()];
    }
    
    public function update($id){
        return __METHOD__;
    }
        
    public function delete($id){
        return __METHOD__;
    }
    
    public function insert(){
        return __METHOD__;
    }
}
