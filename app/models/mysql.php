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

    /** @var string Guarda la sentencia SQL. */
    private $query;

    /** @var \PDOStatement  */
    private $prepareObject;

    public function __construct() {
        //Establecer conexión con la base de datos
        try {
            $strConexion = 'mysql:host=' . \DB_HOST . ';dbname=' . \DB_NAME . ';charset=' . \DB_CHARSET;
            //Conexión con la base de datos. PDO Object.
            $this->connection = new \PDO($strConexion, \DB_USER, \DB_PASSWORD);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $ex) {
            die('Error al intentar establecer la conexión con la base de datos.');
        }
    }

    public function select($table, $fetch = '', $where = '', $prepare = [], $columns = '*', $orderBy = '', $limit = '') {
        //EJ
//        $prepare = [[
//            'parameter' => '',
//            'value' => '',
//            'dataType' => ''
//        ]];
        $output = \FALSE;
        $sql = "SELECT $columns FROM $table";
        $sql .= empty($where) ? '' : " WHERE $where";
        $sql .= empty($orderBy) ? '' : " ORDER BY $orderBy";
        $sql .= empty($limit) ? '' : " LIMIT $limit";
        $this->query = $sql;

        if ($this->execute($sql, $prepare)) {
            switch ($fetch) {
                case 'fetchAll':
                    $output = $this->prepareObject->fetchAll();
                    break;
                case 'fetchObject':
                    $output = $this->prepareObject->fetchObject();
                    break;
                default:
                    $output = $this->prepareObject;
                    break;
            }
        }
        return $output;
    }

    public function insert($table, $columns, $prepare) {
        $output = \FALSE;
        $sql = 'INSERT INTO sn_posts (colums) VALUES (values)';
        return $output;
    }

    public function update() {
        $output = \FALSE;
        $sql = 'UPDATE sn_posts SET columns WHERE ID = 1';
        return $output;
    }

    public function delete() {
        $output = \FALSE;
        $sql = 'DELETE FROM sn_posts WHERE ID = 1';
        return $output;
    }

    /**
     * Metodo que cierra la conexión actual.
     */
    public function close() {
        $this->connection = null;
    }

    /**
     * Metodo que obtiene la instancia de la conexión actual.
     * @return \PDO
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Metodo que obtiene la sentencia SQL.
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * 
     * @param string $sql
     * @param array $prepare
     * @return bool
     */
    private function execute($sql, $prepare) {
        $this->prepareObject = $this->connection->prepare($sql);

        if (!$this->bindValue($prepare)) {
            return \FALSE;
        }
        return $this->prepareObject->execute();
    }

    /**
     * 
     * @param array $data
     * @return bool 
     */
    private function bindValue($data) {
        $count = \count($data);
        $error = \FALSE;

        for ($i = 0; $i < $count && !$error; ++$i) {
            $value = $data[$i];

            if (!$this->prepareObject->bindValue($value['parameter'], $value['value'], $value['dataType'])) {
                $error = \TRUE;
            }
        }
        return !$error;
    }

}
