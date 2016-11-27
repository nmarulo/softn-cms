<?php
/**
 * InputNumberBuilder.php
 */

namespace SoftnCMS\Helpers\form\inputs\builders;

use SoftnCMS\Helpers\form\inputs\types\InputNumber;

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
