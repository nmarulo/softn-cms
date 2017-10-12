<?php
/**
 * InputAlphanumeric.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\form\inputs\types\InputText;
use SoftnCMS\util\Sanitize;
use SoftnCMS\util\Validate;

/**
 * Interface InputAlphanumeric
 * @author Nicolás Marulanda P.
 */
class InputAlphanumeric extends InputText {
    
    /**
     * InputAlphanumeric constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function filter() {
        $output = Sanitize::alphanumeric($this->value, $this->accents, $this->withoutSpace, $this->replaceSpace, $this->specialChar);
        
        if (!Validate::alphanumeric($output, $this->lenMax, $this->accents, $this->lenStrict, $this->specialChar)) {
            $output = '';
        }
        
        return $output;
    }
    
}
