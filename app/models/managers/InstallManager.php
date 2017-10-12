<?php
/**
 * InstallManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\util\Arrays;
use SoftnCMS\util\Logger;
use SoftnCMS\util\Messages;

/**
 * Class InstallManager
 * @author Nicolás Marulanda P.
 */
class InstallManager {
    
    const INSTALL_SITE_URL    = 'install_site_url';
    
    const INSTALL_DB_NAME     = 'install_db_name';
    
    const INSTALL_DB_USER     = 'install_db_user';
    
    const INSTALL_DB_PASSWORD = 'install_db_password';
    
    const INSTALL_HOST        = 'install_host';
    
    const INSTALL_PREFIX      = 'install_prefix';
    
    const INSTALL_CHARSET     = 'install_charset';
    
    /** @var \PDO */
    private $connection;
    
    public function checkConnection($dataInput) {
        $result    = TRUE;
        $dbHost    = Arrays::get($dataInput, self::INSTALL_HOST);
        $dbName    = Arrays::get($dataInput, self::INSTALL_DB_NAME);
        $dbUser    = Arrays::get($dataInput, self::INSTALL_DB_USER);
        $dbPass    = Arrays::get($dataInput, self::INSTALL_DB_PASSWORD);
        $dbCharset = Arrays::get($dataInput, self::INSTALL_CHARSET);
        $dsn       = "mysql:host=$dbHost;dbname=$dbName;charset=$dbCharset";
        
        try {
            $this->connection = new \PDO($dsn, $dbUser, $dbPass);
        } catch (\PDOException $pdoEx) {
            Messages::addDanger(__('Error al establecer la conexión con la base de datos.'));
            Logger::getInstance()
                  ->withName('INSTALL')
                  ->error($pdoEx->getMessage());
            $result = FALSE;
        }
        
        return $result;
    }
    
    public function createFileConfig($dataInput) {
        //Compruebo si puedo escribir en la carpeta donde están los archivo
        if (!is_writable(ABSPATH)) {
            Messages::addDanger(__('No es posible escribir en el directorio %1$s.', ABSPATH));
            Logger::getInstance()
                  ->withName('INSTALL')
                  ->debug('No es posible escribir en el directorio.', ['directory' => ABSPATH]);
            
            return FALSE;
        }
        
        $configFile      = $this->getDataFileConfig($dataInput);
        $configFile_path = ABSPATH . 'config.php';
        $handle          = fopen($configFile_path, 'w');
        foreach ($configFile as $line) {
            fwrite($handle, $line);
        }
        fclose($handle);
        chmod($configFile_path, 0666);
        
        return TRUE;
    }
    
    private function getDataFileConfig($dataInput) {
        $this->createConstants($dataInput);
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
    
    private function createConstants($dataInput) {
        define('URL_WEB', Arrays::get($dataInput, self::INSTALL_SITE_URL));
        define('DB_NAME', Arrays::get($dataInput, self::INSTALL_DB_NAME));
        define('DB_USER', Arrays::get($dataInput, self::INSTALL_DB_USER));
        define('DB_PASSWORD', Arrays::get($dataInput, self::INSTALL_DB_PASSWORD));
        define('DB_HOST', Arrays::get($dataInput, self::INSTALL_HOST));
        define('DB_PREFIX', Arrays::get($dataInput, self::INSTALL_PREFIX));
        define('DB_CHARSET', Arrays::get($dataInput, self::INSTALL_CHARSET));
        define('DB_TYPE', 'mysql');
        define('APP_DEBUG', FALSE);
        define('LOGGED_KEY', $this->generateKey());
        define('COOKIE_KEY', $this->generateKey());
        define('SALTED_KEY', $this->generateKey());
        define('TOKEN_KEY', $this->generateKey());
        define('COOKIE_EXPIRE', strtotime('+30 days'));
    }
    
    private function generateKey($len = 64) {
        $random_str = '';
        $chars      = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $size       = strlen($chars) - 1;
        
        for ($i = 0; $i < $len; $i++) {
            $random_str .= $chars[rand(0, $size)];
        }
        
        return $random_str;
    }
    
    public function createTables() {
        $fileScriptSQL = ABSPATH . '../softn_cms.sql';
        
        if (!is_readable($fileScriptSQL)) {
            return FALSE;
        }
        
        $scriptSQL = file_get_contents($fileScriptSQL);
        $scriptSQL = str_replace(REPLACE_SQL_SITE_URL, URL_WEB, $scriptSQL);
        $scriptSQL = str_replace(REPLACE_SQL_PREFIX, DB_PREFIX, $scriptSQL);
        
        return $this->connection->exec($scriptSQL) !== FALSE;
    }
}
