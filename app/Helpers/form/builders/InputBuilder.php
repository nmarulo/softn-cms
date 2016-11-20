<?php
/**
 * InputBuilder.php
 */

namespace SoftnCMS\Helpers\form\builders;

use SoftnCMS\Helpers\ArrayHelp;
use SoftnCMS\Helpers\form\InputAbstract;

/**
 * Class InputBuild
 * @author Nicolás Marulanda P.
 */
abstract class InputBuilder extends InputAbstract {
    
    /**
     * @param int $lenMax
     *
     * @return $this
     */
    public function setLenMax($lenMax) {
        $this->lenMax = $lenMax;
        
        return $this;
    }
    
    /**
     * @param boolean $require
     *
     * @return $this
     */
    public function setRequire($require) {
        $this->require = $require;
        
        return $this;
    }
    
    /**
     * @param int $lenMin
     *
     * @return $this
     */
    public function setLenMin($lenMin) {
        $this->lenMin = $lenMin;
        
        return $this;
    }
    
    /**
     * @param string $arrayType
     *
     * @return $this
     */
    public function setArrayType($arrayType) {
        $this->arrayType = $arrayType;
        
        return $this;
    }
    
    /**
     * @param boolean $lenStrict
     *
     * @return $this
     */
    public function setLenStrict($lenStrict) {
        $this->lenStrict = $lenStrict;
        
        return $this;
    }
    
    /**
     * @param boolean $accents
     *
     * @return $this
     */
    public function setAccents($accents) {
        $this->accents = $accents;
        
        return $this;
    }
    
    /**
     * @param boolean $withoutSpace
     *
     * @return $this
     */
    public function setWithoutSpace($withoutSpace) {
        $this->withoutSpace = $withoutSpace;
        
        return $this;
    }
    
    /**
     * @param string $replaceSpace
     *
     * @return $this
     */
    public function setReplaceSpace($replaceSpace) {
        $this->replaceSpace = $replaceSpace;
        
        return $this;
    }
    
    /**
     * @param boolean $sign
     *
     * @return $this
     */
    public function setSign($sign) {
        $this->sign = $sign;
        
        return $this;
    }
    
    /**
     * @param array $method
     *
     * @return $this
     */
    public function setMethod($method) {
        $this->method = $method;
        
        return $this;
    }
    
    public function initValue() {
        if (empty($this->method)) {
            $this->method = $_POST;
        }
        
        $this->value = ArrayHelp::get($this->method, $this->name);
    }
    
    public abstract function build();
    
}
