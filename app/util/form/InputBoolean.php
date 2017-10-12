<?php
/**
 * InputBoolean.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\form\inputs\Input;
use SoftnCMS\util\Validate;

/**
 * Class InputBoolean
 * @author Nicolás Marulanda P.
 */
class InputBoolean extends Input {
    
    /**
     * InputBoolean constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function filter() {
        return Validate::boolean($this->value);
    }
    
}
