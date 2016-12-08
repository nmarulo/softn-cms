<?php
/**
 * InputCommonBuilder.php
 */

namespace SoftnCMS\helpers\form\inputs\builders;

use SoftnCMS\helpers\form\inputs\types\InputCommon;

/**
 * Class InputCommonBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputCommonBuilder extends InputBuilder {
    
    use InputCommon;
    
    /**
     * @param int $lenMax
     *
     * @return $this
     */
    public function setLenMax($lenMax) {
        $this->lenMax = $lenMax;
        
        return $this;
    }
    
    /**
     * @param int $lenMin
     *
     * @return $this
     */
    public function setLenMin($lenMin) {
        $this->lenMin = $lenMin;
        
        return $this;
    }
    
    /**
     * @param boolean $lenStrict
     *
     * @return $this
     */
    public function setLenStrict($lenStrict) {
        $this->lenStrict = $lenStrict;
        
        return $this;
    }
    
}
