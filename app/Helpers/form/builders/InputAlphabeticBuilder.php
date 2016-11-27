<?php
/**
 * InputAlphabeticBuilder.php
 */

namespace SoftnCMS\Helpers\form\builders;

use SoftnCMS\Helpers\form\InputAlphabetic;
use SoftnCMS\Helpers\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\Helpers\form\inputs\builders\InputTextBuilder;

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
