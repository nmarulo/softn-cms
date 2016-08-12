<?php

/**
 * Modulo del modelo del formulario de registro de usuarios.
 * Gestiona el registro de usuarios.
 */

namespace SoftnCMS\models;

use SoftnCMS\models\admin\User;
use SoftnCMS\models\admin\UserInsert;

/**
 * Clase que gestiona el registro de usuarios.
 *
 * @author Nicolás Marulanda P.
 */
class Register {

    /** @var string Nombre de usuario. */
    private $username;

    /** @var string Email. */
    private $userEmail;

    /** @var string Contraseña. */
    private $userpass;

    /**
     * Constructor.
     * @param string $username Nombre de usuario.
     * @param string $userEmail Email.
     * @param string $userpass Contraseña.
     */
    public function __construct($username, $userEmail, $userpass) {
        $this->username = $username;
        $this->userEmail = $userEmail;
        $this->userpass = $userpass;
    }

    /**
     * Metodo que registra un usuario en la aplicación. Si todo es correcto 
     * redirecciona a la pagina de LOGIN.
     * @return bool Retornara FALSE en caso de error.
     */
    public function register() {
        if (!$this->isExistsUsername() && !$this->isExistsUserEmail()) {
            global $urlSite;

            $user = User::defaultInstance();
            $register = new UserInsert($this->username, $this->username, $this->userEmail, $this->userpass, $user->getUserRol(), $user->getUserUrl());

            if ($register->insert()) {
                \header("Location: $urlSite" . 'login');
                exit();
            }
        }

        return \FALSE;
    }

    /**
     * Metodo que comprueba si el nombre de usuario existe.
     * @return bool Si es TRUE, el usuario existe.
     */
    private function isExistsUsername() {
        $user = User::selectByLogin($this->username);

        return $user !== \FALSE;
    }

    /**
     * Metodo que comprueba si el email existe.
     * @return bool Si es TRUE, el email existe.
     */
    private function isExistsUserEmail() {
        $user = User::selectByEmail($this->userEmail);

        return $user !== \FALSE;
    }

}
