<?php

/**
 * Modulo modelo: Gestiona los datos de cada opción.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\Model;

/**
 * Clase Option para gestionar los datos de cada opción.
 * @author Nicolás Marulanda P.
 */
class Option extends Model {
    
    /** Nombre asignado. */
    const OPTION_NAME = 'option_name';
    
    /** Valor. */
    const OPTION_VALUE = 'option_value';
    
    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'options';
    
    /** @var array Datos. */
    private $option;
    
    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct($data) {
        $this->option = $data;
    }
    
    /**
     * Método que obtiene el nombre de la tabla.
     * @return string
     */
    public static function getTableName() {
        return self::$TABLE;
    }
    
    /**
     * Método que retorna una instancia por defecto.
     * @return Option
     */
    public static function defaultInstance() {
        $data = [
            self::ID           => 0,
            self::OPTION_NAME  => '',
            self::OPTION_VALUE => '',
        ];
        
        return new Option($data);
    }
    
    /**
     * Método que obtiene un post según su "ID".
     *
     * @param int $value Identificador del post.
     *
     * @return Option|bool Si es FALSE, no hay datos.
     */
    public static function selectByID($value) {
        $select = self::selectBy(self::$TABLE, $value, self::ID, \PDO::PARAM_INT);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array $data Lista de datos.
     *
     * @return Option|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    /**
     * Método que obtiene una opción según su nombre asignado.
     *
     * @param string $value
     *
     * @return Option
     */
    public static function selectByName($value) {
        $select = self::selectBy(self::$TABLE, $value, self::OPTION_NAME);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene el identificador.
     * @return int
     */
    public function getID() {
        return $this->option[Option::ID];
    }
    
    /**
     * Método que obtiene el nombre asignado.
     * @return string
     */
    public function getOptionName() {
        return $this->option[Option::OPTION_NAME];
    }
    
    /**
     * Método que obtiene el valor.
     * @return string
     */
    public function getOptionValue() {
        return $this->option[Option::OPTION_VALUE];
    }
    
}
