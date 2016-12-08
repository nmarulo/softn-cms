<?php
/**
 * InputAlphanumericBuilder.php
 */

namespace SoftnCMS\helpers\form\builders;

use SoftnCMS\helpers\form\InputAlphanumeric;
use SoftnCMS\helpers\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\helpers\form\inputs\builders\InputTextBuilder;

/**
 * Class InputAlphanumericBuild
 * @author Nicolás Marulanda P.
 */
class InputAlphanumericBuilder extends InputTextBuilder implements InputBuilderInterface {
    
    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
        $this->initValue();
    }
    
    /**
     * @param        $name
     * @param string $type
     *
     * @return InputAlphanumericBuilder
     */
    public static function init($name, $type = 'text') {
        return new InputAlphanumericBuilder($name, $type);
    }
    
    /**
     * @return InputAlphanumeric
     */
    public function build() {
        return new InputAlphanumeric($this);
    }
}
