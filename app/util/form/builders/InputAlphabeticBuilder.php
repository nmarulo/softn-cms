<?php
/**
 * InputAlphabeticBuilder.php
 */

namespace SoftnCMS\helpers\form\builders;

use SoftnCMS\helpers\form\InputAlphabetic;
use SoftnCMS\helpers\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\helpers\form\inputs\builders\InputTextBuilder;

/**
 * Class InputAlphabeticBuilder
 * @author Nicolás Marulanda P.
 */
class InputAlphabeticBuilder extends InputTextBuilder implements InputBuilderInterface {
    
    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
        $this->initValue();
    }
    
    /**
     * @param        $name
     * @param string $type
     *
     * @return InputAlphabeticBuilder
     */
    public static function init($name, $type = 'text') {
        return new InputAlphabeticBuilder($name, $type);
    }
    
    /**
     * @return InputAlphabetic
     */
    public function build() {
        return new InputAlphabetic($this);
    }
    
}
