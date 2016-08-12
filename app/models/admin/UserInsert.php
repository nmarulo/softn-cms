<?php

/**
 * Modulo del modelo usuario.
 * Gestiona el proceso de insertar usuarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\User;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona el proceso de insertar usuarios.
 *
 * @author Nicolás Marulanda P.
 */
class UserInsert {

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

    /** @var string Nombre de las columnas. */
    private static $COLUMNS = User::USER_EMAIL . ', ' . User::USER_LOGIN . ', ' . User::USER_NAME . ', ' . User::USER_PASS . ', ' . User::USER_REGISTRED . ', ' . User::USER_ROL . ', ' . User::USER_URL;

    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . User::USER_EMAIL . ', ' . ':' . User::USER_LOGIN . ', ' . ':' . User::USER_NAME . ', ' . ':' . User::USER_PASS . ', ' . ':' . User::USER_REGISTRED . ', ' . ':' . User::USER_ROL . ', ' . ':' . User::USER_URL;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /** @var int Identificador del INSERT realizado. */
    private $lastInsertId;

    /**
     * Constructor.
     * @param string $userLogin Nombre de usuario.
     * @param string $userName Nombre real.
     * @param string $userEmail Email.
     * @param string $userPass Contraseña.
     * @param int $userRol Rol asignado.
     * @param string $userUrl Pagina web del usuario.
     */
    public function __construct($userLogin, $userName, $userEmail, $userPass, $userRol, $userUrl) {
        $this->userLogin = $userLogin;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPass = User::encrypt($userPass);
        $this->userRol = 0;
        $this->userUrl = $userUrl;
        $this->prepareStatement = [];
    }

    /**
     * Metodo que realiza el proceso de insertar el usuario en la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function insert() {
        $db = DBController::getConnection();
        $table = User::getTableName();
        $this->prepare();

        //En caso de error
        if ($db->insert($table, self::$COLUMNS, self::$VALUES, $this->prepareStatement)) {
            $this->lastInsertId = $db->lastInsertId();
            return \TRUE;
        }

        return \FALSE;
    }

    /**
     * Metodo que obtiene el identificador del nuevo usuario.
     * @return int
     */
    public function getLastInsertId() {
        return $this->lastInsertId;
    }

    /**
     * Metodo que establece los datos a preparar.
     */
    private function prepare() {
        $date = \date('Y-m-d H:i:s', \time());
        $this->addPrepare(':' . User::USER_LOGIN, $this->userLogin, \PDO::PARAM_STR);
        $this->addPrepare(':' . User::USER_EMAIL, $this->userEmail, \PDO::PARAM_STR);
        $this->addPrepare(':' . User::USER_NAME, $this->userName, \PDO::PARAM_STR);
        $this->addPrepare(':' . User::USER_PASS, $this->userPass, \PDO::PARAM_STR);
        $this->addPrepare(':' . User::USER_REGISTRED, $date, \PDO::PARAM_STR);
        $this->addPrepare(':' . User::USER_ROL, $this->userRol, \PDO::PARAM_INT);
        $this->addPrepare(':' . User::USER_URL, $this->userUrl, \PDO::PARAM_STR);
    }

    /**
     * Metodo que guarda los datos establecidos.
     * @param string $parameter Indice a buscar. EJ: ":ID"
     * @param string $value Valor del indice.
     * @param int $dataType Tipo de dato. EJ: \PDO::PARAM_*
     */
    private function addPrepare($parameter, $value, $dataType) {
        $this->prepareStatement[] = DBController::prepareStatement($parameter, $value, $dataType);
    }

}
