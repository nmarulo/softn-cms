<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Post;
use SoftnCMS\controllers\DBController;

/**
 * Description of PostDelete
 *
 * @author MaruloPC-Desk
 */
class PostDelete {

    private $id;

    public function __construct($id) {
        $this->id = $id;
    }

    public function delete() {
        $db = DBController::getConnection();
        $table = Post::getTableName();
        $parameter = ':id';
        $where = "ID = $parameter";
        $newData = $this->id;
        $dataType = \PDO::PARAM_INT;
        $prepare = [
            DBController::prepareStatement($parameter, $newData, $dataType)
        ];
        return $db->delete($table, $where, $prepare);
    }

}
