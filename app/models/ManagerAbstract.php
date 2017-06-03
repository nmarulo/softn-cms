<?php
/**
 * Model.php
 */

namespace SoftnCMS\models;

use SoftnCMS\util\Arrays;
use SoftnCMS\util\MySQL;

/**
 * Class Model
 * @author Nicolás Marulanda P.
 */
abstract class ManagerAbstract {
    
    const ID = 'ID';
    
    /** @var array */
    protected $prepare;
    
    /**
     * ManagerAbstract constructor.
     */
    public function __construct() {
        $this->prepare = [];
    }
    
    /**
     * @param int $id
     *
     * @return bool|mixed
     */
    public function searchById($id) {
        $this->parameterQuery(self::ID, $id, \PDO::PARAM_INT);
        
        return $this->searchBy(self::ID);
    }
    
    /**
     * @param string $parameter
     * @param mixed  $value
     * @param int    $dataType
     */
    protected function parameterQuery($parameter, $value, $dataType) {
        if (!is_null($value)) {
            $this->prepare[] = MySQL::prepareStatement($parameter, $value, $dataType);
        }
    }
    
    /**
     * @param string $parameter
     *
     * @return bool|mixed
     */
    protected function searchBy($parameter) {
        return Arrays::get($this->searchAllBy($parameter), 0);
    }
    
    protected function searchAllBy($parameter) {
        $query = 'SELECT * ';
        $query .= 'FROM ' . $this->getTableWithPrefix($this->getTable());
        $query .= " WHERE $parameter = :$parameter";
        
        return $this->readData($query);
    }
    
    /**
     * @param string $table
     *
     * @return string
     */
    protected function getTableWithPrefix($table = '') {
        if (empty($table)) {
            $table = $this->getTable();
        }
        
        return DB_PREFIX . $table;
    }
    
    protected abstract function getTable();
    
    /**
     * @param string $query
     *
     * @return array
     */
    protected function readData($query = "") {
        if (empty($query)) {
            $query = 'SELECT * ';
            $query .= 'FROM ' . $this->getTableWithPrefix($this->getTable());
            $query .= ' ORDER BY ID DESC';
        }
        
        $objects = [];
        $result  = $this->select($query);
        
        foreach ($result as $value) {
            $objects[] = $this->buildObjectTable($value);
        }
        
        return $objects;
    }
    
    private function select($query) {
        $mySQL  = new MySQL();
        $result = $mySQL->select($query, $this->prepare);
        $this->closeConnection($mySQL);
        
        return $result;
    }
    
    /**
     * @param MySQL $connection
     */
    protected function closeConnection($connection) {
        $connection->close();
        $this->prepare = [];
    }
    
    protected function buildObjectTable($result) {
        if (empty($result) || !is_array($result)) {
            throw new \Exception('Error');
        }
        
        return NULL;
    }
    
    public function read($filters = []) {
        return $this->readData();
    }
    
    public function count() {
        $query  = 'SELECT COUNT(*) AS COUNT ';
        $query  .= 'FROM ' . $this->getTableWithPrefix();
        $result = $this->select($query);
        
        $result = Arrays::get($result, 0);
        
        if ($result === FALSE) {
            return 0;
        }
        
        return Arrays::get($result, 'COUNT');
    }
}
