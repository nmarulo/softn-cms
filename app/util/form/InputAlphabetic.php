<?php
/**
 * InputAlphabetic.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\form\inputs\types\InputText;
use SoftnCMS\util\Sanitize;
use SoftnCMS\util\Validate;

/**
 * Class InputAlphabetic
 * @author Nicolás Marulanda P.
 */
class InputAlphabetic extends InputText {
    
    public function filter() {
        $output = Sanitize::alphabetic($this->value, $this->accents, $this->withoutSpace, $this->replaceSpace);
        
        if (!Validate::alphabetic($output, $this->lenMax, $this->accents, $this->lenStrict)) {
            $output = '';
        }
        
        return $output;
    }
    
}
