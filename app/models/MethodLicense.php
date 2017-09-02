<?php
/**
 * MethodLicense.php
 */

namespace SoftnCMS\models;

/**
 * Class MethodLicense
 * @author Nicolás Marulanda P.
 */
class MethodLicense implements \Serializable {
    
    /** @var string */
    private $methodName;
    
    /** @var array */
    private $fields;
    
    /**
     * MethodLicense constructor.
     *
     * @param string $methodName
     * @param array  $fields
     */
    public function __construct($methodName, $fields = []) {
        $this->methodName = $methodName;
        $this->fields     = $fields;
    }
    
    public function serialize() {
        return serialize([
            $this->methodName,
            $this->fields,
        ]);
    }
    
    public function unserialize($serialized) {
        list($this->methodName, $this->fields) = unserialize($serialized);
    }
    
    public function addField($fieldName) {
        if (array_search($fieldName, $this->fields) === FALSE) {
            $this->fields[] = $fieldName;
        }
    }
    
    public function removeField($fieldName) {
        $result = array_search($fieldName, $this->fields);
        
        if ($result !== FALSE) {
            unset($this->fields[$result]);
        }
    }
    
    /**
     * @return string
     */
    public function getMethodName() {
        return $this->methodName;
    }
    
    /**
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }
    
}
