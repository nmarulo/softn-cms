<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models;

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
        $db = \SoftnCMS\controllers\DBController::getConnection();
        $table = Option::getTableName();
        $where = 'ID = :id';
        
        $this->prepare();
        //Si no hay datos, no se ejecuta la consulta.
        if(empty($this->dataColumns)){
            return \TRUE;
        }
        $this->addPrepareStatement(':id', $this->option->getID(), \PDO::PARAM_INT);
        
        if(!$db->update($table, $this->dataColumns, $where, $this->prepareStatement)){
            return \FALSE;
        }
        
        return \TRUE;
    }

    private function prepare() {
        $this->checkFields($this->option->getOptionValue(), $this->optionValue, Option::OPTION_VALUE, \PDO::PARAM_STR, \FALSE);
    }
    
    private function checkFields($oldData, $newData, $column, $dataType, $separator = \TRUE){
        if ($oldData != $newData) {
            $parameter = ':' . $column;
            $this->addSetDataSQL($column, $parameter, $separator);
            $this->addPrepareStatement($parameter, $newData, $dataType);
        }
    }

    private function addSetDataSQL($key, $data, $separator = \TRUE) {
        $this->dataColumns .= "$key = $data";
        $this->dataColumns .= $separator ? ', ' : '';
    }

    private function addPrepareStatement($parameter, $value, $dataType) {
        $this->prepareStatement[] = [
            'parameter' => $parameter,
            'value' => $value,
            'dataType' => $dataType,
        ];
    }
}
