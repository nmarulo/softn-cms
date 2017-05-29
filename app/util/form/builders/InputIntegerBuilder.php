<?php
/**
 * InputIntegerBuilder.php
 */

namespace SoftnCMS\helpers\form\builders;

use SoftnCMS\helpers\form\InputInteger;
use SoftnCMS\helpers\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\helpers\form\inputs\builders\InputNumberBuilder;

/**
 * Class InputIntegerBuilder
 * @author Nicolás Marulanda P.
 */
class InputIntegerBuilder extends InputNumberBuilder implements InputBuilderInterface {
    
    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
        $this->initValue();
    }
    
    public static function init($name, $type = 'text') {
        return new InputIntegerBuilder($name, $type);
    }
    
    public function build() {
        return new InputInteger($this);
    }
}
