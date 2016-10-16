<?php

/**
 * Modulo del modelo de etiquetas.
 * Gestiona los datos de cada etiqueta.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\Model;
use SoftnCMS\models\admin\template\TermTemplate;

/**
 * Clase que gestiona los datos de cada etiqueta.
 * @author Nicolás Marulanda P.
 */
class Term extends Model {
    
    use TermTemplate;
    
    /** Nombre de la etiqueta. */
    const TERM_NAME = 'term_name';
    
    /** Descripción de la etiqueta. */
    const TERM_DESCRIPTION = 'term_description';
    
    /** Número de publicaciones vinculadas. */
    const TERM_COUNT = 'term_count';
    
    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'terms';
    
    /** @var array Datos de la etiqueta. */
    private $term;
    
    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct($data) {
        $this->term     = $data;
        $this->instance = $this;
    }
    
    /**
     * Metodo que obtiene el nombre de la tabla.
     * @return string
     */
    public static function getTableName() {
        return self::$TABLE;
    }
    
    /**
     * Metodo que retorna una instancia por defecto.
     * @return Term
     */
    public static function defaultInstance() {
        $data = [
            self::ID               => 0,
            self::TERM_NAME        => '',
            self::TERM_DESCRIPTION => '',
            self::TERM_COUNT       => 0,
        ];
        
        return new Term($data);
    }
    
    /**
     * Metodo que obtiene una etiqueta segun su "ID".
     *
     * @param int $value
     *
     * @return Term|bool
     */
    public static function selectByID($value) {
        $select = self::selectBy(self::$TABLE, $value, self::ID, \PDO::PARAM_INT);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Metodo que recibe un lista de datos y retorna un instancia.
     *
     * @param array $data Lista de datos.
     *
     * @return Term|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    /**
     * Metodo que obtiene una etiqueta segun su "nombre".
     *
     * @param int $value
     *
     * @return Term|bool
     */
    public static function selectByName($value) {
        $select = self::selectBy(self::$TABLE, $value, self::TERM_NAME);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Metodo que obtiene el identificador de la etiqueta.
     * @return int
     */
    public function getID() {
        return $this->term[self::ID];
    }
    
    /**
     * Metodo que obtiene el nombre de la etiqueta.
     * @return string
     */
    public function getTermName() {
        return $this->term[self::TERM_NAME];
    }
    
    /**
     * Metodo que obtiene la descripción de la etiqueta.
     * @return string
     */
    public function getTermDescription() {
        return $this->term[self::TERM_DESCRIPTION];
    }
    
    /**
     * Metodo que obtiene el número de entradas vinculadas a la etiqueta.
     * @return int
     */
    public function getTermCount() {
        return $this->term[self::TERM_COUNT];
    }
    
}
