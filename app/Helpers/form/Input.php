<?php
/**
 * Input.php
 */

namespace SoftnCMS\Helpers\form;

/**
 * Class Input
 * @author Nicolás Marulanda P.
 */
abstract class Input extends InputAbstract implements InputInterface {
    
    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }
    
    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }
    
    /**
     * @return int
     */
    public function getLenMax() {
        return $this->lenMax;
    }
    
    /**
     * @return boolean
     */
    public function isRequire() {
        return $this->require;
    }
    
    /**
     * @return int
     */
    public function getLenMin() {
        return $this->lenMin;
    }
    
    /**
     * @return string
     */
    public function getArrayType() {
        return $this->arrayType;
    }
    
    /**
     * @return boolean
     */
    public function isLenStrict() {
        return $this->lenStrict;
    }
    
    /**
     * @return boolean
     */
    public function isAccents() {
        return $this->accents;
    }
    
    /**
     * @return boolean
     */
    public function isWithoutSpace() {
        return $this->withoutSpace;
    }
    
    /**
     * @return string
     */
    public function getReplaceSpace() {
        return $this->replaceSpace;
    }
    
    /**
     * @return boolean
     */
    public function isSign() {
        return $this->sign;
    }
    
    /**
     * @return array
     */
    public function getMethod() {
        return $this->method;
    }
}
