<?php
/**
 * InputUrlBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\inputs\builders\InputBuilder;
use SoftnCMS\util\form\InputUrl;

/**
 * Class InputUrlBuilder
 * @author Nicolás Marulanda P.
 */
class InputUrlBuilder extends InputBuilder {
    
    /**
     * @var InputUrl
     */
    private $input;
    
    /**
     * InputUrlBuilder constructor.
     *
     * @param InputUrl $input
     */
    public function __construct($input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    public static function init($name, $type = 'text') {
        $input = new InputUrl();
        $input->setName($name);
        $input->setType($type);
        
        return new InputUrlBuilder($input);
    }
    
    public function build() {
        parent::build();
        
        return $this->input;
    }
}
