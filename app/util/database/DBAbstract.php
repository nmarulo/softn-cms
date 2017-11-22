<?php
/**
 * DBAbstract.php
 */

namespace SoftnCMS\util\database;

use SoftnCMS\util\Arrays;
use SoftnCMS\util\Logger;

/**
 * Class DBAbstract
 * @author Nicolás Marulanda P.
 */
abstract class DBAbstract implements DBInterface {
    
    /** @var array */
    private $prepareStatement;
    
    /** @var string */
    private $query;
    
    /** @var int */
    private $rowCount;
    
    /** @var int */
    private $lastInsertId;
    
    /** @var string */
    private $table;
    
    /**
     * DBAbstract constructor.
     */
    public function __construct() {
        $this->prepareStatement = [];
        $this->query            = '';
        $this->rowCount         = 0;
        $this->lastInsertId     = 0;
        $this->table            = '';
    }
    
    /**
     * @return null|DBInterface
     */
    public static function getNewInstance() {
        //TODO: Lista de tipos.
        $connection = NULL;
        
        switch (DB_TYPE) {
            case 'mysql':
                $connection = new MySQL();
                break;
        }
        
        return $connection;
    }
    
    /**
     * @param string $parameter
     * @param mixed  $value
     * @param string $dataType
     * @param string $column
     */
    public function addPrepareStatement($parameter, $value, $dataType = '', $column = '') {
        if (!is_null($value)) {
            if ($dataType === '') {
                $dataType = self::getDataType($value);
            }
            
            if (empty($column)) {
                $column = $parameter;
            }
            
            $this->prepareStatement[":$parameter"] = [
                'value'    => $value,
                'dataType' => $dataType,
                'column'   => $column,
            ];
        }
    }
    
    /**
     * @param $value
     *
     * @return int
     * @throws \Exception
     */
    private static function getDataType($value) {
        if (is_bool($value)) {
            $dataType = \PDO::PARAM_BOOL;
        } elseif (is_string($value)) {
            $dataType = \PDO::PARAM_STR;
        } elseif (is_int($value) || is_float($value) || is_numeric($value)) {
            $dataType = \PDO::PARAM_INT;
        } else {
            Logger::getInstance()
                  ->error('Tipo de dato no identificado.', ['value' => $value]);
            throw new \Exception('Tipo de dato no identificado.');
        }
        
        return $dataType;
    }
    
    /**
     * @param $query
     *
     * @return array|bool
     */
    public function select($query) {
        $prepareObject = $this->execute($query);
        
        if (empty($prepareObject)) {
            return FALSE;
        }
        
        $result = $prepareObject->fetchAll();
        $this->close();
        
        return $result;
    }
    
    /**
     * @param string $query
     *
     * @return bool|\PDOStatement
     */
    private function execute($query) {
        $this->query   = $query;
        $prepareObject = $this->prepareStatementObject($query);
        
        try {
            if (!empty($prepareObject) && $prepareObject->execute()) {
                $this->rowCount = $prepareObject->rowCount();
                
                return $prepareObject;
            }
            
            Logger::getInstance()
                  ->warning("No se logro ejecutar la consulta preparada.");
        } catch (\Exception $ex) {
            Logger::getInstance()
                  ->error($ex->getMessage());
        }
        
        return FALSE;
    }
    
    /**
     * @param string @query
     *
     * @return bool|\PDOStatement
     */
    private function prepareStatementObject($query) {
        try {
            $prepare = $this->getConnection()
                            ->prepare($query);
            
            if (!empty($prepare) && $this->bindsValueToParam($prepare)) {
                
                return $prepare;
            }
            
            Logger::getInstance()
                  ->error("Error al preparar la consulta.");
        } catch (\Exception $ex) {
            Logger::getInstance()
                  ->error($ex->getMessage());
        }
        
        return FALSE;
    }
    
    /**
     * @return \PDO
     */
    protected abstract function getConnection();
    
