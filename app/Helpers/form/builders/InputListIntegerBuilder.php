<?php
/**
 * InputListIntegerBuilder.php
 */

namespace SoftnCMS\Helpers\form\builders;

use SoftnCMS\Helpers\form\InputListInteger;
use SoftnCMS\Helpers\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\Helpers\form\inputs\builders\InputSelectNumberBuilder;

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
