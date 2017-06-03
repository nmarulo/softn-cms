<?php
/**
 * InputListAlphabetic.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\form\inputs\types\InputSelectText;

/**
 * Class InputListAlphabetic
 * @author Nicolás Marulanda P.
 */
class InputListAlphabetic extends InputSelectText {
    
    public function filter() {
        //TODO: pendiente
        return $this->value;
    }
}
