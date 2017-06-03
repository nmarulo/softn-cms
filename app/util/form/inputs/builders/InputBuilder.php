<?php
/**
 * InputBuilder.php
 */

namespace SoftnCMS\util\form\inputs\builders;

use SoftnCMS\util\form\inputs\Input;
use SoftnCMS\util\form\inputs\InputBuilderInterface;
use SoftnCMS\util\form\inputs\InputInterface;

/**
 * Class InputBuild
 * @author Nicolás Marulanda P.
 */
abstract class InputBuilder implements InputInterface, InputBuilderInterface {
    
    /** @var Input */
    private $input;
    
    /**
     * InputBuilder constructor.
     *
     * @param Input $input
     */
    public function __construct(Input $input) {
        $this->input = $input;
    }
    
    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value) {
        $this->input->setValue($value);
        
        return $this;
    }
    
    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name) {
        $this->input->setName($name);
        
        return $this;
    }
    
    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type) {
        $this->input->setType($type);
        
        return $this;
    }
    
    /**
     * @param boolean $require
     *
     * @return $this
     */
    public function setRequire($require) {
        $this->input->setRequire($require);
        
        return $this;
    }
    
    /**
     * @param $method
     *
     * @return $this
     */
    public function setMethod($method) {
        $this->input->setMethod($method);
        
        return $this;
    }
    
    public abstract function build();
    
}
