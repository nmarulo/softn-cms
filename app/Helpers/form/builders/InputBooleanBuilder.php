<?php
/**
 * InputBooleanBuilder.php
 */

namespace SoftnCMS\Helpers\form\builders;

use SoftnCMS\Helpers\form\InputBoolean;
use SoftnCMS\Helpers\form\inputs\builders\InputBuilder;
use SoftnCMS\Helpers\form\inputs\builders\InputBuilderInterface;

/**
 * Class InputBooleanBuilder
 * @author Nicolás Marulanda P.
 */
class InputBooleanBuilder extends InputBuilder implements InputBuilderInterface {
    
    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
        $this->initValue();
    }
    
    /**
     * @param        $name
     * @param string $type
     *
     * @return InputBooleanBuilder
     */
    public static function init($name, $type = 'text') {
        return new InputBooleanBuilder($name, $type);
    }
    
    /**
     * @return InputBoolean
     */
    public function build() {
        return new InputBoolean($this);
    }
    
}
