<?php

/**
 * Modulo modelo: Gestiona grupos de usuarios.
 */
namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\Models;

/**
 * Clase Users para gestionar grupos de usuarios.
 * @author Nicolás Marulanda P.
 */
class Users extends Models {
    
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(User::getTableName(), __CLASS__);
    }
    
    /**
     * Método que obtiene todos los usuarios de la base de datos.
     * @return Users|bool Si es FALSE, no hay datos.
     */
    public static function selectAll() {
        $select = self::select(User::getTableName());
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array $data Lista de datos.
     *
     * @return Users|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    /**
     * Método que obtiene un número limitado de datos.
     *
     * @param string $limit
     *
     * @return Users|bool Si es FALSE, no hay datos.
     */
    public static function selectByLimit($limit) {
        $select = self::select(User::getTableName(), '', [], '*', $limit);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los usuarios que coinciden con su nombre real.
     *
     * @param string $val
     *
     * @return Users|bool Si es FALSE, no hay datos.
     */
    public static function selectByName($val) {
        $value     = "%$val%";
        $parameter = ':' . User::USER_NAME;
        $where     = User::USER_NAME . " LIKE $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, \PDO::PARAM_STR);
        $select    = self::select(User::getTableName(), $where, $prepare);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los usuarios según su fecha de registro.
     *
     * @param string $value
     *
     * @return Users|bool Si es FALSE, no hay datos.
     */
    public static function selectByRegistred($value) {
        $select = self::selectBy(User::getTableName(), $value, User::USER_REGISTRED);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los usuarios según su rol asignado.
     *
     * @param int $value
     *
     * @return Users|bool Si es FALSE, no hay datos.
     */
    public static function selectByRol($value) {
        $select = self::selectBy(User::getTableName(), $value, User::USER_ROL, \PDO::PARAM_INT);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene el número total de datos.
     * @return int
     */
    public static function count() {
        return parent::countData(User::getTableName());
    }
    
    /**
     * Método que obtiene un array con los datos de los usuarios y los agrega a la lista.
     *
     * @param array $user
     */
    public function addData($user) {
        foreach ($user as $value) {
            $this->add(new User($value));
        }
    }
    
}
