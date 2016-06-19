<?php

/**
 * Gestión de usuarios.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase para implementar los usuarios como objetos.
 * @author Nicolás Marulanda P.
 */
class SN_Users {

    /** @var object Objeto SN_Users de la sesión actual. */
    private static $session = null;

    /** @var int Identificador del usuarios. */
    private $ID;

    /** @var string Nombre de usuario. */
    private $user_login;

    /** @var string Nombre real. */
    private $user_name;

    /** @var string Email. */
    private $user_email;

    /** @var string Contraseña. */
    private $user_pass;

    /** @var int Rol asignado. */
    private $user_rol;

    /** @var date Fecha de registro. */
    private $user_registred;

    /** @var string Pagina web del usuario. */
    private $user_url;

    /**
     * Constructor.
     * @param array|PDOStatement $arg Datos del usuario.<br/>
     * <b>NOTA: Los indices del array deben corresponder con el nombre de la tabla.</b>
     */
    public function __construct($arg) {
        if (is_object($arg)) {
            $this->ID = $arg->ID;
            $this->user_login = $arg->user_login;
            $this->user_name = $arg->user_name;
            $this->user_email = $arg->user_email;
            $this->user_pass = $arg->user_pass;
            $this->user_rol = $arg->user_rol;
            $this->user_registred = $arg->user_registred;
            $this->user_url = $arg->user_url;
        } elseif (is_array($arg)) {
            $default = array(
                'ID' => 0,
                'user_login' => '',
                'user_name' => '',
                'user_email' => '',
                'user_pass' => '',
                'user_rol' => 0,
                'user_registred' => '0000-00-00 00:00:00',
                'user_url' => ''
            );

            $default = array_merge($default, $arg);

            $this->ID = $default['ID'];
            $this->user_login = $default['user_login'];
            $this->user_name = $default['user_name'];
            $this->user_email = $default['user_email'];
            $this->user_pass = $default['user_pass'];
            $this->user_rol = $default['user_rol'];
            $this->user_registred = $default['user_registred'];
            $this->user_url = $default['user_url'];
        } else {
            echo 'ERROR. Tipo de parametro incorrecto.';
        }
    }

    /**
     * Metodo que obtiene la una instance SN_Users de la sesión actual.
     * @return object
     */
    public static function getSession() {
        if (empty(self::$session) && isset($_SESSION['username'])) {
            self::$session = SN_Users::get_instance($_SESSION['username']);
        }
        return self::$session;
    }

    /**
     * Metodo que realiza el HASH al valor pasado por parametro.
     * @param string $pass
     * @return string
     */
    public static function encrypt($pass) {
        return hash('sha256', $pass . LOGGED_KEY);
    }

    /**
     * Metodo que comprueba el rol del usuario.
     * @param string $rol Nombre clave del rol.
     * @param bool $redirect Si es TRUE, redirecciona a la pagina de inicio 
     * si la comprobación del rol es FALSE.
     * @return boolean
     */
    public static function checkRol($rol = 'admin', $redirect = false) {
        if (self::$session->getUser_rol() >= getRol($rol)) {
            return true;
        } elseif ($redirect) {
            Messages::add('No tienes suficientes permisos para realizar esta acción.', Messages::TYPE_W);
            redirect('index', ADM);
        }

        return false;
    }

    /**
     * Metodo que obtiene una lista con los usuarios que contienen 
     * el texto pasado por parametro.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $str
     * @return array
     */
    public static function search($str) {
        global $sndb;

        return $sndb->query([
                    'table' => 'users',
                    'where' => 'user_name LIKE :user_name',
                    'orderBy' => 'ID DESC',
                    'prepare' => [[':user_name', '%' . $str . '%'],]
                        ], 'fetchAll');
    }

