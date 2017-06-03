<?php
/**
 * InputIntegerBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\InputInteger;
use SoftnCMS\util\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\util\form\inputs\builders\InputNumberBuilder;

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
