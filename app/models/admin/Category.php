<?php

/**
 * Modulo modelo: Gestiona los datos de cada categoría.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\Model;
use SoftnCMS\models\admin\template\CategoryTemplate;

/**
 * Clase Category para gestionar los datos de cada categoría.
 * @author Nicolás Marulanda P.
 */
class Category extends Model {
    
    use CategoryTemplate;
    
    /** Nombre de la categoría. */
    const CATEGORY_NAME = 'category_name';
    
    /** Descripción de la categoría. */
    const CATEGORY_DESCRIPTION = 'category_description';
    
    /** Número de publicaciones vinculadas. */
    const CATEGORY_COUNT = 'category_count';
    
    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'categories';
    
    /** @var array Datos de la categoría. */
    private $category;
    
    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct($data) {
        $this->category = $data;
        $this->instance = $this;
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
     * @return Category
     */
    public static function defaultInstance() {
        $data = [
            self::ID                   => 0,
            self::CATEGORY_NAME        => '',
            self::CATEGORY_DESCRIPTION => '',
            self::CATEGORY_COUNT       => 0,
        ];
        
        return new Category($data);
    }
    
    /**
     * Método que obtiene una categoría según su "ID".
     *
     * @param int $value
     *
     * @return Category|bool
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
     * @return Category|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    /**
     * Método que obtiene una categoría según su "nombre".
     *
     * @param int $value
     *
     * @return Category|bool
     */
    public static function selectByName($value) {
        $select = self::selectBy(self::$TABLE, $value, self::CATEGORY_NAME);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene el identificador de la categoría.
     * @return int
     */
    public function getID() {
        return $this->category[self::ID];
    }
    
    /**
     * Método que obtiene el nombre de la categoría.
     * @return string
     */
    public function getCategoryName() {
        return $this->category[self::CATEGORY_NAME];
    }
    
    /**
     * Método que obtiene la descripción de la categoría.
     * @return string
     */
    public function getCategoryDescription() {
        return $this->category[self::CATEGORY_DESCRIPTION];
    }
    
    /**
     * Método que obtiene el número de entradas vinculadas a la categoría.
     * @return int
     */
    public function getCategoryCount() {
        return $this->category[self::CATEGORY_COUNT];
    }
    
}
