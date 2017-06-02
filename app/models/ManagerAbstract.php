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
        $this->prepare[] = MySQL::prepareStatement($parameter, $value, $dataType);
    }
    
    /**
     * @param string $parameter
     *
     * @return bool|mixed
     */
    protected function searchBy($parameter) {
        $query = 'SELECT * FROM ';
        $query .= $this->getTableWithPrefix();
        $query .= " WHERE $parameter = :$parameter";
        
        return Arrays::get($this->readData($query), 0);
    }
    
    /**
     * @return string
     */
    protected function getTableWithPrefix() {
        return DB_PREFIX . $this->getTable();
    }
    
    protected abstract function getTable();
    
    /**
     * @param string $query
     *
     * @return array
     */
    protected function readData($query = "") {
        if (empty($query)) {
            $query = 'SELECT * FROM ';
            $query .= $this->getTableWithPrefix();
            $query .= ' ORDER BY ID DESC';
        }
        
        $objects = [];
        $mySQL   = new MySQL();
        $result  = $mySQL->select($query, $this->prepare);
        $this->closeConnection($mySQL);
        
        foreach ($result as $value) {
            $objects[] = $this->buildObjectTable($value);
        }
        
        return $objects;
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
        
        return null;
    }
    
    public function read() {
        return $this->readData();
    }
}
