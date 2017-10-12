<?php
/**
 * InputHtmlBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\InputHtml;
use SoftnCMS\util\form\inputs\builders\InputTextBuilder;

/**
 * Class InputHtmlBuilder
 * @author Nicolás Marulanda P.
 */
class InputHtmlBuilder extends InputTextBuilder {
    
    /**
     * @var InputHtml
     */
    private $input;
    
    /**
     * InputHtmlBuilder constructor.
     *
     * @param InputHtml $input
     */
    public function __construct($input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    public static function init($name, $type = 'text') {
        $input = new InputHtml();
        $input->setName($name);
        $input->setType($type);
        
        return new InputHtmlBuilder($input);
    }
    
    public function build() {
        parent::build();
        
        return $this->input;
    }
}
