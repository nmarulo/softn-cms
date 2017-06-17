<?php
/**
 * InputAlphabeticBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\InputAlphabetic;
use SoftnCMS\util\form\inputs\builders\InputTextBuilder;

/**
 * Class InputAlphabeticBuilder
 * @author Nicolás Marulanda P.
 */
class InputAlphabeticBuilder extends InputTextBuilder {
    
    /**
     * @var InputAlphabetic
     */
    private $input;
    
    /**
     * InputAlphabeticBuilder constructor.
     *
     * @param InputAlphabetic $input
     */
    public function __construct($input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    /**
     * @param        $name
     * @param string $type
     *
     * @return InputAlphabeticBuilder
     */
    public static function init($name, $type = 'text') {
        $input = new InputAlphabetic();
        $input->setName($name);
        $input->setType($type);
        
        return new InputAlphabeticBuilder($input);
    }
    
    /**
     * @return InputAlphabetic
     */
    public function build() {
        parent::build();
        
        return $this->input;
    }
    
}
