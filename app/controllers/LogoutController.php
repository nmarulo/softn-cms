<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\Login;

/**
 * Description of LogoutController
 *
 * @author Nicolás Marulanda P.
 */
class LogoutController {

    public function index() {
        return ['data' => $this->dataIndex()];
    }

    private function dataIndex() {
        if (Login::isLogin()) {
            unset($_SESSION['usernameID']);

            if (isset($_COOKIE['userRememberMe'])) {
                setcookie('userRememberMe', '', time() - 10);
                /** Tiempo de espera para que las cookies se eliminen. */
                usleep(2000);
            }
        }
        header('Location: ' . \LOCALHOST . 'login');
        exit();
    }

}
