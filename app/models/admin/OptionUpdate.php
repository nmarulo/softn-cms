<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;

/**
 * Description of OptionUpdate
 *
 * @author Nicolás Marulanda P.
 */
class OptionUpdate {

    /**
     *
     * @var Option 
     */
    private $option;
    private $optionValue;
    private $dataColumns;
    private $prepareStatement;

    public function __construct(Option $option, $optionValue) {
        $this->option = $option;
        $this->optionValue = $optionValue;
        $this->prepareStatement = [];
        $this->dataColumns = "";
    }

    public function update() {
        $db = DBController::getConnection();
        $table = Option::getTableName();
        $where = 'ID = :id';

        $this->prepare();
        //Si no hay datos, no se ejecuta la consulta.
        if (empty($this->dataColumns)) {
            return \TRUE;
        }

        $parameter = ':id';
        $newData = $this->option->getID();
        $dataType = \PDO::PARAM_INT;
        $this->prepareStatement[] = DBController::prepareStatement($parameter, $newData, $dataType);

        if (!$db->update($table, $this->dataColumns, $where, $this->prepareStatement)) {
            return \FALSE;
        }
        return \TRUE;
    }

    private function prepare() {
        $this->checkFields($this->option->getOptionValue(), $this->optionValue, Option::OPTION_VALUE, \PDO::PARAM_STR);
    }

    private function checkFields($oldData, $newData, $column, $dataType) {
        if ($oldData != $newData) {
            $parameter = ':' . $column;
            $this->addSetDataSQL($column, $parameter);
            $this->prepareStatement[] = DBController::prepareStatement($parameter, $newData, $dataType);
        }
    }

    private function addSetDataSQL($key, $data) {
        $this->dataColumns .= empty($this->dataColumns) ? '' : ', ';
        $this->dataColumns .= "$key = $data";
    }

}
