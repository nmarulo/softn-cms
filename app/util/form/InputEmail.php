<?php
/**
 * InputEmail.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\form\inputs\Input;
use SoftnCMS\util\Sanitize;
use SoftnCMS\util\Validate;

/**
 * Class InputEmail
 * @author Nicolás Marulanda P.
 */
class InputEmail extends Input {
    
    /**
     * InputEmail constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function filter() {
        $output = Sanitize::email($this->value);
        
        if (!Validate::email($output)) {
            $output = '';
        }
        
        return $output;
    }
}
