<?php
/**
 * InputUrlBuilder.php
 */

namespace SoftnCMS\Helpers\form\builders;

use SoftnCMS\Helpers\form\inputs\builders\InputBuilder;
use SoftnCMS\Helpers\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\Helpers\form\InputUrl;

/**
 * Class InputUrlBuilder
 * @author Nicolás Marulanda P.
 */
class InputUrlBuilder extends InputBuilder implements InputBuilderInterface {
    
    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
        $this->initValue();
    }
    
    public static function init($name, $type = 'text') {
        return new InputUrlBuilder($name, $type);
    }
    
    public function build() {
        return new InputUrl($this);
    }
}
