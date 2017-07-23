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
    
    const ID          = 'ID';
    
    const FORM_SUBMIT = 'form_submit';
    
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
     * @param string $column
     */
    protected function parameterQuery($parameter, $value, $dataType, $column = '') {
        if (!is_null($value)) {
            $this->prepare[] = MySQL::prepareStatement($parameter, $value, $dataType, $column);
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
    
    /**
     * @param string $parameter
     * @param string $orderBy
     *
     * @return array
     */
    protected function searchAllBy($parameter, $orderBy = '') {
        $table = $this->getTableWithPrefix();
        $query = sprintf('SELECT * FROM %1$s WHERE %2$s = :%2$s', $table, $parameter);
        
        if (!empty($orderBy)) {
            $query .= " ORDER BY $orderBy DESC";
        }
        
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
    protected function readData($query = '') {
        if (empty($query)) {
            $table = $this->getTableWithPrefix();
            $query = sprintf('SELECT * FROM %1$s ORDER BY %2$s DESC', $table, self::ID);
        }
        
        $objects = [];
        $result  = $this->select($query);
        array_walk($result, function($value) use (&$objects) {
            $objects[] = $this->buildObjectTable($value);
        });
        
        return $objects;
    }
    
    protected function select($query) {
        $mySQL  = new MySQL();
        $result = $mySQL->select($query, $this->prepare);
        $this->closeConnection($mySQL);
        
        if (empty($result)) {
            return [];
        }
        
        return $result;
    }
    
    /**
     * @param MySQL $connection
     */
    protected function closeConnection(MySQL $connection) {
        $connection->close();
        $this->prepare = [];
    }
    
    /**
     * @param $result
     *
     * @return null
     * @throws \Exception
     */
    protected function buildObjectTable($result) {
        if (empty($result) || !is_array($result)) {
            throw new \Exception('Error');
        }
        
        return NULL;
    }
    
    /**
     * @param array $filters
     *
     * @return array
     */
    public function read($filters = []) {
        if (empty($filters)) {
            return $this->readData();
        }
        
        $limit = Arrays::get($filters, 'limit');
        $table = $this->getTableWithPrefix();
        $query = sprintf('SELECT * FROM %1$s ORDER BY %2$s DESC', $table, self::ID);
        $query .= $limit === FALSE ? '' : " LIMIT $limit";
        
        return $this->readData($query);
    }
    
    /**
     * @return bool|int
     */
    public function count() {
        $table  = $this->getTableWithPrefix();
        $query  = sprintf('SELECT COUNT(*) AS COUNT FROM %s', $table);
        $result = $this->select($query);
        $result = Arrays::get($result, 0);
        $result = Arrays::get($result, 'COUNT');
        
        return $result === FALSE ? 0 : $result;
    }
    
}
