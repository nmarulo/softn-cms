<?php

/**
 * Modulo del controlador usuario.
 * Gestiona la información del usuario.
 */

namespace SoftnCMS\models;

use SoftnCMS\models\Post;

/**
 * Clase que gestiona la información de cada uno de los usuarios.
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

    /**
     * Datos del usuario.
     * @var array 
     */
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
    
    public static function defaultInstance(){
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

    public function getCountPosts() {
        $db = \SoftnCMS\controllers\DBController::getConnection();
        $table = Post::getTableName();
        $fetch = 'fetchAll';
        $userIDPost = Post::USER_ID;
        $where = "$userIDPost = :$userIDPost";
        $prepare = [
            [
                'parameter' => ":$userIDPost",
                'value' => $this->getID(),
                'dataType' => \PDO::PARAM_INT
            ]
        ];
        $columns = 'COUNT(*) AS count';
        $select = $db->select($table, $fetch, $where, $prepare, $columns);
        return $select[0]['count'];
    }

}
