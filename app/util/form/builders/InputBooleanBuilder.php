<?php
/**
 * InputBooleanBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\InputBoolean;
use SoftnCMS\util\form\inputs\builders\InputBuilder;

/**
 * Class InputBooleanBuilder
 * @author Nicolás Marulanda P.
 */
class InputBooleanBuilder extends InputBuilder {
    
    /**
     * @var InputBoolean
     */
    private $input;
    
    /**
     * InputBooleanBuilder constructor.
     *
     * @param InputBoolean $input
     */
    public function __construct($input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    /**
     * @param        $name
     * @param string $type
     *
     * @return InputBooleanBuilder
     */
    public static function init($name, $type = 'text') {
        $input = new InputBoolean();
        $input->setName($name);
        $input->setType($type);
        
        return new InputBooleanBuilder($input);
    }
    
    /**
     * @return InputBoolean
     */
    public function build() {
        return $this->input;
    }
    
}
