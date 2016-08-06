<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers;
use SoftnCMS\models\MySql;

/**
 * Description of DBController
 *
 * @author Nicolás Marulanda P.
 */
class DBController {

    public static function getConnection() {
        $connection = null;

        switch (\DB_TYPE) {
            case 'mysql':
                $connection = new MySql();
                break;
        }
        return $connection;
    }
    
    public static function prepareStatement($parameter, $value, $dataType) {
        return [
            'parameter' => $parameter,
            'value' => $value,
            'dataType' => $dataType,
        ];
    }

}
