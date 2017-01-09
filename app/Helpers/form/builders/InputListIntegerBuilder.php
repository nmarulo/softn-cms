<?php
/**
 * InputListIntegerBuilder.php
 */

namespace SoftnCMS\helpers\form\builders;

use SoftnCMS\helpers\form\InputListInteger;
use SoftnCMS\helpers\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\helpers\form\inputs\builders\InputSelectNumberBuilder;

/**
 * Class InputListIntegerBuild
 * @author Nicolás Marulanda P.
 */
class InputListIntegerBuilder extends InputSelectNumberBuilder implements InputBuilderInterface {
    
    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
        $this->initValue();
    }
    
    public static function init($name, $type = 'text') {
        return new InputListIntegerBuilder($name, $type);
    }
    
    public function build() {
        return new InputListInteger($this);
    }
    
}
