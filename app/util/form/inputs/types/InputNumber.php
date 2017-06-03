<?php
/**
 * InputNumber.php
 */

namespace SoftnCMS\util\form\inputs\types;

use SoftnCMS\util\form\inputs\InputNumberInterface;

/**
 * Class InputNumber
 * @author Nicolás Marulanda P.
 */
abstract class InputNumber extends InputCommon implements InputNumberInterface {
    
    /**
     * @var bool
     */
    protected $sign;
    
    /**
     * InputNumber constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->sign = FALSE;
    }
    
    /**
     * @return boolean
     */
    public function isSign() {
        return $this->sign;
    }
    
    /**
     * @param bool $sign
     */
    public function setSign($sign) {
        $this->sign = $sign;
    }
}
