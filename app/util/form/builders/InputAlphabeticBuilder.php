<?php
/**
 * InputAlphabeticBuilder.php
 */

namespace SoftnCMS\util\form\builders;

use SoftnCMS\util\form\InputAlphabetic;
use SoftnCMS\util\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\util\form\inputs\builders\InputTextBuilder;

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
