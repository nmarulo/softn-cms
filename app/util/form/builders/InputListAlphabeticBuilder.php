<?php
/**
 * InputListAlphabeticBuilder.php
 */

namespace SoftnCMS\helpers\form\builders;

use SoftnCMS\helpers\form\InputListAlphabetic;
use SoftnCMS\helpers\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\helpers\form\inputs\builders\InputSelectTextBuilder;

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
