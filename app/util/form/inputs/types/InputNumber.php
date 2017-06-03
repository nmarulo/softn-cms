<?php
/**
 * InputNumber.php
 */

namespace SoftnCMS\util\form\inputs\types;

/**
 * Class InputNumber
 * @author Nicolás Marulanda P.
 */
trait InputNumber {
    
    use InputCommon;
    
    protected $sign = FALSE;
    
    /**
     * @return boolean
     */
    public function isSign() {
        return $this->sign;
    }
}
