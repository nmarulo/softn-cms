<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\admin\base;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\Model;

/**
 * Description of ModelDelete
 *
 * @author MaruloPC-Desk
 */
abstract class ModelDelete {

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    protected $prepareStatement;

    /** @var int Identificador. */
    protected $id;
    
    /** @var string Nombre de la tabla. **/
    private $table;

    /**
     * Constructor.
     * @param int $id Identificador.
     * @param string $table Nombre de la tabla.
     */
    public function __construct($id, $table) {
        $this->prepareStatement = [];
        $this->id = $id;
        $this->table = $table;
    }

    /**
     * Metodo que borra el post de la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function delete() {
        $db = DBController::getConnection();
        $parameter = Model::ID;
        $where = "$parameter = :$parameter";
        $this->prepare();

        return $db->delete($this->table, $where, $this->prepareStatement);
    }

    /**
     * Metodo que establece los datos a preparar.
     */
    protected function prepare() {
        $this->addPrepare(':' . Model::ID, $this->id, \PDO::PARAM_INT);
    }

    /**
     * Metodo que guarda los datos establecidos.
     * @param string $parameter Indice a buscar. EJ: ":ID"
     * @param string $value Valor del indice.
     * @param int $dataType Tipo de dato. EJ: \PDO::PARAM_*
     */
    protected function addPrepare($parameter, $value, $dataType) {
        $this->prepareStatement[] = DBController::prepareStatement($parameter, $value, $dataType);
    }

}
