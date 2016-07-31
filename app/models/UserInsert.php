<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models;

use SoftnCMS\models\User;

/**
 * Description of UserInsert
 *
 * @author Nicolás Marulanda P.
 */
class UserInsert {
    
    private $userLogin;
    private $userName;
    private $userEmail;
    private $userPass;
    private $userRol;
    private $userUrl;
    private static $COLUMNS = User::USER_EMAIL . ', ' . User::USER_LOGIN . ', ' . User::USER_NAME . ', ' . User::USER_PASS . ', ' . User::USER_REGISTRED . ', ' . User::USER_ROL . ', ' . User::USER_URL;
    private static $VALUES = ':' . User::USER_EMAIL . ', ' . ':' . User::USER_LOGIN . ', ' . ':' . User::USER_NAME . ', ' . ':' . User::USER_PASS . ', ' . ':' . User::USER_REGISTRED . ', ' . ':' . User::USER_ROL . ', ' . ':' . User::USER_URL;
    private $prepareStatement;

    public function __construct($userLogin, $userName, $userEmail, $userPass, $userRol, $userUrl) {
        $this->userLogin = $userLogin;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPass = $this->encrypt($userPass);
        $this->userRol = 0;
        $this->userUrl = $userUrl;
        $this->prepareStatement = [];
    }

    public function insert() {
        $db = \SoftnCMS\controllers\DBController::getConnection();
        $table = User::getTableName();
        $this->prepare();
        
        if (!$db->insert($table, self::$COLUMNS, self::$VALUES, $this->prepareStatement)) {
            return \FALSE;
        }
        return $db->lastInsertId();
    }

    private function prepare() {
        $date = \date('Y-m-d H:i:s', \time());
        $this->addPrepareStatement(':' . User::USER_LOGIN, $this->userLogin, \PDO::PARAM_STR);
        $this->addPrepareStatement(':' . User::USER_EMAIL, $this->userEmail, \PDO::PARAM_STR);
        $this->addPrepareStatement(':' . User::USER_NAME, $this->userName, \PDO::PARAM_STR);
        $this->addPrepareStatement(':' . User::USER_PASS, $this->userPass, \PDO::PARAM_STR);
        $this->addPrepareStatement(':' . User::USER_REGISTRED, $date, \PDO::PARAM_STR);
        $this->addPrepareStatement(':' . User::USER_ROL, $this->userRol, \PDO::PARAM_INT);
        $this->addPrepareStatement(':' . User::USER_URL, $this->userUrl, \PDO::PARAM_STR);
    }

    private function addPrepareStatement($parameter, $value, $dataType) {
        $this->prepareStatement[] = [
            'parameter' => $parameter,
            'value' => $value,
            'dataType' => $dataType,
        ];
    }
    
    /**
     * Metodo que realiza el HASH al valor pasado por parametro.
     * @param string $pass
     * @return string
     */
    public function encrypt($pass) {
        return hash('sha256', $pass . \LOGGED_KEY);
    }
}
