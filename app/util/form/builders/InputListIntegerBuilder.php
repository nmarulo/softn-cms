<?php
/**
 * InputListIntegerBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\InputListInteger;
use SoftnCMS\util\form\inputs\builders\InputSelectNumberBuilder;

/**
 * Class InputListIntegerBuild
 * @author Nicolás Marulanda P.
 */
class InputListIntegerBuilder extends InputSelectNumberBuilder {
    
    /**
     * @var InputListInteger
     */
    private $input;
    
    /**
     * InputListIntegerBuilder constructor.
     *
     * @param InputListInteger $input
     */
    public function __construct($input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    public static function init($name, $type = 'text') {
        $input = new InputListInteger();
        $input->setName($name);
        $input->setType($type);
        
        return new InputListIntegerBuilder($input);
    }
    
    public function build() {
        parent::build();
        
        return $this->input;
    }
    
}
