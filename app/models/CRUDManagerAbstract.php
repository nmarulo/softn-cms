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
    
    /**
     * Identifica si el formulario es para actualizar datos.
     */
    const FORM_UPDATE = 'update';
    
    /**
     * Identifica si el formulario es para insertar datos.
     */
    const FORM_CREATE = 'create';
    
    /** @var int */
    private $lastInsertId;
    
    /**
     * CRUDManagerAbstract constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @return int
     */
    public function getLastInsertId() {
        return $this->lastInsertId;
    }
    
    /**
     * @param TableAbstract $object
     *
     * @return bool
     */
    public function update($object) {
        parent::parameterQuery(self::ID, $object->getId(), \PDO::PARAM_INT);
        
        return $this->updateData($object);
    }
    
    /**
     * @param        $object
     * @param string $column
     *
     * @return bool
     */
    protected function updateData($object, $column = self::ID) {
        $this->addParameterQuery($object);
        $mySQL  = new MySQL();
        $result = $mySQL->update(parent::getTableWithPrefix(), $this->prepare, $column);
        parent::closeConnection($mySQL);
        
        return $result;
    }
    
    protected abstract function addParameterQuery($object);
    
    /**
     * @param $object
     *
     * @return bool
     */
    public function create($object) {
        $this->addParameterQuery($object);
        $this->lastInsertId = 0;
        $mySQL              = new MySQL();
        $result             = $mySQL->insert(parent::getTableWithPrefix(), $this->prepare);
        
        if ($result) {
            $this->lastInsertId = $mySQL->lastInsertId();
        }
        
        parent::closeConnection($mySQL);
        
        return $result;
    }
    
    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete($id) {
        parent::parameterQuery(self::ID, $id, \PDO::PARAM_INT);
        $mySQL  = new MySQL();
        $result = $mySQL->delete(parent::getTableWithPrefix(), $this->prepare);
        parent::closeConnection($mySQL);
        
        return $result;
    }
}
