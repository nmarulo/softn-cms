<?php

/**
 * Gestión de la base de datos.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase usada para instanciar la conexión a la base de datos.
 * @author Nicolás Marulanda P.
 */
class SN_DB {

    /** @var PDO Instancia de la conexion a la base de datos. */
    private $connection;

    /** @var string Prefijo de las tablas. */
    private $prefix;

    /** @var string Guarda la sentencia SQL. */
    private $query;

    /**
     * Constructor.
     * Realiza la conexion a la base de datos. (Datos de conexión ver sn-config.php)
     */
    public function __construct() {
        //Establecer conexión con la base de datos
        try {
            $this->prefix = DB_PREFIX;
            $strConexion = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            //Conexión con la base de datos. PDO Object.
            $this->connection = new PDO($strConexion, DB_USER, DB_PASSWORD);
        } catch (PDOException $ex) {
            die('ERROR FATAL: ' . $ex->getMessage());
        }
    }

    /**
     * Metodo que ejecuta un consulta SELECT.
     * @param type $arg
     * @param type $fetch
     * @return type
     */
    public function query($arg, $fetch = null) {
        $out = 0;
        $defaults = array(
            'column' => '*',
            'table' => '',
            'where' => '',
            'orderBy' => '',
            'limit' => '',
            'prepare' => []
        );

        $defaults = array_merge($defaults, $arg);

        $defaults['table'] = $this->prefix . $defaults['table'];

        $sql = "SELECT $defaults[column]";
        $sql .= empty($defaults['table']) ? '' : " FROM $defaults[table]";
        $sql .= empty($defaults['where']) ? '' : " WHERE $defaults[where]";
        $sql .= empty($defaults['orderBy']) ? '' : " ORDER BY $defaults[orderBy]";
        $sql .= empty($defaults['limit']) ? '' : " LIMIT $defaults[limit]";

        $this->query = $sql;

        $sql = $this->connection->prepare($sql);

        if ($defaults['prepare']) {
            $sql = $this->bindValue($sql, $defaults['prepare']);
        }

        $sql->execute();

        if ($sql) {
            $out = $sql;
            switch ($fetch) {
                case 'fetchAll':
                    $out = $out->fetchAll();
                    break;
                case 'fetchObject':
                    $out = $out->fetchObject();
                    break;
            }
        }

        return $out;
    }

    /**
     * Metodo que ejecuta un consulta INSERT, UPDATE y DELETE.
     * @param type $arg
     * @return type
     */
    public function exec($arg) {
        $out = 0;
        $defaults = array(
            'type' => 'INSERT',
            'column' => '*',
            'table' => '',
            'where' => '',
            'values' => '',
            'set' => '',
            'prepare' => []
        );

        $defaults = array_merge($defaults, $arg);

        $defaults['table'] = $this->prefix . $defaults['table'];

        switch ($defaults['type']) {
            case 'INSERT':
                $sql = "INSERT INTO $defaults[table]";
                $sql .= strcmp($defaults['column'], '*') ? " ($defaults[column])" : '';
                $sql .= " VALUE ($defaults[values])";
                break;
            case 'UPDATE':
                $sql = "UPDATE $defaults[table] SET $defaults[set] WHERE $defaults[where]";
                break;
            case 'DELETE':
                $sql = "DELETE FROM $defaults[table] WHERE $defaults[where]";
                break;
        }

        $this->query = $sql;

        $sql = $this->connection->prepare($sql);

        if ($defaults['prepare']) {
            $sql = $this->bindParam($sql, $defaults['prepare']);
        }

        $sql->execute();

        if ($sql) {
            $out = $sql;
        }

        return $out;
    }

    /**
     * Obtiene la conexion de la base de datos.
     * @return PDOStatement
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Cierra la conexion de la base de datos.
     */
    function close() {
        $this->connection = null;
    }

    /**
     * Obtiene la consulta SQL actual.
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }

    private function bindParam($sql, $prepare) {
        foreach ($prepare as $value) {
            $sql->bindValue($value[0], $value[1]);
        }
        return $sql;
    }

    private function bindValue($sql, $prepare) {
        foreach ($prepare as $value) {
            $sql->bindValue($value[0], $value[1]);
        }
        return $sql;
    }

}
