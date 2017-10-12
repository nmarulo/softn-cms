<?php
/**
 * MySQL.php
 */

namespace SoftnCMS\util\database;

/**
 * Class MySQL
 * @author Nicolás Marulanda P.
 */
class MySQL extends DBAbstract {
    
    /** @var \PDO */
    private $connection;
    
    /**
     * MySQL constructor.
     */
    public function __construct() {
        parent::__construct();
        //Establecer conexión con la base de datos
        try {
            $strConnection = sprintf('mysql:host=%1$s;dbname=%2$s;charset=%3$s', DB_HOST, DB_NAME, DB_CHARSET);
            //Conexión con la base de datos. PDO Object.
            $this->connection = new \PDO($strConnection, DB_USER, DB_PASSWORD);
            $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, FALSE);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $ex) {
            die('Error al intentar establecer la conexión con la base de datos.' . $ex->getMessage());
        }
    }
    
    protected function getConnection() {
        return $this->connection;
    }
    
    protected function setConnection($value) {
        $this->connection = $value;
    }
    
}
