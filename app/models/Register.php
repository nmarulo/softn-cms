<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models;

use SoftnCMS\models\admin\User;
use SoftnCMS\models\admin\UserInsert;

/**
 * Description of Register
 *
 * @author MaruloPC-Desk
 */
class Register {
    
    private $username;
    private $userEmail;
    private $userpass;
    
    public function __construct($username, $userEmail, $userpass) {
        $this->username = $username;
        $this->userEmail = $userEmail;
        $this->userpass = $userpass;
    }
    
    public function register(){
        if(!$this->isExistsUsername() && !$this->isExistsUserEmail()){
            $user = User::defaultInstance();
            $register = new UserInsert($this->username, $this->username, $this->userEmail, $this->userpass, $user->getUserRol(), $user->getUserUrl());
            
            if($register->insert() !== \FALSE){
                \header('Location: ' . \LOCALHOST . 'login');
                exit();
            }
        }
        return \FALSE;
    }
    
    private function isExistsUsername(){
        $user = User::selectByLogin($this->username);
        return $user !== \FALSE;        
    }
    
    private function isExistsUserEmail(){
        $user = User::selectByEmail($this->userEmail);
        return $user !== \FALSE;
    }
    
}
