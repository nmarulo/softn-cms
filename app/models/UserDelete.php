<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models;

use SoftnCMS\models\User;

/**
 * Description of UserDelete
 *
 * @author Nicolás Marulanda P.
 */
class UserDelete {
    
    private $id;
    private $prepareStatement;

    public function __construct($id) {
        $this->id = $id;
        $this->prepareStatement = [];
    }

    public function delete() {
        $db = \SoftnCMS\controllers\DBController::getConnection();
        $table = User::getTableName();
        $where = 'ID = :id';
        
        $this->addPrepareStatement(':id', $this->id, \PDO::PARAM_INT);
        return $db->delete($table, $where, $this->prepareStatement);
    }
    
    private function addPrepareStatement($parameter, $value, $dataType) {
        $this->prepareStatement[] = [
            'parameter' => $parameter,
            'value' => $value,
            'dataType' => $dataType,
        ];
    }
    
}
