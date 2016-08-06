<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\Login;

/**
 * Description of LoginController
 *
 * @author Nicolás Marulanda P.
 */
class LoginController {

    public function index() {
        return ['data' => $this->dataIndex()];
    }

    private function dataIndex() {
        if (\filter_input(\INPUT_POST, 'login')) {
            $dataInput = $this->getDataInput();

            if ($dataInput['userLogin'] && $dataInput['userPass']) {
                $login = new Login($dataInput['userLogin'], $dataInput['userPass'], $dataInput['userRememberMe']);
                $login->login();
            }
        }
        return [];
    }

    private function getDataInput() {
        return [
            'userLogin' => \filter_input(\INPUT_POST, 'userLogin'),
            'userPass' => \filter_input(\INPUT_POST, 'userPass'),
            'userRememberMe' => \filter_input(\INPUT_POST, 'userRememberMe'),
        ];
    }

}
