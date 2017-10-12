<?php
/**
 * InputUrl.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\form\inputs\Input;
use SoftnCMS\util\Sanitize;
use SoftnCMS\util\Validate;

/**
 * Class InputUrl
 * @author Nicolás Marulanda P.
 */
class InputUrl extends Input {
    
    /**
     * InputUrl constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function filter() {
        $output = Sanitize::url($this->value);
        
        if (!Validate::url($output)) {
            $output = '';
        }
        
        return $output;
    }
}
