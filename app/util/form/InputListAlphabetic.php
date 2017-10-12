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
    
    /**
     * InputListAlphabetic constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function filter() {
        //TODO: pendiente
        return $this->value;
    }
}
