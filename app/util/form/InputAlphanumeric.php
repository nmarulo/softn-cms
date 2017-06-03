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
    
    public function filter() {
        $output = Sanitize::alphanumeric($this->value, $this->accents, $this->withoutSpace, $this->replaceSpace);
        
        if (!Validate::alphanumeric($output, $this->lenMax, $this->accents, $this->lenStrict)) {
            $output = '';
        }
        
        return $output;
    }
    
}
