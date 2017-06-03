<?php
/**
 * InputListInteger.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\form\inputs\types\InputSelectNumber;
use SoftnCMS\util\Sanitize;

/**
 * Class InputListInteger
 * @author Nicolás Marulanda P.
 */
class InputListInteger extends InputSelectNumber {
    
    /**
     * InputListInteger constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function filter() {
        return Sanitize::arrayList($this->value, $this->listType, $this->sign);
    }
}
