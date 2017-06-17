<?php
/**
 * InputCommonBuilder.php
 */

namespace SoftnCMS\util\form\inputs\builders;

use SoftnCMS\util\form\inputs\InputCommonInterface;
use SoftnCMS\util\form\inputs\types\InputCommon;

/**
 * Class InputCommonBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputCommonBuilder extends InputBuilder implements InputCommonInterface {
    
    /**
     * @var InputCommon
     */
    private $input;
    
    /**
     * InputCommonBuilder constructor.
     *
     * @param InputCommon $input
     */
    public function __construct(InputCommon $input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    /**
     * @param int $lenMax
     *
     * @return $this
     */
    public function setLenMax($lenMax) {
        $this->input->setLenMax($lenMax);
        
        return $this;
    }
    
    /**
     * @param int $lenMin
     *
     * @return $this
     */
    public function setLenMin($lenMin) {
        $this->input->setLenMin($lenMin);
        
        return $this;
    }
    
    /**
     * @param boolean $lenStrict
     *
     * @return $this
     */
    public function setLenStrict($lenStrict) {
        $this->input->setLenStrict($lenStrict);
        
        return $this;
    }
    
}
