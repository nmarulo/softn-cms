<?php

/**
 * Modulo modelo: Gestiona el registro de sesión.
 */

namespace SoftnCMS\models;

use SoftnCMS\models\admin\User;
use SoftnCMS\models\admin\UserInsert;

/**
 * Clase Register para gestionar el registro de usuarios.
 * @author Nicolás Marulanda P.
 */
class Register {
    
    /** @var string Nombre de usuario. */
    private $username;
    
    /** @var string Email. */
    private $userEmail;
    
    /** @var string Contraseña. */
    private $userPass;
    
    /**
     * Constructor.
     *
     * @param string $username  Nombre de usuario.
     * @param string $userEmail Email.
     * @param string $userPass  Contraseña.
     */
    public function __construct($username, $userEmail, $userPass) {
        $this->username  = $username;
        $this->userEmail = $userEmail;
        $this->userPass  = $userPass;
    }
    
    /**
     * Método que registra un usuario en la aplicación. Si es correcto
     * redireccionara ha la pagina de LOGIN.
     * @return bool Retorna FALSE en caso de error.
     */
    public function register() {
        if (!$this->isExistsUsername() && !$this->isExistsUserEmail()) {
            $user     = User::defaultInstance();
            $register = new UserInsert($this->username, $this->username, $this->userEmail, $this->userPass, $user->getUserRol(), $user->getUserUrl());
            
            return $register->insert();
        }
        
        return \FALSE;
    }
    
    /**
     * Método que comprueba si el nombre de usuario existe.
     * @return bool Si es TRUE, el usuario existe.
     */
    private function isExistsUsername() {
        $user = User::selectByLogin($this->username);
        
        return $user !== \FALSE;
    }
    
    /**
     * Método que comprueba si el email existe.
     * @return bool Si es TRUE, el email existe.
     */
    private function isExistsUserEmail() {
        $user = User::selectByEmail($this->userEmail);
        
        return $user !== \FALSE;
    }
    
}
