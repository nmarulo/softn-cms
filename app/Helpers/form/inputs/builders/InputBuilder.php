<?php
/**
 * InputBuilder.php
 */

namespace SoftnCMS\helpers\form\inputs\builders;

use SoftnCMS\helpers\ArrayHelp;
use SoftnCMS\helpers\form\inputs\Input;

/**
 * Class InputBuild
 * @author Nicolás Marulanda P.
 */
abstract class InputBuilder {
    
    use Input;
    
    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value) {
        $this->value = $value;
        
        return $this;
    }
    
    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type) {
        $this->type = $type;
        
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
     * @param $method
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
