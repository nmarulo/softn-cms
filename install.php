<?php

/**
 * Controlador de la pagina de instalación.
 */
$check = -1;
/** Ruta absotula del proyecto. */
define('ABSPATH', dirname(__FILE__)  . DIRECTORY_SEPARATOR);
session_start();
if (!isset($_SESSION['install'])) {
    $_SESSION['install'] = [
        'install_url' => '',
        'install_db' => '',
        'install_user' => '',
        'install_pass' => '',
        'install_host' => '',
        'install_prefix' => 'sn_',
        'install_charset' => 'utf8',
    ];
}

/**
 * Metodo que genera un cadena de caracteres alfanumerica con mayusculas y minusculas
 * @param int $leng Longitud de la cadena.
 * @return string
 */
function generateKey($leng = 64) {
    $random_str = "";
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $size = strlen($chars) - 1;
    for ($i = 0; $i < $leng; $i++) {
        $random_str .= $chars[rand(0, $size)];
    }
    return $random_str;
}

/**
 * Metodo que lee un fichero SQL y ejecuta las sentencias SQL.
 * @param PDO $db Conexión a la base de datos.
 * @return int Retorna 5 en caso de error.
 */
function execSQL($db) {
    $filename = ABSPATH . 'sn-admin/includes/install.sql';
//    $filename = ABSPATH . 'eer-softn-cms.sql';
    if (file_exists($filename)) {
        $file = file($filename);
        $sql = file_get_contents($filename);
        $sql = str_replace('{DB_PREFIX}', DB_PREFIX, $sql);
        $sql = str_replace('{URL_WEB}', URL_WEB, $sql);
        $error = 0;
        if ($db->exec($sql) === FALSE) {
            ++$error;
        }
        return $error ? 5 : -1;
    }
    return 5;
}

if (isset($_GET['demo'])) {
    require 'sn-includes/sn-users.php';
    require 'sn-includes/sn-db.php';
    require 'sn-config.php';
    $sndb = new SN_DB();
    if (SN_Users::getSession() !== null && SN_Users::getSession()->getUser_rol() == 3) {
        $filename = ABSPATH . 'sn-admin/includes/demo.sql';
        if (file_exists($filename)) {
            $sql = file_get_contents($filename);
            $sql = str_replace('{DB_PREFIX}', DB_PREFIX, $sql);
            $sndb->getConnection()->exec($sql);
            $sndb->close();
        }
    }
    $check = 3;
} elseif (isset($_POST['step']) || (isset($_GET['step']) && $_GET['step'] == 3)) {
    $step = $_REQUEST['step'];

    //En el paso 3 compuebo el archivo config ya que este fue creado manualmente.
    if ($step == 3) {
        if (file_exists(ABSPATH . 'sn-config.php')) {
            require ABSPATH . 'sn-config.php';
        } else {
            $check = 1;
        }
    } else {
        define('URL_WEB', filter_input(INPUT_POST, 'install_url'));
        define('DB_NAME', filter_input(INPUT_POST, 'install_db'));
        define('DB_USER', filter_input(INPUT_POST, 'install_user'));
        define('DB_PASSWORD', filter_input(INPUT_POST, 'install_pass'));
        define('DB_HOST', filter_input(INPUT_POST, 'install_host'));
        define('DB_PREFIX', filter_input(INPUT_POST, 'install_prefix'));
        define('DB_CHARSET', filter_input(INPUT_POST, 'install_charset'));
        define('LOGGED_KEY', generateKey());
        define('COOKIE_KEY', generateKey());
        //define('SALTED_KEY',    '8s6IyXziV8T2Xlz1TpySqGjFM0PZGJyWmHt8vf2Dmde2DYwsykRtOEJbFM6bN3rz');
        define('COOKIE_EXPIRE', strtotime('+30 days'));
        $_SESSION['install'] = [
            'install_url' => URL_WEB,
            'install_db' => DB_NAME,
            'install_user' => DB_USER,
            'install_pass' => DB_PASSWORD,
            'install_host' => DB_HOST,
            'install_prefix' => DB_PREFIX,
            'install_charset' => DB_CHARSET,
        ];
    }

    try {
        $str = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $connection = new PDO($str, DB_USER, DB_PASSWORD);
        $check = execSQL($connection);
        if ($check != 5) {
            $user_access = 'admin';
            $date = date('Y-m-d H:i:s');
            $user_pass = hash('sha256', $user_access . LOGGED_KEY);
            $sql = "INSERT INTO " . DB_PREFIX . "users VALUES (NULL, '$user_access', '$user_access', 'admin@localhost', '$user_pass', 3, '$date', NULL)";
            if ($connection->exec($sql) === FALSE) {
                $check = 6;
            }
        }
    } catch (PDOException $ex) {
        $check = 0;
    }

    //Compruebo que se conecto a la base de datos
    if ($check && $step != 3 && $check < 5) {
        //Compruebo si puedo escribir en la carpeta donde estan los archivo
        if (is_writable(ABSPATH)) {
            $check = 2;
        } else {
            $check = 1;
        }

        $configFile = file(ABSPATH . 'sn-config-sample.php');

        foreach ($configFile as $num => $line) {
            //Si no encuentra la exprecion regular en la linea, pasa a la siguente iteracion
            if (!preg_match('/^define\(\'([A-Z_]+)\',([ ]+)/', $line, $match)) {
                continue;
            }

            if ($match[1] != 'COOKIE_EXPIRE') {
                $configFile[$num] = "define('" . $match[1] . "'," . $match[2] . "'" . constant($match[1]) . "');\r\n";
            }
        }

        if ($check == 2) {
            $configFile_path = ABSPATH . 'sn-config.php';
            $handle = fopen($configFile_path, 'w');
            foreach ($configFile as $line) {
                fwrite($handle, $line);
            }
            fclose($handle);
            chmod($configFile_path, 0666);
            $check = 3;
        }
    }
} elseif (file_exists(ABSPATH . 'sn-config.php')) {
    $check = 3;
}

require ABSPATH . 'sn-admin/content/install.php';
