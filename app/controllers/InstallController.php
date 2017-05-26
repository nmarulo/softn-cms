<?php
/**
 * Modulo controlador: Pagina de instalación de la aplicación.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\helpers\ArrayHelp;
use SoftnCMS\helpers\form\builders\InputAlphanumericBuilder;
use SoftnCMS\helpers\form\builders\InputUrlBuilder;
use SoftnCMS\helpers\Helps;

/**
 * Clase InstallController de la pagina de instalación de la aplicación
 * @author Nicolás Marulanda P.
 */
class InstallController extends Controller {
    
    /**
     * Método llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        /*
         * Comprobación adicional. Ya existe un evento en el ROUTER para cuando
         * se este en la pagina de instalación y la constante "INSTALL" no este declarada
         * se redirecciona a la pagina de login.
         */
        if (file_exists(ABSPATH . 'config.php')) {
            Helps::redirect();
        }
        
        if (Form::submit('step')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput === FALSE) {
                Messages::addWarning("Los datos no son validos.");
            } else {
                $this->check($dataInput);
            }
        }
        
        return [
            'installUrl'     => Router::getDATA()[SITE_URL],
            'installDb'      => 'softn_cms',
            'installUser'    => 'root',
            'installPass'    => 'root',
            'installHost'    => 'localhost',
            'installPrefix'  => 'sn_',
            'installCharset' => 'utf8',
        ];
    }
    
    private function getDataInput() {
        Form::setINPUT([
            InputUrlBuilder::init('installUrl')
                           ->build(),
            InputAlphanumericBuilder::init('installDb')
                                    ->build(),
            InputAlphanumericBuilder::init('installUser')
                                    ->build(),
            InputAlphanumericBuilder::init('installPass')
                                    ->build(),
            InputAlphanumericBuilder::init('installHost')
                                    ->setRequire(FALSE)
                                    ->build(),
            InputAlphanumericBuilder::init('installPrefix')
                                    ->setRequire(FALSE)
                                    ->build(),
            InputAlphanumericBuilder::init('installCharset')
                                    ->setRequire(FALSE)
                                    ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    private function check($dataInput) {
        define('URL_WEB', ArrayHelp::get($dataInput, 'installUrl'));
        define('DB_NAME', ArrayHelp::get($dataInput, 'installDb'));
        define('DB_USER', ArrayHelp::get($dataInput, 'installUser'));
        define('DB_PASSWORD', ArrayHelp::get($dataInput, 'installPass'));
        define('DB_HOST', ArrayHelp::get($dataInput, 'installHost'));
        define('DB_PREFIX', 'sn_');
        //        define('DB_PREFIX', ArrayHelp::get($dataInput, 'installPrefix'));
        define('DB_CHARSET', 'utf8');
        define('DB_TYPE', 'mysql');
        //        define('DB_CHARSET', ArrayHelp::get($dataInput, 'installCharset'));
        define('APP_DEBUG', 0);
        define('LOGGED_KEY', '5GLhueRQNTmlo8nY6XusgNN1JDSnQWRKsGiQTkjU0QuD9IVf9SdtmPrwDxc6irBs');
        define('COOKIE_KEY', 'bydiGj9QAG0Nh9RpzGjmPKtTTiUI6PI3iivDP9nEyoTQMtaqnmN7GxR8xXYci5dw');
        define('TOKEN_KEY', '8s6IyXziV8T2Xlz1TpySqGjFM0PZGJyWmHt8vf2Dmde2DYwsykRtOEJbFM6bN3rz');
        //        define('LOGGED_KEY', $this->generateKey());
        //        define('COOKIE_KEY', $this->generateKey());
        //define('SALTED_KEY',    '8s6IyXziV8T2Xlz1TpySqGjFM0PZGJyWmHt8vf2Dmde2DYwsykRtOEJbFM6bN3rz');
        //        define('TOKEN_KEY', $this->generateKey());
        define('COOKIE_EXPIRE', strtotime('+30 days'));
        
        $str = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        
        try {
            $connection = new \PDO($str, DB_USER, DB_PASSWORD);
        } catch (\PDOException $pdoEx) {
            Messages::addError('Error al establecer la conexión con la base de datos.');
            Helps::redirectRoute();
        }
        
        //Compruebo si puedo escribir en la carpeta donde están los archivo
        if (!is_writable(ABSPATH)) {
            Messages::addWarning('No es posible escribir en el directorio ' . ABSPATH . '.');
            Helps::redirectRoute();
        }
        
        $configFile = $this->initDataConfig();
        
        $this->writeFile($configFile);
        
        Messages::addSuccess('El proceso de instalación se completo correctamente.');
        Helps::redirect(Router::getRoutes()['login']);
    }
    
    /**
     * Método que establece los datos de configuración usando como base el fichero "config-sample.php".
     * @return array
     */
    private function initDataConfig() {
        $configFile = file(ABSPATH . 'config-sample.php');
        
        foreach ($configFile as $num => $line) {
            //Si no encuentra la excreción regular en la linea, pasa a la siguiente iteración
            if (!preg_match('/^define\(\'([A-Z_]+)\',([ ]+)/', $line, $match)) {
                continue;
            }
            
            switch ($match[1]) {
                case 'DB_NAME':
                case 'DB_USER':
                case 'DB_PASSWORD':
                case 'DB_HOST':
                case 'DB_PREFIX':
                case 'DB_CHARSET':
                case 'LOGGED_KEY':
                case 'COOKIE_KEY':
                case 'TOKEN_KEY':
                    //$match[1] nombre de la constante
                    //$match[2] espacio
                    $configFile[$num] = "define('" . $match[1] . "'," . $match[2] . "'" . constant($match[1]) . "');\r\n";
                    break;
            }
        }
        
        return $configFile;
    }
    
    /**
     * Método que crea el fichero "config.php" y escribe los datos.
     *
     * @param array $configFile
     */
    private function writeFile($configFile) {
        $configFile_path = ABSPATH . 'config.php';
        $handle          = fopen($configFile_path, 'w');
        foreach ($configFile as $line) {
            fwrite($handle, $line);
        }
        fclose($handle);
        chmod($configFile_path, 0666);
    }
    
    /**
     * Método que lee un fichero SQL y ejecuta las sentencias SQL.
     * @return bool
     */
    private function execSQL() {
        $error    = FALSE;
        $db       = DBController::getConnection();
        $filename = ABSPATH . 'sn-admin/includes/install.sql';
        
        if (file_exists($filename)) {
            $sql = file_get_contents($filename);
            //            $sql = str_replace('{DB_PREFIX}', DB_PREFIX, $sql);
            //            $sql = str_replace('{URL_WEB}', URL_WEB, $sql);
            $error = $db->getConnection()
                        ->exec($sql) === FALSE;
        } else {
            $error = TRUE;
        }
        
        return $error;
    }
    
    /**
     * Método que genera un cadena de caracteres alfanumérica con mayúsculas y minúsculas
     *
     * @param int $len Longitud de la cadena.
     *
     * @return string
     */
    private function generateKey($len = 64) {
        $random_str = "";
        $chars      = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $size       = strlen($chars) - 1;
        for ($i = 0; $i < $len; $i++) {
            $random_str .= $chars[rand(0, $size)];
        }
        
        return $random_str;
    }
    
}
