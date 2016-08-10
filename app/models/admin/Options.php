<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Option;
use SoftnCMS\controllers\DBController;

/**
 * Description of Options
 *
 * @author Nicolás Marulanda P.
 */
class Options {

    /**
     * Lista, donde el indice o clave corresponde al ID.
     * @var array 
     */
    private $options;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->options = [];
    }

    /**
     * 
     * @return Options
     */
    public static function selectAll() {
        return self::select();
    }

    /**
     * 
     * @param type $where
     * @param type $prepare
     * @param type $columns
     * @param type $limit
     * @param type $orderBy
     * @return Options
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = '', $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = Option::getTableName();
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);
        $options = new Options();
        $options->addOptions($select);
        return $options;
    }

    /**
     * Metodo que obtiene todos los options.
     * @return array
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * Metodo que obtiene, segun su ID, un post.
     * @param string $optionName
     * @return Option
     */
    public function getOption($optionName) {
        return $this->options[$optionName];
    }

    /**
     * Metodo que agrega un post a la lista.
     * @param Option $option
     */
    public function addOption(Option $option) {
        $this->options[$option->getOptionName()] = $option;
    }

    /**
     * Metodo que obtiene un array con los datos de los post y los agrega a la lista.
     * @param array $option
     */
    public function addOptions($option) {
        foreach ($option as $value) {
            $this->addOption(new Option($value));
        }
    }

}
