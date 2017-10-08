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
    
    /** @var array */
    private $prepareStatement;
    
    /** @var DBAbstract */
    private $db;
    
    /**
     * ManagerAbstract constructor.
     */
    public function __construct() {
        $this->prepareStatement = [];
        $this->db               = NULL;
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
        DBAbstract::addPrepareStatement($this->prepareStatement, $parameter, $value, $dataType, $column);
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
        $db = $this->getDB();
        
        return $this->checkResult($db->select($query));
    }
    
    /**
     * @return MySQL
     */
    private function getDB() {
        $db = new MySQL();
        $db->setTable($this->getTableWithPrefix());
        $db->setPrepareStatement($this->prepareStatement);
        $this->prepareStatement = [];
        $this->db               = $db;
        
        return $db;
    }
    
    /**
     * @param $result
     *
     * @return array|bool
     */
    private function checkResult($result) {
        if (empty($result) || !is_array($result)) {
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
        $db     = $this->getDB();
        $result = $db->select($query);
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
            
            if (!empty($this->prepareStatement)) {
                $conditionsMap = array_map(function($key, $value) {
                    $column = Arrays::get($value, 'column');
                    
                    return "$column = $key";
                }, array_keys($this->prepareStatement), $this->prepareStatement);
                
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
        $db = $this->getDB();
        
        if ($db->insert()) {
            return $db->getLastInsetId();
        }
        
        return FALSE;
    }
    
    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function updateByColumnId($object) {
        if (empty($object)) {
            return FALSE;
        }
        
        $this->prepareStatement($object);
        $db = $this->getDB();
        
        return $db->updateByColumn(self::COLUMN_ID);
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
        $db = $this->getDB();
        
        return $db->delete();
    }
    
    /**
     * @param int $id
     *
     * @return int
     */
    public function deleteById($id) {
        if (empty($id)) {
            return FALSE;
        }
        
        $db = $this->getDB();
        $db->prepareStatement(self::COLUMN_ID, $id, \PDO::PARAM_INT);
        
        return $db->delete();
    }
    
    /**
     * @return int
     */
    public function getLastInsertId() {
        return $this->db->getLastInsetId();
    }
    
    /**
     * @return int
     */
    protected function getRowCount() {
        return $this->db->getRowCount();
    }
    
    /**
     * @return string
     */
    protected function getQuery() {
        return $this->db->getQuery();
    }
}
