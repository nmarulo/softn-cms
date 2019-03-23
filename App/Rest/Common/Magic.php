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
    
    public function __get($name) {
        try {
            return $this->$name;
        } catch (\Exception $exception) {
            $this->$name = NULL;
            
            return NULL;
        }
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
}
