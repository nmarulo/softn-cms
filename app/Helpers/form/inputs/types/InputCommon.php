<?php
/**
 * InputCommon.php
 */

namespace SoftnCMS\helpers\form\inputs\types;

use SoftnCMS\helpers\form\inputs\Input;

/**
 * Class InputCommon
 * @author Nicolás Marulanda P.
 */
trait InputCommon {
    
    use Input;
    
    protected $lenMax    = 0;
    
    protected $lenMin    = 1;
    
    protected $lenStrict = FALSE;
    
    /**
     * @return int
     */
    public function getLenMax() {
        return $this->lenMax;
    }
    
    /**
     * @return int
     */
    public function getLenMin() {
        return $this->lenMin;
    }
    
    /**
     * @return boolean
     */
    public function isLenStrict() {
        return $this->lenStrict;
    }
    
}
