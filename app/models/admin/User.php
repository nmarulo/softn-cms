<?php

/**
 * Modulo modelo: Gestiona los datos de cada usuario.
 */
namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\Model;
use SoftnCMS\models\admin\template\UserTemplate;

/**
 * Clase User para gestionar los datos de cada usuarios.
 * @author Nicolás Marulanda P.
 */
class User extends Model {
    
    use UserTemplate;
    
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
    
    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'users';
    
    /** @var array Datos del usuario. */
    private $user;
    
    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct($data) {
        $this->user     = $data;
        $this->instance = $this;
    }
    
    /**
     * Método que obtiene el nombre de la tabla.
     * @return string
     */
    public static function getTableName() {
        return self::$TABLE;
    }
    
    /**
     * Método que retorna una instancia por defecto.
     * @return User
     */
    public static function defaultInstance() {
        $data = [
            self::ID             => 0,
            self::USER_EMAIL     => '',
            self::USER_LOGIN     => '',
            self::USER_NAME      => '',
            self::USER_PASS      => '',
            self::USER_REGISTRED => '0000-00-00 00:00:00',
            self::USER_ROL       => 0,
            self::USER_URL       => '',
        ];
        
        return new User($data);
    }
    
    /**
     * Método que realiza el HASH al valor pasado por parámetro.
     *
     * @param string $pass
     *
     * @return string
     */
    public static function encrypt($pass) {
        return hash('sha256', $pass . \LOGGED_KEY);
    }
    
    /**
     * Método que obtiene un usuario según su "ID".
     *
     * @param int $value
     *
     * @return User|bool
     */
    public static function selectByID($value) {
        $select = self::selectBy(self::$TABLE, $value, self::ID, \PDO::PARAM_INT);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que recibe un lista de datos y retorna un instancia POSTS.
     *
     * @param array $data Lista de datos.
     *
     * @return User|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    /**
     * Método que obtiene un usuario según su "Login".
     *
     * @param string $value
     *
     * @return User|bool
     */
    public static function selectByLogin($value) {
        $select = self::selectBy(self::$TABLE, $value, self::USER_LOGIN);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene un usuario según su "Email".
     *
     * @param string $value
     *
     * @return User|bool
     */
    public static function selectByEmail($value) {
        $select = self::selectBy(self::$TABLE, $value, self::USER_EMAIL);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene el nombre para el inicio de sesión.
     * @return string
     */
    public function getUserLogin() {
        return $this->user[self::USER_LOGIN];
    }
    
    /**
     * Método que obtiene el nombre del usuario.
     * @return string
     */
    public function getUserName() {
        return $this->user[self::USER_NAME];
    }
    
    /**
     * Método que obtiene el email.
     * @return string
     */
    public function getUserEmail() {
        return $this->user[self::USER_EMAIL];
    }
    
    /**
     * Método que obtiene la contraseña.
     * @return string
     */
    public function getUserPass() {
        return $this->user[self::USER_PASS];
    }
    
    /**
     * Método que obtiene el rol.
     * @return int
     */
    public function getUserRol() {
        return $this->user[self::USER_ROL];
    }
    
    /**
     * Método que obtiene la fecha de registro.
     * @return string
     */
    public function getUserRegistred() {
        return $this->user[self::USER_REGISTRED];
    }
    
    /**
     * Método que obtiene la pagina web del usuario.
     * @return string
     */
    public function getUserUrl() {
        return $this->user[self::USER_URL];
    }
    
    /**
     * Método que obtiene el número de POST realizados.
     * @return int
     */
    public function getCountPosts() {
        $db         = DBController::getConnection();
        $table      = Post::getTableName();
        $fetch      = 'fetchAll';
        $userIDPost = Post::USER_ID;
        $where      = "$userIDPost = :$userIDPost";
        $prepare    = [
            DBController::prepareStatement(":$userIDPost", $this->getID(), \PDO::PARAM_INT),
        ];
        $columns    = 'COUNT(*) AS count';
        $select     = $db->select($table, $fetch, $where, $prepare, $columns);
        
        return $select[0]['count'];
    }
    
    /**
     * Método que obtiene el identificador del usuario.
     * @return int
     */
    public function getID() {
        return $this->user[self::ID];
    }
    
}
