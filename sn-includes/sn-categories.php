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

    /** @var int Identificador de la categoría. */
    private $ID;

    /** @var string Nombre de la categoría. */
    private $category_name;

    /** @var string Descripción de la categoría. */
    private $category_description;

    /** @var int Número de publicaciones vinculadas. */
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

    /**
     * Metodo que obtiene una lista con las categorias que contienen 
     * el texto pasado por parametro.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $str
     * @return array
     */
    public static function search($str) {
        global $sndb;

        return $sndb->query([
                    'table' => 'categories',
                    'where' => 'category_name LIKE :category_name',
                    'orderBy' => 'category_name',
                    'prepare' => [[':category_name', '%' . $str . '%'],]
                        ], 'fetchAll');
    }

    /**
     * Metodo que borra una categoria segun su id.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @param int $id Identificador de la categoría.
     * @return bool
     */
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

    /**
     * Metodo que obtiene todas las categorías ordenadas por nombre.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $fetch [Opcional] Tipo de datos a retornar.
     * Con "fetchObject" para retornar los datos como objetos. 
     * Por defecto, "fetchAll", retorna un array asociativo.
     * @return array|object
     */
    public static function dataList($fetch = 'fetchAll') {
        global $sndb;

        return $sndb->query(['table' => 'categories', 'orderBy' => 'category_name',], $fetch);
    }

    /**
     * Metodo que obtiene una categoría segun su ID y retorna 
     * un instancia SN_Categories con los datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $id Identificador de la categoría.
     * @return object
     */
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

    /**
     * Metodo que obtiene la ultima categoría.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return object Retorna un objeto PDOstatement.
     */
    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'categories',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ), 'fetchObject');
    }

    /**
     * Metodo que agrega los datos de la categoría a la base de datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @return bool
     */
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

    /**
     * Metodo que actualiza los datos de la categoría.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @return bool
     */
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

    /**
     * Metodo que obtiene el identificador de la categoría.
     * @return int
     */
    public function getID() {
        return $this->ID;
    }

    /**
     * Metodo que obtiene el nombre de la categoría.
     * @return string
     */
    public function getCategory_name() {
        return $this->category_name;
    }

    /**
     * Metodo que obtiene la descripción de la categoría.
     * @return string
     */
    public function getCategory_description() {
        return $this->category_description;
    }

    /**
     * Metodo que obtiene el número de entradas vinculadas a la categoría.
     * @return int
     */
    public function getCategory_count() {
        return $this->category_count;
    }

}
