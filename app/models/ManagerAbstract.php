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
    
    /** @var string */
    protected $columns;
    
    /** @var string */
    protected $values;
    
    /** @var array */
    protected $prepare;
    
    /**
     * ManagerAbstract constructor.
     */
    public function __construct() {
        $this->clearData();
    }
    
    protected function getById($id) {
        $this->setColumns('*');
        $this->addPrepareWhere(self::ID, $id, \PDO::PARAM_INT);
        
        return Arrays::get($this->readData(), 0);
    }
    
    protected function addPrepareWhere($parameter, $value, $dataType, $comparisionOperator = '=', $logicalOperator = '') {
        $param        = ":$parameter";
        $this->values .= empty($this->values) ? '' : " $logicalOperator ";
        $this->values .= "$parameter $comparisionOperator $param";
        
        $this->prepare($param, $value, $dataType);
    }
    
    protected function prepare($parameter, $value, $dataType) {
        $this->prepare[] = MySQL::prepareStatement($parameter, $value, $dataType);
    }
    
    protected function readData($limit = '', $orderBy = 'ID DESC', $fetch = MySQL::FETCH_ALL) {
        $mySQL  = new MySQL();
        $result = $mySQL->select($this->getTableWithPrefix(), $fetch, $this->values, $this->prepare, $this->columns, $orderBy, $limit);
        $mySQL->close();
        $this->clearData();
        
        $objects = [];
        
        foreach ($result as $value) {
            $objects[] = $this->buildObjectTable($value);
        }
        
        return $objects;
    }
    
    protected function clearData() {
        $this->prepare = [];
        $this->values  = '';
        $this->columns = '';
    }
    
    protected abstract function buildObjectTable($result, $fetch = MySQL::FETCH_ALL);
    
    protected function setColumns($column) {
        $this->columns .= empty($this->columns) ? '' : ', ';
        $this->columns .= $column;
    }
    
    protected abstract function getTable();
    
    protected function getTableWithPrefix(){
        return DB_PREFIX . $this->getTable();
    }
}
