<?php
/**
 * InputNumberBuilder.php
 */

namespace SoftnCMS\util\form\inputs\builders;

use SoftnCMS\util\form\inputs\InputNumberInterface;
use SoftnCMS\util\form\inputs\types\InputNumber;

/**
 * Class InputNumberBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputNumberBuilder extends InputCommonBuilder implements InputNumberInterface {
    
    /**
     * @var InputNumber
     */
    private $input;
    
    /**
     * InputNumberBuilder constructor.
     *
     * @param InputNumber $input
     */
    public function __construct(InputNumber $input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    /**
     * @param boolean $sign
     *
     * @return $this
     */
    public function setSign($sign) {
        $this->input->setSign($sign);
        
        return $this;
    }
    
}
