<?php

/**
 * Modulo del controlador usuario.
 * Gestiona la información del usuario.
 */

namespace SoftnCMS\controllers;

/**
 * Clase que gestiona la información de cada uno de los usuarios.
 *
 * @author Nicolás Marulanda P.
 */
class User {

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
    const USER_ROL = '$user_rol';

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

}
