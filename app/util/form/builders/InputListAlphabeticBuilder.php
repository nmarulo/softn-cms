<?php
/**
 * InputListAlphabeticBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\InputListAlphabetic;
use SoftnCMS\util\form\inputs\builders\InputSelectTextBuilder;

/**
 * Class InputListAlphabeticBuilder
 * @author Nicolás Marulanda P.
 */
class InputListAlphabeticBuilder extends InputSelectTextBuilder {
    
    /**
     * @var InputListAlphabetic
     */
    private $input;
    
    /**
     * InputListAlphabeticBuilder constructor.
     *
     * @param InputListAlphabetic $input
     */
    public function __construct($input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    public static function init($name, $type = 'text') {
        $input = new InputListAlphabetic();
        $input->setName($name);
        $input->setType($type);
        
        return new InputListAlphabeticBuilder($input);
    }
    
    public function build() {
        return $this->input;
    }
    
}
