<?php
/**
 * InputNumber.php
 */

namespace SoftnCMS\helpers\form\inputs\types;

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
