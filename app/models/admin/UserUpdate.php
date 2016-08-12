<?php

/**
 * Modulo del modelo usuario.
 * Gestiona la actualización de usuarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\User;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona la actualización usuarios.
 *
 * @author Nicolás Marulanda P.
 */
class UserUpdate {

    /** @var User Instancia con los datos sin modificar. */
    private $user;

    /** @var string Nombre de usuario. */
    private $userLogin;

    /** @var string Nombre real. */
    private $userName;

    /** @var string Email. */
    private $userEmail;

    /** @var string Contraseña. */
    private $userPass;

    /** @var int Rol asignado. */
    private $userRol;

    /** @var string Pagina web del usuario. */
    private $userUrl;

    /** @var string Campos que seran actualizados. */
    private $dataColumns;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /**
     * Constructor.
     * @param User $user Instancia con los datos sin modificar.
     * @param string $userLogin Nombre de usuario.
     * @param string $userName Nombre real.
     * @param string $userEmail Email.
     * @param string $userPass Contraseña.
     * @param int $userRol Rol asignado.
     * @param string $userUrl Pagina web del usuario.
     */
    public function __construct(User $user, $userLogin, $userName, $userEmail, $userPass, $userRol, $userUrl) {
        $this->user = $user;
        $this->userLogin = $userLogin;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPass = User::encrypt($userPass);
        $this->userRol = $userRol;
        $this->userUrl = $userUrl;
        $this->prepareStatement = [];
        $this->dataColumns = "";
    }

    /**
     * Metodo que actualiza los datos del usuario en la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function update() {
        $db = DBController::getConnection();
        $table = User::getTableName();
        $parameter = ':id';
        $where = "ID = $parameter";
        $newData = $this->user->getID();
        $dataType = \PDO::PARAM_INT;
        $this->addPrepare($parameter, $newData, $dataType);

        /*
         * Si no hay datos, no se ejecuta la consulta. 
         * Se retorna TRUE para evitar un error.
         */
        if ($this->prepare()) {
            return \TRUE;
        }

        return $db->update($table, $this->dataColumns, $where, $this->prepareStatement);
    }

    /**
     * Metodo que obtiene el usuario con los datos actualizados.
     * @return User
     */
    public function getUser() {
        $db = DBController::getConnection();
        $table = User::getTableName();
        $fetch = 'fetchAll';
        $columns = '*';
        $where = 'ID = :id';
        //Obtiene el primer dato el cual corresponde al id.
        $prepare = [$this->prepareStatement[0]];
        $select = $db->select($table, $fetch, $where, $prepare, $columns);
        $user = new User($select[0]);

        return $user;
    }

    /**
     * Metodo que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    private function prepare() {
        $this->checkFields($this->user->getUserLogin(), $this->userLogin, User::USER_LOGIN, \PDO::PARAM_STR);
        $this->checkFields($this->user->getUserName(), $this->userName, User::USER_NAME, \PDO::PARAM_STR);
        $this->checkFields($this->user->getUserEmail(), $this->userEmail, User::USER_EMAIL, \PDO::PARAM_STR);
        $this->checkFields($this->user->getUserPass(), $this->userPass, User::USER_PASS, \PDO::PARAM_STR);
        $this->checkFields($this->user->getUserRol(), $this->userRol, User::USER_ROL, \PDO::PARAM_INT);
        $this->checkFields($this->user->getUserUrl(), $this->userUrl, User::USER_URL, \PDO::PARAM_STR);

        return empty($this->dataColumns);
    }

    /**
     * Metodo que comprueba si el nuevo dato es diferente al de la base de datos, 
     * de ser asi el campo sera actualizado.
     * @param string|int $oldData Dato actual.
     * @param string|int $newData Dato nuevo.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType Tipo de dato.
     */
    private function checkFields($oldData, $newData, $column, $dataType) {
        if ($oldData != $newData) {
            $parameter = ':' . $column;
            $this->addSetDataSQL($column, $parameter);
            $this->addPrepare($parameter, $newData, $dataType);
        }
    }

    /**
     * Metodo que agrega los datos que seran actualizados.
     * @param string $column Nombre de la columna en la tabla.
     * @param string $data Nuevo valor.
     */
    private function addSetDataSQL($column, $data) {
        $this->dataColumns .= empty($this->dataColumns) ? '' : ', ';
        $this->dataColumns .= "$column = $data";
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
