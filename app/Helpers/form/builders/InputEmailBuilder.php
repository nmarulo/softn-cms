<?php
/**
 * InputEmailBuilder.php
 */

namespace SoftnCMS\helpers\form\builders;

use SoftnCMS\helpers\form\InputEmail;
use SoftnCMS\helpers\form\inputs\builders\InputBuilder;
use SoftnCMS\helpers\form\inputs\builders\InputBuilderInterface;

/**
 * Class InputEmailBuilder
 * @author Nicolás Marulanda P.
 */
class InputEmailBuilder extends InputBuilder implements InputBuilderInterface {
    
    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
        $this->initValue();
    }
    
    public static function init($name, $type = 'text') {
        return new InputEmailBuilder($name, $type);
    }
    
    public function build() {
        return new InputEmail($this);
    }
    
}
