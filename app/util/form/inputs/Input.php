<?php
/**
 * Input.php
 */

namespace SoftnCMS\util\form\inputs;

/**
 * Class Input
 * @author Nicolás Marulanda P.
 */
trait Input {
    
    /** @var string */
    protected $value;
    
    /** @var string */
    protected $name;
    
    /** @var string */
    protected $type;
    
    protected $require = TRUE;
    
    /** @var array $_POST o $_GET */
    protected $method = [];
    
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
     * @return boolean
     */
    public function isRequire() {
        return $this->require;
    }
    
    /**
     * @return array
     */
    public function getMethod() {
        return $this->method;
    }
    
}
