<?php
/**
 * Input.php
 */

namespace SoftnCMS\util\form\inputs;

/**
 * Class Input
 * @author Nicolás Marulanda P.
 */
abstract class Input implements InputInterface {
    
    /** @var mixed */
    protected $value;
    
    /** @var string */
    protected $name;
    
    /** @var string */
    protected $type;
    
    /** @var bool */
    protected $require;
    
    /** @var array $_POST o $_GET */
    protected $method;
    
    /**
     * Input constructor.
     */
    public function __construct() {
        $this->value   = '';
        $this->name    = '';
        $this->type    = '';
        $this->require = TRUE;
        $this->method  = [];
    }
    
    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }
    
    /**
     * @param mixed $value
     */
    public function setValue($value) {
        $this->value = $value;
    }
    
    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }
    
    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }
    
    /**
     * @return boolean
     */
    public function isRequire() {
        return $this->require;
    }
    
    /**
     * @param bool $require
     */
    public function setRequire($require) {
        $this->require = $require;
    }
    
    /**
     * @return array
     */
    public function getMethod() {
        return $this->method;
    }
    
    /**
     * @param array $method
     */
    public function setMethod($method) {
        $this->method = $method;
    }
    
    public abstract function filter();
    
}
