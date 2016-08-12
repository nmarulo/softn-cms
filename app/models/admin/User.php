<?php

/**
 * Modulo del modelo usuario.
 * Gestiona los datos de cada usuario.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Post;

/**
 * Clase que gestiona los datos de cada usuarios.
 *
 * @author Nicolás Marulanda P.
 */
class User {

    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'users';

    /** Identificador del usuarios. */
    const ID = 'ID';

    /** Nombre de usuario. */
    const USER_LOGIN = 'user_login';

    /** Nombre real. */
    const USER_NAME = 'user_name';

    /** Email. */
    const USER_EMAIL = 'user_email';

    /** Contraseña. */
    const USER_PASS = 'user_pass';

    /** Rol asignado. */
    const USER_ROL = 'user_rol';

    /** Fecha de registro. */
    const USER_REGISTRED = 'user_registred';

    /** Pagina web del usuario. */
    const USER_URL = 'user_url';

    /** @var array Datos del usuario. */
    private $user;

    /**
     * Constructor.
     * @param array $data
     */
    public function __construct($data) {
        $this->user = $data;
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
     * @return User
     */
    public static function defaultInstance() {
        $data = [
            User::ID => 0,
            User::USER_EMAIL => '',
            User::USER_LOGIN => '',
            User::USER_NAME => '',
            User::USER_PASS => '',
            User::USER_REGISTRED => '0000-00-00 00:00:00',
            User::USER_ROL => 0,
            User::USER_URL => '',
        ];

        return new User($data);
    }

    /**
     * Metodo que realiza el HASH al valor pasado por parametro.
     * @param string $pass
     * @return string
     */
    public static function encrypt($pass) {
        return hash('sha256', $pass . \LOGGED_KEY);
    }

    /**
     * Metodo que obtiene un usuario segun su "ID".
     * @param int $value
     * @return User|bool
     */
    public static function selectByID($value) {
        return self::selectBy($value, User::ID, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene un usuario segun su "Login".
     * @param string $value
     * @return User|bool
     */
    public static function selectByLogin($value) {
        return self::selectBy($value, User::USER_LOGIN);
    }

    /**
     * Metodo que obtiene un usuario segun su "Email".
     * @param string $value
     * @return User|bool
     */
    public static function selectByEmail($value) {
        return self::selectBy($value, User::USER_EMAIL);
    }

    /**
     * Metodo que obtiene un usuario segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType [Opcional] Por defecto \PDO::PARAM_STR. Tipo de dato.
     * @return User|bool
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
     * @param int $limit [Opcional] Por defecto 1. Numero de datos a retornar.
     * @param string $orderBy [Opcional] Por defecto "ID DESC". Ordenar por.
     * @return User|bool En caso de no obtener datos retorna FALSE.
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = 1, $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = self::$TABLE;
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);

        if (empty($select)) {
            return \FALSE;
        }

        return new User($select[0]);
    }

    /**
     * Metodo que obtiene el identificador del usuario.
     * @return int
     */
    public function getID() {
        return $this->user[User::ID];
    }

    /**
     * Metodo que obtiene el nombre para el inicio de sesión.
     * @return string
     */
    public function getUserLogin() {
        return $this->user[User::USER_LOGIN];
    }

    /**
     * Metodo que obtiene el nombre del usuario.
     * @return string
     */
    public function getUserName() {
        return $this->user[User::USER_NAME];
    }

    /**
     * Metodo que obtiene el email.
     * @return string
     */
    public function getUserEmail() {
        return $this->user[User::USER_EMAIL];
    }

    /**
     * Metodo que obtiene la contraseña.
     * @return string
     */
    public function getUserPass() {
        return $this->user[User::USER_PASS];
    }

    /**
     * Metodo que obtiene el rol.
     * @return int
     */
    public function getUserRol() {
        return $this->user[User::USER_ROL];
    }

    /**
     * Metodo que obtiene la fecha de registro.
     * @return string
     */
    public function getUserRegistred() {
        return $this->user[User::USER_REGISTRED];
    }

    /**
     * Metodo que obtiene la pagina web del usuario.
     * @return string
     */
    public function getUserUrl() {
        return $this->user[User::USER_URL];
    }

    /**
     * Metodo que obtiene el número de POST realializados.
     * @return int
     */
    public function getCountPosts() {
        $db = DBController::getConnection();
        $table = Post::getTableName();
        $fetch = 'fetchAll';
        $userIDPost = Post::USER_ID;
        $where = "$userIDPost = :$userIDPost";
        $prepare = [
            DBController::prepareStatement(":$userIDPost", $this->getID(), \PDO::PARAM_INT)
        ];
        $columns = 'COUNT(*) AS count';
        $select = $db->select($table, $fetch, $where, $prepare, $columns);

        return $select[0]['count'];
    }

}
