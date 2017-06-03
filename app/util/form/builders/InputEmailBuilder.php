<?php
/**
 * InputEmailBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\InputEmail;
use SoftnCMS\util\form\inputs\builders\InputBuilder;

/**
 * Class InputEmailBuilder
 * @author Nicolás Marulanda P.
 */
class InputEmailBuilder extends InputBuilder {
    
    /**
     * @var InputEmail
     */
    private $input;
    
    /**
     * InputEmailBuilder constructor.
     *
     * @param InputEmail $input
     */
    public function __construct($input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    public static function init($name, $type = 'text') {
        $input = new InputEmail();
        $input->setName($name);
        $input->setType($type);
        
        return new InputEmailBuilder($input);
    }
    
    public function build() {
        parent::build();
        
        return $this->input;
    }
    
}
