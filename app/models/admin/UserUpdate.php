<?php

/**
 * Modulo del modelo usuario.
 * Gestiona la actualización de usuarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\User;
use SoftnCMS\models\admin\base\ModelUpdate;
use SoftnCMS\models\admin\base\Model;

/**
 * Clase que gestiona la actualización usuarios.
 *
 * @author Nicolás Marulanda P.
 */
class UserUpdate extends ModelUpdate {

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
        parent::__construct(User::getTableName());
        $this->user = $user;
        $this->userLogin = $userLogin;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPass = User::encrypt($userPass);
        $this->userRol = $userRol;
        $this->userUrl = $userUrl;
    }

    /**
     * Metodo que obtiene el usuario con los datos actualizados.
     * @return User
     */
    public function getDataUpdate() {
        //Obtiene el primer dato el cual corresponde al id.
        $id = $this->prepareStatement[0]['value'];

        return User::selectByID($id);
    }

    /**
     * Metodo que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    protected function prepare() {
        $this->addPrepare(':' . Model::ID, $this->user->getID(), \PDO::PARAM_INT);
        $this->checkFields($this->user->getUserLogin(), $this->userLogin, User::USER_LOGIN, \PDO::PARAM_STR);
        $this->checkFields($this->user->getUserName(), $this->userName, User::USER_NAME, \PDO::PARAM_STR);
        $this->checkFields($this->user->getUserEmail(), $this->userEmail, User::USER_EMAIL, \PDO::PARAM_STR);
        $this->checkFields($this->user->getUserPass(), $this->userPass, User::USER_PASS, \PDO::PARAM_STR);
        $this->checkFields($this->user->getUserRol(), $this->userRol, User::USER_ROL, \PDO::PARAM_INT);
        $this->checkFields($this->user->getUserUrl(), $this->userUrl, User::USER_URL, \PDO::PARAM_STR);

        return empty($this->dataColumns);
    }

}
