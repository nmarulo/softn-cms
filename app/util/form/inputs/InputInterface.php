<?php
/**
 * InputInterface.php
 */

namespace SoftnCMS\util\form\inputs;

/**
 * Class Input
 * @author Nicolás Marulanda P.
 */
interface InputInterface {
    
    /**
     * @param string $value
     */
    public function setValue($value);
    
    /**
     * @param string $name
     */
    public function setName($name);
    
    /**
     * @param string $type
     */
    public function setType($type);
    
    /**
     * @param bool $require
     */
    public function setRequire($require);
    
    /**
     * @param array $method
     */
    public function setMethod($method);
    
}
