<?php
/**
 * InputListAlphabeticBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\InputListAlphabetic;
use SoftnCMS\util\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\util\form\inputs\builders\InputSelectTextBuilder;

/**
 * Class InputListAlphabeticBuilder
 * @author Nicolás Marulanda P.
 */
class InputListAlphabeticBuilder extends InputSelectTextBuilder implements InputBuilderInterface {
    
    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
        $this->initValue();
    }
    
    public static function init($name, $type = 'text') {
        return new InputListAlphabeticBuilder($name, $type);
    }
    
    public function build() {
        return new InputListAlphabetic($this);
    }
    
}
