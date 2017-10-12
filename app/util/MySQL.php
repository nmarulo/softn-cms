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
            $strConnection = sprintf('mysql:host=%1$s;dbname=%2$s;charset=%3$s', DB_HOST, DB_NAME, DB_CHARSET);
            //Conexión con la base de datos. PDO Object.
            $this->connection = new \PDO($strConnection, DB_USER, DB_PASSWORD);
            $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, FALSE);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $ex) {
            die('Error al intentar establecer la conexión con la base de datos.' . $ex->getMessage());
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
    public static function prepareStatement($parameter, $value, $dataType, $column = '') {
        return [
            'parameter' => $parameter,
            'value'     => $value,
            'dataType'  => $dataType,
            'column'    => $column,
        ];
    }
    
    /**
     * Método que ejecuta una consulta.
     *
     * @param string $query          Consulta SQL.
     * @param array  $parameterQuery [Opcional] Lista de indices a reemplazar en la consulta.
     *                               Usar prepareStatement().
     *
     * @return array|bool|mixed|\PDOStatement
     */
    public function select($query, $parameterQuery = []) {
        $output      = FALSE;
        $this->query = $query;
        
        if ($this->execute($query, $parameterQuery)) {
            $output = $this->prepareObject->fetchAll();
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
            return FALSE;
        }
        
        $output = false;
        
        try {
            $output = $this->prepareObject->execute();
            
            if (!$output) {
                Logger::getInstance()
                      ->error('No se logro ejecutar la consulta.');
            }
        } catch (\PDOException $ex) {
            if (DEBUG) {
                Messages::addDanger($ex->getMessage());
            }
            
            Logger::getInstance()
                  ->error($ex->getMessage());
        }
        
        return $output;
    }
    
    /**
     * Método que comprueba los tipos de datos de los valores vinculados a un parámetro.
     *
     * @param array $parameterQuery Lista de indices a reemplazar en la consulta.
     *
     * @return bool Si es \TRUE la operación se realizado correctamente.
     */
    private function bindValue($parameterQuery) {
        $count = count($parameterQuery);
        $error = FALSE;
        
        for ($i = 0; $i < $count && !$error; ++$i) {
            $value          = $parameterQuery[$i];
            $parameter      = ':' . $value['parameter'];
            $parameterValue = $value['value'];
            
            try {
                if (!$this->prepareObject->bindValue($parameter, $parameterValue, $value['dataType'])) {
                    $error = TRUE;
                }
            } catch (\PDOException $ex) {
                if (DEBUG) {
                    Messages::addDanger($ex->getMessage());
                }
                
                $error = TRUE;
                Logger::getInstance()
                      ->error($ex->getMessage());
            }
            
            if (!is_numeric($parameterValue)) {
                $parameterValue = "'$parameterValue'";
            }
            //Reemplaza los parámetros con sus valores correspondientes.
            $this->query = preg_replace("/$parameter/", $parameterValue, $this->query, 1);
        }
        
        Logger::getInstance()
              ->debug($this->query);
        
        if ($error) {
            Logger::getInstance()
                  ->error('Error al establecer los tipos de datos de los valores vinculados a un parámetro', ['currentParam' => $parameterQuery]);
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
        
        $strFields   = implode(', ', $fields);
        $strValues   = implode(', ', $values);
        $query       = sprintf('INSERT INTO %1$s (%2$s) VALUES (%3$s)', $table, $strFields, $strValues);
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
        $fields = array_filter($parameterQuery, function($value) use ($column) {
            return $value['parameter'] != $column;
        });
        $fields = array_map(function($value) {
            return $value['parameter'] . ' = :' . $value['parameter'];
        }, $fields);
        
        $strFields   = implode(', ', $fields);
        $query       = sprintf('UPDATE %1$s SET %2$s WHERE %3$s = :%3$s', $table, $strFields, $column);
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
    public function deleteConditional($table, $parameterQuery, $logicalOperator) {
        $values = array_map(function($value) {
            $column = Arrays::get($value, 'column');
            $param  = Arrays::get($value, 'parameter');
            $column = empty($column) ? $param : $column;
            
            return $column . ' = :' . $param;
        }, $parameterQuery);
        
        //En caso de enviar mas de un dato en el "$parameterQuery".
        $strValues = implode(' ' . $logicalOperator . ' ', $values);
        $query     = sprintf('DELETE FROM %1$s WHERE %2$s', $table, $strValues);
        
        return $this->delete($query, $parameterQuery);
    }
    
    public function delete($query, $parameterQuery) {
        $this->query = $query;
        
        if ($this->execute($query, $parameterQuery)) {
            return $this->prepareObject->rowCount();
        }
        
        return FALSE;
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
