<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\Register;

/**
 * Description of RegisterController
 *
 * @author MaruloPC-Desk
 */
class RegisterController {

    public function index() {
        return ['data' => $this->dataIndex()];
    }

    private function dataIndex() {
        if (\filter_input(\INPUT_POST, 'register')) {
            $dataInput = $this->getDataInput();
            
            if($this->checkPasswords($dataInput)){
                $register = new Register($dataInput['userLogin'], $dataInput['userEmail'], $dataInput['userPass']);
                $register->register();
            }
        }

        return [];
    }
    
    private function checkPasswords($dataInput){
        if($dataInput['userPass'] && $dataInput['userPassR'] && $dataInput['userPass'] == $dataInput['userPassR']){
            return \TRUE;
        }
        return \FALSE;
    }

    private function getDataInput() {
        return [
            'userLogin' => \filter_input(\INPUT_POST, 'userLogin'),
            'userPass' => \filter_input(\INPUT_POST, 'userPass'),
            'userPassR' => \filter_input(\INPUT_POST, 'userPassR'),
            'userEmail' => \filter_input(\INPUT_POST, 'userEmail'),
        ];
    }

}
