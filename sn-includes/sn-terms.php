<?php

/**
 * Gestión de etiquetas.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase para implementar las etiquetas como objetos.
 * @author Nicolás Marulanda P.
 */
class SN_Terms {

    /** @var int Identificador de la etiqueta. */
    private $ID;

    /** @var string Nombre. */
    private $term_name;

    /** @var string Descripción. */
    private $term_description;

    /** @var int Número de publicaciones vinculadas. */
    private $term_count;

    /**
     * Constructor.
     * @param array|PDOStatement $arg Datos de la etiqueta.<br/>
     * <b>NOTA: Los indices del array deben corresponder con el nombre de la tabla.</b>
     */
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

    /**
     * Metodo que obtiene una lista con las etiquetas que contienen 
     * el texto pasado por parametro.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $str
     * @return array
     */
    public static function search($str) {
        global $sndb;

        return $sndb->query([
                    'table' => 'terms',
                    'where' => 'term_name LIKE :term_name',
                    'orderBy' => 'term_name',
                    'prepare' => [[':term_name', '%' . $str . '%'],]
                        ], 'fetchAll');
    }

    /**
     * Metodo que borra una etiqueta segun su id.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @param int $id Identificador de la etiqueta.
     * @return bool
     */
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

    /**
     * Metodo que obtiene todas las etiquetas ordenadas por nombre.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $fetch [Opcional] Tipo de datos a retornar.
     * Con "fetchObject" para retornar los datos como objetos. 
     * Por defecto, "fetchAll", retorna un array asociativo.
     * @return array|object
     */
    public static function dataList($fetch = 'fetchAll') {
        global $sndb;

        return $sndb->query(['table' => 'terms', 'orderBy' => 'term_name'], $fetch);
    }

    /**
     * Metodo que obtiene una etiqueta segun su ID y retorna 
     * un instancia SN_Terms con los datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $id Identificador de la etiqueta.
     * @return object
     */
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

    /**
     * Metodo que obtiene la ultima etiqueta.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return object Retorna un objeto PDOstatement.
     */
    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'terms',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ), 'fetchObject');
    }

    /**
     * Metodo que agrega los datos de la etiqueta a la base de datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @return bool
     */
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
                $out = true;
            }
        }

        return $out;
    }

    /**
     * Metodo que actualiza los datos de la etiqueta.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @return bool
     */
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

    /**
     * Metodo que obtiene el identificador de la etiqueta.
     * @return int
     */
    public function getID() {
        return $this->ID;
    }

    /**
     * Metodo que obtiene el nombre de la etiqueta.
     * @return string
     */
    public function getTerm_name() {
        return $this->term_name;
    }

    /**
     * Metodo que obtiene la descripción.
     * @return string
     */
    public function getTerm_description() {
        return $this->term_description;
    }

    /**
     * Metodo que obtiene el número de entradas vinculadas a la etiqueta.
     * @return type
     */
    public function getTerm_count() {
        return $this->term_count;
    }

}
