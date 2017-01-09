<?php

/**
 * Modulo modelo: Gestiona el proceso de insertar usuarios.
 */
namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\ModelInsert;

/**
 * Clase UserInsert para gestionar el proceso de insertar usuarios.
 * @author Nicolás Marulanda P.
 */
class UserInsert extends ModelInsert {
    
    /** @var string Nombre de las columnas. */
    private static $COLUMNS = User::USER_EMAIL . ', ' . User::USER_LOGIN . ', ' . User::USER_NAME . ', ' . User::USER_PASS . ', ' . User::USER_REGISTRED . ', ' . User::USER_ROL . ', ' . User::USER_URL;
    
    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . User::USER_EMAIL . ', ' . ':' . User::USER_LOGIN . ', ' . ':' . User::USER_NAME . ', ' . ':' . User::USER_PASS . ', ' . ':' . User::USER_REGISTRED . ', ' . ':' . User::USER_ROL . ', ' . ':' . User::USER_URL;
    
    /** @var string Nombre de usuario. */
    private $userLogin;
    
    /** @var string Nombre real. */
    private $userName;
    
    /** @var string Email. */
    private $userEmail;
    
    /** @var string Contraseña. */
    private $userPass;
    
    /** @var string Rol asignado. */
    private $userRol;
    
    /** @var string Pagina web del usuario. */
    private $userUrl;
    
    /**
     * Constructor.
     *
     * @param string $userLogin Nombre de usuario.
     * @param string $userName  Nombre real.
     * @param string $userEmail Email.
     * @param string $userPass  Contraseña.
     * @param int    $userRol   Rol asignado.
     * @param string $userUrl   Pagina web del usuario.
     */
    public function __construct($userLogin, $userName, $userEmail, $userPass, $userRol, $userUrl) {
        parent::__construct(User::getTableName(), self::$COLUMNS, self::$VALUES);
        $this->userLogin = $userLogin;
        $this->userName  = $userName;
        $this->userEmail = $userEmail;
        $this->userPass  = User::encrypt($userPass);
        $this->userRol   = 0;
        $this->userUrl   = $userUrl;
    }
    
    /**
     * Método que establece los datos a preparar.
     */
    protected function prepare() {
        $date = \date('Y-m-d H:i:s', \time());
        $this->addPrepare(':' . User::USER_LOGIN, $this->userLogin, \PDO::PARAM_STR);
        $this->addPrepare(':' . User::USER_EMAIL, $this->userEmail, \PDO::PARAM_STR);
        $this->addPrepare(':' . User::USER_NAME, $this->userName, \PDO::PARAM_STR);
        $this->addPrepare(':' . User::USER_PASS, $this->userPass, \PDO::PARAM_STR);
        $this->addPrepare(':' . User::USER_REGISTRED, $date, \PDO::PARAM_STR);
        $this->addPrepare(':' . User::USER_ROL, $this->userRol, \PDO::PARAM_INT);
        $this->addPrepare(':' . User::USER_URL, $this->userUrl, \PDO::PARAM_STR);
    }
    
}
