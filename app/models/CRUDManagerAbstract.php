<?php
/**
 * CRUDManagerAbstract.php
 */

namespace SoftnCMS\models;

use SoftnCMS\util\MySQL;

/**
 * Class CRUDManagerAbstract
 * @author Nicolás Marulanda P.
 */
abstract class CRUDManagerAbstract extends ManagerAbstract {
    
    const FORM_UPDATE = 'update';
    
    const FORM_CREATE = 'create';
    
    /** @var int */
    private $lastInsert;
    
    /**
     * CRUDManagerAbstract constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @return int
     */
    public function getLastInsert() {
        return $this->lastInsert;
    }
    
    protected function createData() {
        $this->lastInsert = 0;
        $mySQL            = new MySQL();
        $result           = $mySQL->insert(parent::getTableWithPrefix(), $this->columns, $this->values, $this->prepare);
        
        if ($result) {
            $this->lastInsert = $mySQL->lastInsertId();
        }
        
        $mySQL->close();
        parent::clearData();
        
        
        return $result;
    }
    
    protected function updateData() {
        $mySQL  = new MySQL();
        $result = $mySQL->update(parent::getTableWithPrefix(), $this->columns, $this->values, $this->prepare);
        $mySQL->close();
        parent::clearData();
        
        return $result;
    }
    
    protected function deleteData() {
        $mySQL  = new MySQL();
        $result = $mySQL->delete(parent::getTableWithPrefix(), $this->values, $this->prepare);
        $mySQL->close();
        parent::clearData();
        
        return $result;
    }
    
    protected function addPrepareUpdate($parameter, $value, $dataType) {
        if ($value !== NULL) {
            $param         = ":$parameter";
            $this->columns .= empty($this->columns) ? '' : ', ';
            $this->columns .= "$parameter = $param";
            parent::prepare($param, $value, $dataType);
        }
    }
    
    protected function addPrepareInsert($parameter, $value, $dataType) {
        $param        = ":$parameter";
        $this->values .= empty($this->values) ? '' : ', ';
        $this->values .= $param;
        parent::setColumns($parameter);
        parent::prepare($param, $value, $dataType);
    }
    
}
