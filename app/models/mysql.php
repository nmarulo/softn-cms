<?php

/**
 * Modulo modelo: MySQL.
 */

namespace SoftnCMS\models;

/**
 * Clase MySQL.
 * @author Nicolás Marulanda P.
 */
class MySql {
    
    /** @var \PDO Instancia de la conexión a la base de datos. */
    private $connection;
    
    /** @var string Guarda la sentencia SQL. */
    private $query;
    
    /** @var \PDOStatement Declaración de la consulta preparada. */
    private $prepareObject;
    
    /**
     * Constructor.
     */
    public function __construct() {
        //Establecer conexión con la base de datos
        try {
            $strConnection = 'mysql:host=' . \DB_HOST . ';dbname=' . \DB_NAME . ';charset=' . \DB_CHARSET;
            //Conexión con la base de datos. PDO Object.
            $this->connection = new \PDO($strConnection, \DB_USER, \DB_PASSWORD);
            $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, \FALSE);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $ex) {
            die('Error al intentar establecer la conexión con la base de datos.');
        }
    }
    
    /**
     * Método que ejecuta una consulta "SELECT" simple.
     *
     * @param string $table   Nombre de la tabla.
     * @param string $fetch   [Opcional] Tipo de datos a retornar.
     *                        Si esta vacía retorna la declaración de la consulta preparada.
     *                        Si es "fetchAll", retorna un array con todos los elementos.
     *                        Si es "fetchObject", retorna un objeto \PDOStatement.
     * @param string $where   [Opcional] Condiciones de la consulta.
     * @param array  $prepare [Opcional] Lista de indices a reemplazar en la consulta.
     *                        Usar DBController::prepareStatement().
     * @param string $columns [Opcional] Columnas a obtener.
     * @param string $orderBy [Opcional] Ordenar la consulta por.
     * @param string $limit   [Opcional] Limitar los datos a retornar de la consulta.
     *
     * @return array|\PDOStatement|bool Retorna \FALSE en caso de error.
     */
    public function select($table, $fetch = '', $where = '', $prepare = [], $columns = '*', $orderBy = '', $limit = '') {
        $sql = $this->createSelect($table, $where, $columns, $orderBy, $limit);
        
        return $this->executeSelect($sql, $prepare, $fetch);
    }
    
    /**
     * Método que crea y retorna una sentencia Select.
     *
     * @param string $table
     * @param string $where
     * @param string $columns
     * @param string $orderBy
     * @param string $limit
     *
     * @return string
     */
    public function createSelect($table, $where = '', $columns = '*', $orderBy = '', $limit = '') {
        $sql = "SELECT $columns FROM $table";
        $sql .= empty($where) ? '' : " WHERE $where";
        $sql .= empty($orderBy) ? '' : " ORDER BY $orderBy";
        $sql .= empty($limit) ? '' : " LIMIT $limit";
        
        return $sql;
    }
    
    /**
     * Método que ejecuta una sentencia Select y obtiene como array o como objetos los resultados.
     *
     * @param string $sql
     * @param array  $prepare
     * @param string $fetch
     *
     * @return array|bool|mixed|\PDOStatement Retorna FALSE en caso de error.
     */
    public function executeSelect($sql, $prepare, $fetch = '') {
        $output      = \FALSE;
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
    
    /**
     * Método que ejecuta la consulta.
     *
     * @param string $sql     Consulta.
     * @param array  $prepare Lista de indices a reemplazar en la consulta.
     *
     * @return bool Si es TRUE, la consulta se ejecuto correctamente.
     */
    private function execute($sql, $prepare) {
        $this->prepareObject = $this->connection->prepare($sql);
        
        if (!$this->bindValue($prepare)) {
            return \FALSE;
        }
        
        return $this->prepareObject->execute();
    }
    
    /**
     * Método que comprueba los tipos de datos de los valores vinculados a un parámetro.
     *
     * @param array $data Lista de indices a reemplazar en la consulta.
     *
     * @return bool Si es \TRUE la operación se realizado correctamente.
     */
    private function bindValue($data) {
        $count = \count($data);
        $error = \FALSE;
        
        for ($i = 0; $i < $count && !$error; ++$i) {
            $value          = $data[$i];
            $parameter      = $value['parameter'];
            $parameterValue = $value['value'];
            
            if (!$this->prepareObject->bindValue($parameter, $parameterValue, $value['dataType'])) {
                $error = \TRUE;
            }
            
            if (!\is_numeric($parameterValue)) {
                $parameterValue = "'$parameterValue'";
            }
            //Reemplaza los parámetros con sus valores correspondientes.
            $this->query = \str_replace($parameter, $parameterValue, $this->query);
        }
        
        return !$error;
    }
    
    /**
     * Método que ejecuta una consulta "INSERT".
     *
     * @param string $table   Nombre de la tabla.
     * @param string $columns Nombre de las columnas.
     * @param string $values  Valores.
     * @param array  $prepare Lista de indices a reemplazar en la consulta.
     *                        Usar DBController::prepareStatement().
     *
     * @return bool Si es \TRUE la consulta se ejecuto correctamente.
     */
    public function insert($table, $columns, $values, $prepare) {
        $sql         = "INSERT INTO $table ($columns) VALUES ($values)";
        $this->query = $sql;
        
        return $this->execute($sql, $prepare);
    }
    
    /**
     * Método que ejecuta una consulta "UPDATE".
     *
     * @param string $table   Nombre de la tabla.
     * @param string $columns Asignación de valores a la columna.
     * @param string $where   Condiciones de la consulta.
     * @param array  $prepare Lista de indices a reemplazar en la consulta.
     *                        Usar DBController::prepareStatement().
     *
     * @return bool Si es \TRUE la consulta se ejecuto correctamente.
     */
    public function update($table, $columns, $where, $prepare) {
        $sql         = "UPDATE $table SET $columns WHERE $where";
        $this->query = $sql;
        
        return $this->execute($sql, $prepare);
    }
    
    /**
     * Método que ejecuta una consulta "DELETE".
     *
     * @param string $table   Nombre de la tabla.
     * @param string $where   Condiciones de la consulta.
     * @param array  $prepare Lista de indices a reemplazar en la consulta.
     *                        Usar DBController::prepareStatement().
     *
     * @return bool Si es \TRUE la consulta se ejecuto correctamente.
     */
    public function delete($table, $where, $prepare) {
        $sql         = "DELETE FROM $table WHERE $where";
        $this->query = $sql;
        
        if ($this->execute($sql, $prepare)) {
            return $this->prepareObject->rowCount();
        }
        
        return \FALSE;
    }
    
    /**
     * Método que obtiene el ID del ultimo dato en la consulta INSERT.
     * @return int
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Método que cierra la conexión actual.
     */
    public function close() {
        $this->connection = NULL;
    }
    
    /**
     * Método que obtiene la instancia de la conexión actual.
     * @return \PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Método que obtiene la sentencia SQL.
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }
    
}
