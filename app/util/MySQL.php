<?php
/**
 * MySQL.php
 */

namespace SoftnCMS\util;

/**
 * Class MySQL
 * @author Nicolás Marulanda P.
 */
class MySQL {
    
    const FETCH_ALL    = 'fetchAll';
    
    const FETCH_OBJECT = 'fetchObject';
    
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
            $strConnection = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            //Conexión con la base de datos. PDO Object.
            $this->connection = new \PDO($strConnection, DB_USER, DB_PASSWORD);
            $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, FALSE);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $ex) {
            die('Error al intentar establecer la conexión con la base de datos.');
        }
    }
    
    /**
     * Método que obtiene los indices a reemplazar en la consulta.
     * EJ: [
     *   [
     *      'parameter' => ':id',
     *      'value'     => 1,
     *      'dataType'  => PDO::PARAM_INT,
     *   ],
     *   [
     *      'parameter' => ':nombre',
     *      'value'     => 'nicolas',
     *      'dataType'  => PDO::PARAM_STR,
     *   ],
     * ]
     *
     * @param string     $parameter Indice a buscar. EJ: ":ID"
     * @param string|int $value     Valor del indice.
     * @param int        $dataType  Tipo de dato. EJ: \PDO::PARAM_*
     *
     * @return array
     */
    public static function prepareStatement($parameter, $value, $dataType) {
        return [
            'parameter' => $parameter,
            'value'     => $value,
            'dataType'  => $dataType,
        ];
    }
    
    /**
     * Método que ejecuta una consulta.
     *
     * @param string $query          Consulta SQL.
     * @param string $fetch          [Opcional] Tipo de datos a retornar.
     * @param array  $parameterQuery [Opcional] Lista de indices a reemplazar en la consulta.
     *                               Usar prepareStatement().
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function select($query, $fetch = '', $parameterQuery = []) {
        $output      = \FALSE;
        $this->query = $query;
        
        if ($this->execute($query, $parameterQuery)) {
            
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
     * @param string $query          Consulta SQL.
     * @param array  $parameterQuery Lista de indices a reemplazar en la consulta.
     *
     * @return bool Si es TRUE, la consulta se ejecuto correctamente.
     */
    private function execute($query, $parameterQuery) {
        $this->prepareObject = $this->connection->prepare($query);
        
        if (!$this->bindValue($parameterQuery)) {
            return \FALSE;
        }
        
        try {
            
            return $this->prepareObject->execute();
        } catch (\PDOException $ex) {
            
            return FALSE;
        }
    }
    
    /**
     * Método que comprueba los tipos de datos de los valores vinculados a un parámetro.
     *
     * @param array $parameterQuery Lista de indices a reemplazar en la consulta.
     *
     * @return bool Si es \TRUE la operación se realizado correctamente.
     */
    private function bindValue($parameterQuery) {
        $count = \count($parameterQuery);
        $error = \FALSE;
        
        for ($i = 0; $i < $count && !$error; ++$i) {
            $value          = $parameterQuery[$i];
            $parameter      = ':' . $value['parameter'];
            $parameterValue = $value['value'];
            
            try {
                if (!$this->prepareObject->bindValue($parameter, $parameterValue, $value['dataType'])) {
                    $error = \TRUE;
                }
            } catch (\PDOException $ex) {
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
     * @param string $table          Nombre de la tabla.
     * @param array  $parameterQuery Lista de indices a reemplazar en la consulta.
     *                               Usar prepareStatement().
     *
     * @return bool Si es \TRUE la consulta se ejecuto correctamente.
     */
    public function insert($table, $parameterQuery) {
        $fields = array_map(function($value) {
            return $value['parameter'];
        }, $parameterQuery);
        
        $values = array_map(function($value) {
            return ':' . $value['parameter'];
        }, $parameterQuery);
        
        $query       = 'INSERT INTO ';
        $query       .= $table;
        $query       .= ' (';
        $query       .= implode(', ', $fields);
        $query       .= ') VALUES (';
        $query       .= implode(', ', $values);
        $query       .= ')';
        $this->query = $query;
        
        return $this->execute($query, $parameterQuery);
    }
    
    /**
     * Método que ejecuta una consulta "UPDATE".
     *
     * @param string $table          Nombre de la tabla.
     * @param array  $parameterQuery Lista de indices a reemplazar en la consulta.
     *                               Usar prepareStatement().
     * @param string $column         Por ejemplo: Nombre de la columna "ID" en la tabla.
     *
     * @return bool Si es \TRUE la consulta se ejecuto correctamente.
     */
    public function update($table, $parameterQuery, $column) {
        $fields = array_filter($parameterQuery, function($value) use ($column){
           return $value['parameter'] != $column;
        });
        $fields = array_map(function($value) {
            return $value['parameter'] . ' = :' . $value['parameter'];
        }, $fields);
        
        $query       = 'UPDATE ';
        $query       .= $table;
        $query       .= ' SET ';
        $query       .= implode(', ', $fields);
        $query       .= ' WHERE ';
        $query       .= "$column = :$column";
        $this->query = $query;
        
        return $this->execute($query, $parameterQuery);
    }
    
    /**
     * Método que ejecuta una consulta "DELETE",
     * si la lista "$parameterQuery" es mayor a 1,
     * se concatenaran con el operador lógico "AND".
     *
     * @param string $table          Nombre de la tabla.
     * @param array  $parameterQuery Lista de indices a reemplazar en la consulta.
     *                               Usar prepareStatement().
     *
     * @return bool Si es \TRUE la consulta se ejecuto correctamente.
     */
    public function delete($table, $parameterQuery) {
        $values = array_map(function($value) {
            return $value['parameter'] . ' = :' . $value['parameter'];
        }, $parameterQuery);
        
        $query = 'DELETE FROM ';
        $query .= $table;
        $query .= ' WHERE ';
        //En caso de enviar mas de un dato en el "$parameterQuery".
        $query       .= implode(' AND ', $values);
        $this->query = $query;
        
        if ($this->execute($query, $parameterQuery)) {
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
