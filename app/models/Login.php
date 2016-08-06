<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models;

use SoftnCMS\models\User;

/**
 * Description of Login
 *
 * @author Nicolás Marulanda P.
 */
class Login {

    private $username;
    private $password;
    private $userRememberMe;

    public function __construct($username, $password, $userRememberMe) {
        $this->username = $username;
        $this->password = $password;
        $this->userRememberMe = $userRememberMe;
    }

    public static function isLogin() {
        if (!isset($_SESSION['usernameID']) && !isset($_COOKIE['userRememberMe'])) {
            return \FALSE;
        }

        if (!isset($_SESSION['usernameID']) && isset($_COOKIE['userRememberMe'])) {
            $_SESSION['username'] = $_COOKIE['userRememberMe'];
        }
        return \TRUE;
    }

    public function login() {
        $user = $this->select();

        if (!empty($user)) {
            $_SESSION['usernameID'] = $user->getID();

            if ($this->userRememberMe) {
                setcookie('userRememberMe', $user->getID(), \COOKIE_EXPIRE);
            }
            \header('Location: ' . \LOCALHOST . 'admin');
            exit();
        }
    }

    private function select() {
        $db = \SoftnCMS\controllers\DBController::getConnection();
        $table = User::getTableName();
        $fetch = 'fetchAll';
        $userName = User::USER_LOGIN;
        $where = "$userName = :$userName";
        $prepare = [
            [
                'parameter' => ":$userName",
                'value' => $this->username,
                'dataType' => \PDO::PARAM_STR,
            ]
        ];
        $columns = '*';
        $orderBy = 'ID DESC';
        $limit = 1;
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);

        if (empty($select)) {
            return \FALSE;
        }
        return new User($select[0]);
    }

}
