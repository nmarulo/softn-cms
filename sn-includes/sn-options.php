<?php

/**
 * Gestión de las configuraciones de la aplicación.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase para implementar las configuraciones como objetos.
 * @author Nicolás Marulanda P.
 */
class SN_Options {

    /** @var int Identificador. */
    private $ID;

    /** @var string Nombre asignado. */
    private $option_name;

    /** @var string Valor. */
    private $option_value;

    /**
     * Constructor.
     * @param array|PDOStatement $arg Datos.<br/>
     * <b>NOTA: Los indices del array deben corresponder con el nombre de la tabla.</b>
     */
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

    /**
     * Metodo que borra los datos segun su id.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $id
     * @return bool
     */
    public static function delete($id) {
        global $sndb;

        return $sndb->exec(array(
                    'type' => 'DELETE',
                    'table' => 'options',
                    'where' => "ID = $id"
        ));
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

        return $sndb->query(array('table' => 'options', 'orderBy' => 'ID'), $fetch);
    }

    /**
     * Metodo que obtiene un dato segun su ID y retorna 
     * un instancia SN_Options.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $name Nombre de la configuración.
     * @return object
     */
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

    /**
     * Metodo que agrega los datos de la configuración a la base de datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return bool
     */
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

    /**
     * Metodo que actualiza los datos de la configuración.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return bool
     */
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

    /**
     * Metodo que obtiene el identificador.
     * @return int
     */
    public function getID() {
        return $this->ID;
    }

    /**
     * Metodo que obtiene el nombre asignado de la configuración.
     * @return string
     */
    public function getOption_name() {
        return $this->option_name;
    }

    /**
     * Metodo que obtiene el valor de la configuración.
     * @return string
     */
    public function getOption_value() {
        return $this->option_value;
    }

    /**
     * Metodo que establece el valor de la configuración.
     * @param string $option_value
     */
    public function setOption_value($option_value) {
        $this->option_value = $option_value;
    }

}
