<?php
/**
 * InputNumberBuilder.php
 */

namespace SoftnCMS\helpers\form\inputs\builders;

use SoftnCMS\helpers\form\inputs\types\InputNumber;

/**
 * Class InputNumberBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputNumberBuilder extends InputCommonBuilder {
    
    use InputNumber;
    
    /**
     * @param boolean $sign
     *
     * @return $this
     */
    public function setSign($sign) {
        $this->sign = $sign;
        
        return $this;
    }
    
}
