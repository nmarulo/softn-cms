<?php
/**
 * Magic.php
 */

namespace App\Rest\Common;

/**
 * Class Magic
 * @author Nicolás Marulanda P.
 */
trait Magic {
    
    /**
     * @var array
     */
    private $properties;
    
    public function __get($name) {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }
        
        return NULL;
    }
    
    public function __set($name, $value) {
        $this->properties[$name] = $value;
    }
    
    public function getProperties(): array {
        return $this->properties;
    }
}