    /**
     * @param \PDOStatement $prepare
     *
     * @return bool
     */
    private function bindsValueToParam(&$prepare) {
        $notError             = TRUE;
        $prepareStatementKeys = array_keys($this->prepareStatement);
        $len                  = count($prepareStatementKeys);
        
        for ($i = 0; $i < $len && $notError; ++$i) {
            $parameter = Arrays::get($prepareStatementKeys, $i);
            $data      = Arrays::get($this->prepareStatement, $parameter);
            $value     = Arrays::get($data, 'value');
            $dataType  = Arrays::get($data, 'dataType');
            
            if ($this->bindValue($prepare, $parameter, $value, $dataType)) {
                if (!is_numeric($value)) {
                    $value = "'$value'";
                }
                //Reemplaza los parámetros con sus valores correspondientes.
                $this->query = preg_replace("/$parameter/", $value, $this->query, 1);
            } else {
                $notError = FALSE;
                Logger::getInstance()
                      ->error('Error al establecer los tipos de datos de los valores vinculados a un parámetro', ['currentParam' => $this->prepareStatement]);
            }
        }
        Logger::getInstance()
              ->debug($this->query);
        
        return $notError;
    }
    
    /**
     * @param \PDOStatement $prepare
     * @param string        $parameter
     * @param mixed         $value
     * @param int           $dataType
     *
     * @return bool
     */
    private function bindValue(&$prepare, $parameter, $value, $dataType) {
        return $prepare->bindValue($parameter, $value, $dataType);
    }
    
    public function close() {
        $this->prepareStatement = [];
    }
    
    public function updateByColumn($column) {
        $columns = array_filter($this->prepareStatement, function($value) use ($column) {
            return Arrays::get($value, 'column') != $column;
        });
        $where   = array_filter($this->prepareStatement, function($value) use ($column) {
            return Arrays::get($value, 'column') == $column;
        });
        $where   = Arrays::findFirst($this->getKeyValue($where));
        $columns = implode(', ', $this->getKeyValue($columns));
        $query   = sprintf('UPDATE %1$s SET %2$s WHERE %3$s', $this->table, $columns, $where);
        
        return $this->update($query);
    }
    
    private function getKeyValue($array) {
        return array_map(function($key, $value) {
            return Arrays::get($value, 'column') . " = $key";
        }, array_keys($array), $array);
    }
    
    /**
     * @param $query
     *
     * @return bool
     */
    public function update($query) {
        if (empty($query)) {
            return FALSE;
        }
        
        $prepareObject = $this->execute($query);
        
        if (empty($prepareObject)) {
            return FALSE;
        }
        
        $this->close();
        
        return TRUE;
    }
    
    /**
     * @param string $allLogicalOperators
     *
     * @return bool
     */
    public function deleteByPrepareStatement($allLogicalOperators = 'AND') {
        $where = $this->getKeyValue($this->prepareStatement);
        $where = implode(" $allLogicalOperators ", $where);
        $query = sprintf('DELETE FROM %1$s WHERE %2$s', $this->table, $where);
        
        return $this->delete($query);
    }
    
    /**
     * @param string $query
     *
     * @return bool
     */
    public function delete($query = '') {
        if (empty($query)) {
            return FALSE;
        }
        
        $prepareObject = $this->execute($query);
        
        if (empty($prepareObject)) {
            return FALSE;
        }
        
        $this->close();
        
        return TRUE;
    }
    
    /**
     * @param $query
     *
     * @return bool
     */
    public function insert($query = '') {
        if (empty($query)) {
            $columns = array_map(function($value) {
                return Arrays::get($value, 'column');
            }, $this->prepareStatement);
            
            $values  = implode(', ', array_keys($this->prepareStatement));
            $columns = implode(', ', $columns);
            $query   = sprintf('INSERT INTO %1$s (%2$s) VALUES (%3$s)', $this->table, $columns, $values);
        }
        
        $prepareObject = $this->execute($query);
        
        if (!$prepareObject) {
            return FALSE;
        }
        
        $this->lastInsertId = $this->getConnection()
                                   ->lastInsertId();
        $this->close();
        
        return TRUE;
        
    }
    
    /**
     * @return int
     */
    public function getLastInsetId() {
        return $this->lastInsertId;
    }
    
    /**
     * @return int
     */
    public function getRowCount() {
        return $this->rowCount;
    }
    
    /**
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }
    
    /**
     * @param string $table
     */
    public function setTable($table) {
        $this->table = $table;
    }
    
    /**
     * @return array
     */
    public function getPrepareStatement() {
        return $this->prepareStatement;
    }
    
    /**
     * @param array $prepareStatement
     */
    public function setPrepareStatement($prepareStatement) {
        $this->prepareStatement = $prepareStatement;
    }
    
    /**
     * @param \PDO $value
     */
    protected abstract function setConnection($value);
    
}
