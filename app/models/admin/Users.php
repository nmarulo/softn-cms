<?php

/**
 * Modulo del modelo usuario.
 * Gestiona grupos de usuarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\User;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona grupos de usuarios.
 *
 * @author Nicolás Marulanda P.
 */
class Users {

    /**
     * Lista, donde el indice o clave corresponde al ID.
     * @var array 
     */
    private $users;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->users = [];
    }

    /**
     * Metodo que obtiene todos los usuarios de la base de datos.
     * @return Users
     */
    public static function selectAll() {
        return self::select();
    }

    /**
     * Metodo que obtiene todos los usuarios que coinciden con su nombre real.
     * @param string $val
     * @return Users
     */
    public static function selectByName($val) {
        $value = "%$val%";
        $parameter = ':' . User::USER_NAME;
        $where = User::USER_NAME . " LIKE $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, \PDO::PARAM_STR);

        return self::select($where, $prepare);
    }

    /**
     * Metodo que obtiene todos los usuarios segun su fecha de registro.
     * @param string $value
     * @return Users
     */
    public static function selectByRegistred($value) {
        return self::selectBy($value, User::USER_REGISTRED);
    }

    /**
     * Metodo que obtiene todos los usuarios segun su rol asignado.
     * @param int $value
     * @return Users
     */
    public static function selectByRol($value) {
        return self::selectBy($value, User::USER_ROL, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene los usuarios segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType [Opcional] Por defecto \PDO::PARAM_STR. Tipo de dato.
     * @return Users
     */
    private static function selectBy($value, $column, $dataType = \PDO::PARAM_STR) {
        $parameter = ":$column";
        $where = "$column = $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, $dataType);

        return self::select($where, $prepare);
    }

    /**
     * Metodo que realiza una consulta a la base de datos.
     * @param string $where [Opcional] Condiciones.
     * @param array $prepare [Opcional] Lista de indices a reemplazar en la consulta.
     * @param string $columns [Opcional] Por defecto "*". Columnas.
     * @param int $limit [Opcional] Numero de datos a retornar.
     * @param string $orderBy [Opcional] Por defecto "ID DESC". Ordenar por.
     * @return Users
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = '', $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = User::getTableName();
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);
        $users = new Users();
        $users->addUsers($select);

        return $users;
    }

    /**
     * Metodo que obtiene todos los usuarios.
     * @return array
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Metodo que obtiene, segun su ID, un usuario.
     * @param int $id
     * @return Users
     */
    public function getUser($id) {
        return $this->users[$id];
    }

    /**
     * Metodo que agrega un usuario a la lista.
     * @param User $user
     */
    public function addUser(User $user) {
        $this->users[$user->getID()] = $user;
    }

    /**
     * Metodo que obtiene un array con los datos de los usuarios y los agrega a la lista.
     * @param array $user
     */
    public function addUsers($user) {
        foreach ($user as $value) {
            $this->addUser(new User($value));
        }
    }

    /**
     * Metodo que obtiene el número total de USERS.
     * @return int
     */
    public function count() {
        $db = DBController::getConnection();
        $table = User::getTableName();
        $fetch = 'fetchAll';
        $columns = 'COUNT(*) AS count';
        $select = $db->select($table, $fetch, '', [], $columns);

        return $select[0]['count'];
    }

}
