<?php
/**
 * InputIntegerBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\InputInteger;
use SoftnCMS\util\form\inputs\builders\InputNumberBuilder;

/**
 * Class InputIntegerBuilder
 * @author Nicolás Marulanda P.
 */
class InputIntegerBuilder extends InputNumberBuilder {
    
    /**
     * @var InputInteger
     */
    private $input;
    
    /**
     * InputIntegerBuilder constructor.
     *
     * @param InputInteger $input
     */
    public function __construct($input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    public static function init($name, $type = 'text') {
        $input = new InputInteger();
        $input->setName($name);
        $input->setType($type);
        
        return new InputIntegerBuilder($input);
    }
    
    public function build() {
        parent::build();
        
        return $this->input;
    }
}
