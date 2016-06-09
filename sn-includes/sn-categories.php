<?php

/**
 * Gestión de categorias.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase para implementar las categorias como objetos.
 * @author Nicolás Marulanda P.
 */
class SN_Categories {

    private $ID;
    private $category_name;
    private $category_description;
    private $category_count;

    /**
     * Constructor.
     * @param array|PDOStatement $arg Datos de la categoria.<br/>
     * <b>NOTA: Los indices del array deben corresponder con el nombre de la tabla.</b>
     */
    public function __construct($arg) {
        if (is_object($arg)) {
            $this->ID = $arg->ID;
            $this->category_name = $arg->category_name;
            $this->category_description = $arg->category_description;
            $this->category_count = $arg->category_count;
        } elseif (is_array($arg)) {
            $default = array(
                'ID' => 0,
                'category_name' => '',
                'category_description' => '',
                'category_count' => 0
            );

            $default = array_merge($default, $arg);

            $this->ID = $default['ID'];
            $this->category_name = $default['category_name'];
            $this->category_description = $default['category_description'];
            $this->category_count = $default['category_count'];
        } else {
            echo 'ERROR. Tipo de parametro incorrecto.';
        }
    }

    public static function search($str) {
        global $sndb;

        return $sndb->query([
                    'table' => 'categories',
                    'where' => 'category_name LIKE :category_name',
                    'orderBy' => 'category_name',
                    'prepare' => [[':category_name', '%' . $str . '%'],]
                        ], 'fetchAll');
    }

    public static function delete($id) {
        global $sndb, $dataTable;

        $out = $sndb->exec([
            'type' => 'DELETE',
            'table' => 'categories',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id]],
        ]);

        if ($out) {
            $dataTable['category']['dataList'] = SN_Categories::dataList();
        }

        return $out;
    }

    public static function dataList($fetch = 'fetchAll') {
        global $sndb;

        return $sndb->query(['table' => 'categories', 'orderBy' => 'category_name',], $fetch);
    }

    public static function get_instance($id) {
        global $sndb;

        $out = $sndb->query([
            'table' => 'categories',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id]],
                ], 'fetchObject');

        if ($out) {
            $out = new SN_Categories($out);
        }

        return $out;
    }

    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'categories',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ), 'fetchObject');
    }

    public function insert() {
        global $sndb, $dataTable;

        $out = $sndb->exec([
            'type' => 'INSERT',
            'table' => 'categories',
            'column' => 'category_name, category_description, category_count',
            'values' => ':category_name, :category_description, :category_count',
            'prepare' => [
                [':category_name', $this->category_name],
                [':category_description', $this->category_description],
                [':category_count', $this->category_count],
            ],
        ]);

        if ($out) {
            $dataTable['category']['dataList'] = SN_Categories::dataList();
        }

        return $out;
    }

    public function update() {
        global $sndb, $dataTable;

        $out = $sndb->exec([
            'type' => 'UPDATE',
            'table' => 'categories',
            'set' => 'category_name = :category_name, category_description = :category_description',
            'where' => 'ID = :ID',
            'prepare' => [
                [':ID', $this->ID],
                [':category_name', $this->category_name],
                [':category_description', $this->category_description],
            ],
        ]);

        if ($out) {
            $dataTable['category']['dataList'] = SN_Categories::dataList();
        }

        return $out;
    }

    public function getID() {
        return $this->ID;
    }

    public function getCategory_name() {
        return $this->category_name;
    }

    public function getCategory_description() {
        return $this->category_description;
    }

    public function getCategory_count() {
        return $this->category_count;
    }

}
