<?php
/**
 * InputAlphanumericBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\InputAlphanumeric;
use SoftnCMS\util\form\inputs\builders\InputTextBuilder;

/**
 * Class InputAlphanumericBuild
 * @author Nicolás Marulanda P.
 */
class InputAlphanumericBuilder extends InputTextBuilder {
    
    /**
     * @var InputAlphanumeric
     */
    private $input;
    
    /**
     * InputAlphanumericBuilder constructor.
     *
     * @param InputAlphanumeric $input
     */
    public function __construct($input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    /**
     * @param        $name
     * @param string $type
     *
     * @return InputAlphanumericBuilder
     */
    public static function init($name, $type = 'text') {
        $input = new InputAlphanumeric();
        $input->setName($name);
        $input->setType($type);
        
        return new InputAlphanumericBuilder($name, $type);
    }
    
    /**
     * @return InputAlphanumeric
     */
    public function build() {
        return $this->input;
    }
}
