<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sn-options
 *
 * @author marulo
 */
class SN_Options {

    private $ID;
    private $option_name;
    private $option_value;

    public function __construct($arg) {
        if (is_object($arg)) {
            $this->ID = $arg->ID;
            $this->option_name = $arg->option_name;
            $this->option_value = $arg->option_value;
        } elseif (is_array($arg)) {
            $default = array(
                'ID' => 0,
                'option_name' => '',
                'option_value' => '',
            );

            $default = array_merge($default, $arg);

            $this->ID = $default['ID'];
            $this->option_name = $default['option_name'];
            $this->option_value = $default['option_value'];
        } else {
            echo 'ERROR. Tipo de parametro incorrecto.';
        }
    }

    public static function delete($id) {
        global $sndb;

        return $sndb->exec(array(
                    'type' => 'DELETE',
                    'table' => 'options',
                    'where' => "ID = $id"
        ));
    }

    public static function dataList($fetch = 'fetchAll') {
        global $sndb;

        return $sndb->query(array('table' => 'options', 'orderBy' => 'ID'), $fetch);
    }

    public static function get_instance($name) {
        global $sndb;

        $out = $sndb->query([
            'table' => 'options',
            'where' => 'option_name = :option_name',
            'prepare' => [[':option_name', $name]],
                ], 'fetchObject');

        if ($out) {
            $out = new SN_Options($out);
        }

        return $out;
    }

    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'options',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ), 'fetchObject');
    }

    public function insert() {
        global $sndb;

        return $sndb->exec([
                    'type' => 'INSERT',
                    'table' => 'options',
                    'column' => 'option_name, option_value',
                    'values' => ":option_name, :option_value",
                    'prepare' => [
                        [':option_name', $this->option_name],
                        [':option_value', $this->option_value],
                    ],
        ]);
    }

    public function update() {
        global $sndb;

        return $sndb->exec([
                    'type' => 'UPDATE',
                    'table' => 'options',
                    'set' => "option_value = :option_value",
                    'where' => "ID = :ID",
                    'prepare' => [
                        [':option_value', $this->option_value],
                        [':ID', $this->ID],
                    ],
        ]);
    }

    public function getID() {
        return $this->ID;
    }

    public function getOption_name() {
        return $this->option_name;
    }

    public function getOption_value() {
        return $this->option_value;
    }

    public function setOption_value($option_value) {
        $this->option_value = $option_value;
    }

}
