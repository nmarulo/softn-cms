<?php
/**
 * ManagerAbstract.php
 */

namespace SoftnCMS\util\database;

use SoftnCMS\util\Arrays;

/**
 * Class ManagerAbstract
 * @author Nicolás Marulanda P.
 */
abstract class ManagerAbstract {
    
    const COLUMN_ID = 'id';
    
    /** @var DBInterface */
    private $connection;
    
    /**
     * ManagerAbstract constructor.
     *
     * @param DBInterface $connection
     */
    public function __construct(DBInterface $connection = NULL) {
        $this->connection = $connection;
    }
    
    /**
     * @param int $id
     *
     * @return bool|mixed
     */
    public function searchById($id) {
        return Arrays::findFirst($this->searchAllByColumn($id, self::COLUMN_ID, \PDO::PARAM_INT));
    }
    
    /**
     * @param mixed  $value
     * @param string $column
     * @param int    $dataType
     * @param array  $addConditions
     *
     * @return array|bool
     */
    protected function searchAllByColumn($value, $column, $dataType, $addConditions = []) {
        $conditions = '';
        
        if (!empty($addConditions)) {
            $conditions = ' ' . implode(' ', $addConditions);
        }
        
        $this->addPrepareStatement($column, $value, $dataType);
        $query = sprintf('SELECT * FROM %1$s WHERE %2$s = :%2$s%3$s', $this->getTableWithPrefix(), $column, $conditions);
        
        return $this->search($query);
    }
    
    protected function addPrepareStatement($parameter, $value, $dataType, $column = '') {
        $this->connection->addPrepareStatement($parameter, $value, $dataType, $column);
    }
    
    /**
     * @param string $table
     *
     * @return string
     */
    protected function getTableWithPrefix($table = '') {
        if (empty($table)) {
            return DB_PREFIX . $this->getTable();
        }
        
        return DB_PREFIX . $table;
    }
    
    /**
     * @return string
     */
    protected abstract function getTable();
    
    /**
     * @param string $query
     *
     * @return array|bool
     */
    protected function search($query) {
        return $this->checkResult($this->connection->select($query));
    }
    
    /**
     * @param $result
     *
     * @return array|bool
     */
    private function checkResult($result) {
        if (empty($result) && !is_array($result)) {
            return FALSE;
        }
        
        $objects = [];
        
        array_walk($result, function($value) use (&$objects) {
            $objects[] = $this->buildObject($value);
        });
        
        return $objects;
    }
    
    /**
     * @param $result
     *
     * @return TableAbstract
     */
    protected abstract function buildObject($result);
    
    public function count() {
        $query  = 'SELECT COUNT(*) AS COUNT FROM ' . $this->getTableWithPrefix();
        $result = $this->connection->select($query);
        $result = Arrays::findFirst($result);
        
        return empty($result) ? 0 : $result;
    }
    
    public function searchAll($limit = '', $orderBy = '') {
        $addConditions = [];
        $strOrderBy    = 'ORDER BY ';
        
        if (empty($orderBy)) {
            $orderBy = self::COLUMN_ID . ' DESC';
        }
        
        $addConditions[] = $strOrderBy . $orderBy;
        
        if (!empty($limit)) {
            $addConditions[] = "LIMIT $limit";
        }
        
        return $this->searchAllByObject(NULL, $addConditions);
    }
    
    /**
     * @param mixed $object
     * @param array $addConditions
     *
     * @return bool|array
     */
    protected function searchAllByObject($object = NULL, $addConditions = []) {
        $conditions = '';
        
        if (!empty($addConditions)) {
            $conditions = ' ' . implode(' ', $addConditions);
        }
        
        if (!empty($object)) {
            $this->prepareStatement($object);
            $prepareStatement = $this->connection->getPrepareStatement();
            
            if (!empty($prepareStatement)) {
                $conditionsMap = array_map(function($key, $value) {
                    $column = Arrays::get($value, 'column');
                    
                    return "$column = $key";
                }, array_keys($prepareStatement), $prepareStatement);
                
                $conditions = ' WHERE ' . implode(" AND ", $conditionsMap) . $conditions;
            }
        }
        
        $query = sprintf('SELECT * FROM %1$s%2$s', $this->getTableWithPrefix(), $conditions);
        
        return $this->search($query);
    }
    
    /**
     * @param TableAbstract $object
     */
    protected abstract function prepareStatement($object);
    
    /**
     * @param mixed $object
     *
     * @return int|bool
     */
    public function create($object) {
        if (empty($object)) {
            return FALSE;
        }
        
        $this->prepareStatement($object);
        $this->connection->setTable($this->getTableWithPrefix());
        
        if ($this->connection->insert()) {
            return $this->connection->getLastInsetId();
        }
        
        return FALSE;
    }
    
    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function updateByColumnId($object) {
        return $this->updateByColumn($object, self::COLUMN_ID);
    }
    
    protected function updateByColumn($object, $columnName) {
        if (empty($object)) {
            return FALSE;
        }
        
        $this->prepareStatement($object);
        $this->connection->setTable($this->getTableWithPrefix());
        
        return $this->connection->updateByColumn($columnName);
    }
    
    /**
     * @param TableAbstract $object
     *
     * @return int
     */
    public function deleteByObject($object) {
        if (empty($object)) {
            return FALSE;
        }
        
        $this->prepareStatement($object);
        
        return $this->deleteByPrepareStatement();
    }
    
    /**
     * @param string $allLogicalOperators
     *
     * @return bool
     */
    protected function deleteByPrepareStatement($allLogicalOperators = 'AND') {
        if (empty($this->connection->getPrepareStatement())) {
            return FALSE;
        }
        
        $this->connection->setTable($this->getTableWithPrefix());
        
        return $this->connection->deleteByPrepareStatement($allLogicalOperators);
    }
    
    /**
     * @param int $id
     *
     * @return bool
     */
    public function deleteById($id) {
        if (empty($id)) {
            return FALSE;
        }
        
        $this->addPrepareStatement(self::COLUMN_ID, $id, \PDO::PARAM_INT);
        
        return $this->deleteByPrepareStatement();
    }
    
    /**
     * @return int
     */
    public function getLastInsertId() {
        return $this->connection->getLastInsetId();
    }
    
    public function delete($query) {
        if (empty($query)) {
            return FALSE;
        }
        
        $this->connection->setTable($this->getTableWithPrefix());
        
        return $this->connection->delete($query);
    }
    
    /**
     * @return int
     */
    public function getRowCount() {
        return $this->connection->getRowCount();
    }
    
    /**
     * @return DBInterface
     */
    protected function getConnection() {
        return $this->connection;
    }
    
    /**
     * @param DBAbstract $connection
     */
    public function setConnection($connection) {
        $this->connection = $connection;
    }
    
    protected function deleteByColumn($value, $column, $dataType) {
        if (empty($value)) {
            return FALSE;
        }
        
        $this->addPrepareStatement($column, $value, $dataType);
        
        return $this->deleteByPrepareStatement();
    }
    
    /**
     * @return string
     */
    protected function getQuery() {
        return $this->connection->getQuery();
    }
    
}
