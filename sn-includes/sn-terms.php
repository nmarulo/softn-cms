<?php

/**
 * Description of sn-terms
 *
 * @author marulo
 */
class SN_Terms {

    private $ID;
    private $term_name;
    private $term_description;
    private $term_count;

    public function __construct($arg) {
        if (is_object($arg)) {
            $this->ID = $arg->ID;
            $this->term_name = $arg->term_name;
            $this->term_description = $arg->term_description;
            $this->term_count = $arg->term_count;
        } elseif (is_array($arg)) {
            $default = array(
                'ID' => 0,
                'term_name' => '',
                'term_description' => '',
                'term_count' => 0
            );

            $default = array_merge($default, $arg);

            $this->ID = $default['ID'];
            $this->term_name = $default['term_name'];
            $this->term_description = $default['term_description'];
            $this->term_count = $default['term_count'];
        } else {
            echo 'ERROR. Tipo de parametro incorrecto.';
        }
    }

    public static function search($str) {
        global $sndb;

        return $sndb->query([
                    'table' => 'terms',
                    'where' => 'term_name LIKE :term_name',
                    'orderBy' => 'term_name',
                    'prepare' => [[':term_name', '%' . $str . '%'],]
                        ], 'fetchAll');
    }

    public static function delete($id) {
        global $sndb, $dataTable;

        $out = $sndb->exec([
            'type' => 'DELETE',
            'table' => 'terms',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id]],
        ]);

        if ($out) {
            $dataTable['term']['dataList'] = SN_Terms::dataList();
        }

        return $out;
    }

    public static function dataList($fetch = 'fetchAll') {
        global $sndb;

        return $sndb->query(['table' => 'terms', 'orderBy' => 'term_name'], $fetch);
    }

    public static function get_instance($id) {
        global $sndb;

        $out = $sndb->query(array(
            'table' => 'terms',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id]],
                ), 'fetchObject');

        if ($out) {
            $out = new SN_Terms($out);
        }
        return $out;
    }

    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'terms',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ), 'fetchObject');
    }

    public function insert() {
        global $sndb, $dataTable;

        $out = $sndb->exec([
            'type' => 'INSERT',
            'table' => 'terms',
            'column' => 'term_name, term_description, term_count',
            'values' => ':term_name, :term_description, :term_count',
            'prepare' => [
                [':term_name', $this->term_name],
                [':term_description', $this->term_description],
                [':term_count', $this->term_count],
            ]
        ]);

        if ($out) {
            $out = SN_Terms::get_lastInsert();
            if ($out) {
                $this->ID = $out->ID;
                $dataTable['term']['dataList'] = SN_Terms::dataList();
            }
        }

        return $out;
    }

    public function update() {
        global $sndb, $dataTable;

        $out = $sndb->exec([
            'type' => 'UPDATE',
            'table' => 'terms',
            'set' => 'term_name = :term_name, term_description = :term_description',
            'where' => 'ID = :ID',
            'prepare' => [
                [':ID', $this->ID],
                [':term_name', $this->term_name],
                [':term_description', $this->term_description],
            ]
        ]);

        if ($out) {
            $dataTable['term']['dataList'] = SN_Terms::dataList();
        }

        return $out;
    }

    public function getID() {
        return $this->ID;
    }

    public function getTerm_name() {
        return $this->term_name;
    }

    public function getTerm_description() {
        return $this->term_description;
    }

    public function getTerm_count() {
        return $this->term_count;
    }

}
