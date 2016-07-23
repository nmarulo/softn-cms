<?php

/**
 * Modelo de acceso a MySQL
 */

namespace SoftnCMS\models;

/**
 * Clase de acceso a MySQL.
 *
 * @author Nicolás Marulanda P.
 */
class MySql {

    /** @var \PDO Instancia de la conexion a la base de datos. */
    private $connection;

    /** @var string Prefijo de las tablas. */
    private $prefix;

    /** @var string Guarda la sentencia SQL. */
    private $query;

    public function __construct() {
        //Establecer conexión con la base de datos
        try {
            $this->prefix = \DB_PREFIX;
            $strConexion = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            //Conexión con la base de datos. PDO Object.
            $this->connection = new \PDO($strConexion, \DB_USER, \DB_PASSWORD);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $ex) {
            die('ERROR FATAL: ' . $ex->getMessage());
        }
    }

//    public function query() {
//        
//    }
//
//    public function exec() {
//        
//    }

    public function close() {
        $this->connection = null;
    }

    /**
     * Metodo que obtiene la conexión actual.
     * @return \PDO
     */
    public function getConnection() {
        return $this->connection;
    }

    public function getQuery() {
        return $this->query;
    }

}
