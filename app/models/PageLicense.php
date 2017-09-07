<?php
/**
 * License.php
 */

namespace SoftnCMS\models;

use SoftnCMS\util\Arrays;

/**
 * Class License
 * @author Nicolás Marulanda P.
 */
class PageLicense implements \Serializable {
    
    /** @var string */
    private $pageName;
    
    /** @var array */
    private $methods;
    
    /** @var bool */
    private $canUpdate;
    
    /** @var bool */
    private $canInsert;
    
    /** @var bool */
    private $canDelete;
    
    /**
     * PageLicense constructor.
     *
     * @param string $pageName
     * @param array  $methods
     */
    public function __construct($pageName, array $methods = []) {
        $this->pageName  = $pageName;
        $this->methods   = $methods;
        $this->canInsert = FALSE;
        $this->canUpdate = FALSE;
        $this->canDelete = FALSE;
    }
    
    public function serialize() {
        return serialize([
            $this->pageName,
            $this->methods,
            $this->canInsert,
            $this->canUpdate,
            $this->canDelete,
        ]);
    }
    
    public function unserialize($serialized) {
        list($this->pageName, $this->methods, $this->canInsert, $this->canUpdate, $this->canDelete) = unserialize($serialized);
    }
    
    /**
     * @param MethodLicense $method
     */
    public function addOrUpdateMethod($method) {
        $methodName = $method->getMethodName();
        
        if (Arrays::keyExists($this->methods, $methodName)) {
            $currentMethod       = Arrays::get($this->methods, $methodName);
            $currentMethodFields = $currentMethod->getFields();
            $merge               = array_merge($currentMethodFields, $method->getFields());
            $fields              = array_diff($merge, $currentMethodFields);
            array_walk($fields, function($field) use (&$currentMethod) {
                $currentMethod->addField($field);
            });
            $method = $currentMethod;
        }
        
        $this->methods[$methodName] = $method;
    }
    
    /**
     * @param MethodLicense $method
     */
    public function removeMethod($method) {
        unset($this->methods[$method->getMethodName()]);
    }
    
    /**
     * @return string
     */
    public function getPageName() {
        return $this->pageName;
    }
    
    /**
     * @return array
     */
    public function getMethods() {
        return $this->methods;
    }
    
    /**
     * @return bool
     */
    public function isCanUpdate() {
        return $this->canUpdate;
    }
    
    /**
     * @param bool $canUpdate
     */
    public function setCanUpdate($canUpdate) {
        $this->canUpdate = $canUpdate;
    }
    
    /**
     * @return bool
     */
    public function isCanInsert() {
        return $this->canInsert;
    }
    
    /**
     * @param bool $canInsert
     */
    public function setCanInsert($canInsert) {
        $this->canInsert = $canInsert;
    }
    
    /**
     * @return bool
     */
    public function isCanDelete() {
        return $this->canDelete;
    }
    
    /**
     * @param bool $canDelete
     */
    public function setCanDelete($canDelete) {
        $this->canDelete = $canDelete;
    }
    
}
