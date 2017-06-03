<?php
/**
 * InputCommon.php
 */

namespace SoftnCMS\util\form\inputs\types;

use SoftnCMS\util\form\inputs\Input;
use SoftnCMS\util\form\inputs\InputCommonInterface;

/**
 * Class InputCommon
 * @author Nicolás Marulanda P.
 */
abstract class InputCommon extends Input implements InputCommonInterface {
    
    /**
     * @var int
     */
    protected $lenMax = 0;
    
    /**
     * @var int
     */
    protected $lenMin = 1;
    
    /**
     * @var bool Longitud estricta
     */
    protected $lenStrict = FALSE;
    
    /**
     * InputCommon constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->lenMax    = 0;
        $this->lenMin    = 1;
        $this->lenStrict = FALSE;
    }
    
    /**
     * @return int
     */
    public function getLenMax() {
        return $this->lenMax;
    }
    
    /**
     * @param int $lenMax
     */
    public function setLenMax($lenMax) {
        $this->lenMax = $lenMax;
    }
    
    /**
     * @return int
     */
    public function getLenMin() {
        return $this->lenMin;
    }
    
    /**
     * @param int $lenMin
     */
    public function setLenMin($lenMin) {
        $this->lenMin = $lenMin;
    }
    
    /**
     * @return boolean
     */
    public function isLenStrict() {
        return $this->lenStrict;
    }
    
    /**
     * @param bool $lenStrict
     */
    public function setLenStrict($lenStrict) {
        $this->lenStrict = $lenStrict;
    }
    
}