    /**
     * Metodo que borra un usuario segun su id.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @param int $id Identificador del usuario.
     * @return bool
     */
    public static function delete($id) {
        global $sndb, $dataTable;

        $out = $sndb->exec([
            'type' => 'DELETE',
            'table' => 'users',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id]],
        ]);

        if ($out) {
            $dataTable['user']['dataList'] = SN_Users::dataList();
        }

        return $out;
    }

    /**
     * Metodo que obtiene todos los usuarios ordenados por ID de forma descendiente.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $fetch [Opcional] Tipo de datos a retornar.
     * Con "fetchObject" para retornar los datos como objetos. 
     * Por defecto, "fetchAll", retorna un array asociativo.
     * @return array|object
     */
    public static function dataList($fetch = 'fetchAll') {
        global $sndb;

        return $sndb->query(['table' => 'users', 'orderBy' => 'ID DESC'], $fetch);
    }

    /**
     * Metodo que obtiene un usuarios segun su ID y retorna 
     * un instancia SN_Users con los datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $id Identificador del usuario.
     * @return object
     */
    public static function get_instance($id) {
        global $sndb;

        $where = is_numeric($id) ? 'ID' : 'user_login';

        $out = $sndb->query([
            'table' => 'users',
            'where' => "$where = :ID",
            'prepare' => [[':ID', $id]]
                ], 'fetchObject');

        if ($out) {
            $out = new SN_Users($out);
        }

        return $out;
    }

    /**
     * Metodo que obtiene el ultimo usuario.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return object Retorna un objeto PDOstatement.
     */
    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query([
                    'table' => 'users',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ], 'fetchObject');
    }

    /**
     * Metodo que comprueba si el usuario ha iniciado sesión previamiente
     * o si tiene guardada la cookie para el inicio automatico.
     * @global array $dataTable Lista de datos de uso común.
     */
    public static function checkLogin() {
        global $dataTable;
        //Si la sesión o cookie no esta creada redirecciona a la pagina de login.
        if (isset($_SESSION['username'])) {
            self::$session = SN_Users::get_instance($_SESSION['username']);
        } elseif (isset($_COOKIE['rememberMe'])) {
            Messages::add('Bienvenido de nuevo.', Messages::TYPE_S);
            $_SESSION['username'] = $_COOKIE['rememberMe'];
            self::$session = SN_Users::get_instance($_COOKIE['rememberMe']);
        } else {
            Messages::add('Por favor inicia sesión.', Messages::TYPE_S);
            header('Location: ' . $dataTable['option']['siteUrl'] . 'login.php');
            exit();
        }
    }

    /**
     * Metodo para iniciar la sesión.
     * @global array $dataTable Lista de datos de uso común.
     * @param object $username Instancia SN_Users.
     * @param string $password Contraseña.
     * @param bool $user_rememberMe Si es TRUE, se creara la cookie "rememberMe"
     */
    public static function login($username, $password, $user_rememberMe = false) {
        global $dataTable;

        if ($username && $username->getUser_pass() == self::encrypt($password)) {
            $_SESSION['username'] = $username->getID();
            if ($user_rememberMe) {
                setcookie('rememberMe', $username->getID(), COOKIE_EXPIRE);
            }
            Messages::add('Inicio de sesión correcto.', Messages::TYPE_S);
            header("Location: " . $dataTable['option']['siteUrl'] . ADM);
            exit();
        } else {
            Messages::add('El usuario o contraseña es incorrecto.', Messages::TYPE_E);
        }
    }

    /**
     * Metodo para cerrar la sesión actual.
     * @global array $dataTable Lista de datos de uso común.
     */
    public static function logout() {
        global $dataTable;
        unset($_SESSION['username']);
        self::$session = null;
        Messages::add('Cierre de sesión correcto.', Messages::TYPE_S);
        if (isset($_COOKIE['rememberMe'])) {
            setcookie('rememberMe', '', time() - 10);
            /** Tiempo de espera para que las cookies se eliminen. */
            usleep(2000);
        }
        header('Location: ' . $dataTable['option']['siteUrl'] . 'login.php');
        exit();
    }

    /**
     * Metodo que registra un nuevo usuario.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @param array $arg Datos del usuario.
     */
    public static function checkSignup($arg) {
        global $sndb, $dataTable;

        $out = $sndb->query([
            'table' => 'users',
            'where' => "user_login = :user_login OR user_email = :user_email",
            'prepare' => [
                [':user_login', $arg['user_login'],],
                [':user_email', $arg['user_email'],],
            ]
        ]);

        //Si rowCount() retorna mas de 0 el usuario o email ya estan registrados
        if ($out->rowCount()) {
            Messages::add('El usuario o email, ya esta siendo utilizado.', Messages::TYPE_W);
        } else {
            if ($arg['user_pass'] == $arg['ruser_pass']) {
                $newUser = new SN_Users([
                    'user_login' => $arg['user_login'],
                    'user_name' => $arg['user_login'],
                    'user_email' => $arg['user_email'],
                    'user_pass' => $arg['user_pass'],
                    'user_rol' => 0,
                    'user_registred' => date('Y-m-d H:i:s'),
                ]);
                if ($newUser->insert()) {
                    Messages::add('Usuario registrado correctamente.', Messages::TYPE_S);
                    Messages::add('Ahora puede iniciar sesión con tu usuario y contraseña.', Messages::TYPE_I);
                    header('Location: ' . $dataTable['option']['siteUrl'] . 'login.php');
                    exit();
                } else {
                    Messages::add('Error en el registro de usuario.', Messages::TYPE_E);
                }
            } else {
                Messages::add('Las contraseñas no coinciden.', Messages::TYPE_E);
            }
        }
    }

    /**
     * Metodo que agrega los datos del usuario a la base de datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return bool
     */
    public function insert() {
        global $sndb;

        $this->user_pass = self::encrypt($this->user_pass);
        $out = $sndb->exec(array(
            'type' => 'INSERT',
            'table' => 'users',
            'column' => 'user_login, user_name, user_email, user_pass, user_rol, user_registred, user_url',
            'values' => ':user_login, :user_name, :user_email, :user_pass, :user_rol, :user_registred, :user_url',
            'prepare' => [
                [':user_login', $this->user_login],
                [':user_name', $this->user_name],
                [':user_email', $this->user_email],
                [':user_pass', $this->user_pass],
                [':user_rol', $this->user_rol],
                [':user_registred', $this->user_registred],
                [':user_url', $this->user_url],
            ]
        ));

        if ($out) {
            $out = SN_Users::get_lastInsert();
            if ($out) {
                $this->ID = $out->ID;
                $out = true;
            }
        }

        return $out;
    }

    /**
     * Metodo que actualiza los datos del usuario.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return bool
     */
    public function update() {
        global $sndb;

        return $sndb->exec(array(
                    'type' => 'UPDATE',
                    'table' => 'users',
                    'set' => 'user_login = :user_login, user_name = :user_name, user_email = :user_email, user_pass = :user_pass, user_rol = :user_rol, user_registred = :user_registred, user_url = :user_url',
                    'where' => 'ID = :ID',
                    'prepare' => [
                        [':ID', $this->ID],
                        [':user_login', $this->user_login],
                        [':user_name', $this->user_name],
                        [':user_email', $this->user_email],
                        [':user_pass', $this->user_pass],
                        [':user_rol', $this->user_rol],
                        [':user_registred', $this->user_registred],
                        [':user_url', $this->user_url],
                    ]
        ));
    }

    /**
     * Metodo que obtiene el identificador del usuario.
     * @return int
     */
    public function getID() {
        return $this->ID;
    }

    /**
     * Metodo que obtiene el nombre para el inicio de sesión.
     * @return string
     */
    public function getUser_login() {
        return $this->user_login;
    }

    /**
     * Metodo que obtiene el nombre del usuario.
     * @return string
     */
    public function getUser_name() {
        return $this->user_name;
    }

    /**
     * Metodo que obtiene el email.
     * @return string
     */
    public function getUser_email() {
        return $this->user_email;
    }

    /**
     * Metodo que obtiene la contraseña.
     * @return string
     */
    public function getUser_pass() {
        return $this->user_pass;
    }

    /**
     * Metodo que obtiene el rol.
     * @return int
     */
    public function getUser_rol() {
        return $this->user_rol;
    }

    /**
     * Metodo que obtiene la fecha de registro.
     * @return string
     */
    public function getUser_registred() {
        return $this->user_registred;
    }

    /**
     * Metodo que obtiene la pagina web del usuario.
     * @return string
     */
    public function getUser_url() {
        return $this->user_url;
    }

}
