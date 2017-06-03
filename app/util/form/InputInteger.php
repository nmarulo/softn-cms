<?php
/**
 * InputInteger.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\form\inputs\types\InputNumber;
use SoftnCMS\util\Sanitize;
use SoftnCMS\util\Validate;

/**
 * Class InputInteger
 * @author Nicolás Marulanda P.
 */
class InputInteger extends InputNumber {
    
    /**
     * InputInteger constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function filter() {
        $output = Sanitize::integer($this->value, $this->sign);
        
        if (!Validate::integer($output, $this->lenMax, $this->lenStrict)) {
            $output = '';
        }
        
        return $output;
    }
}
