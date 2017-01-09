<?php
/**
 * InputUrlBuilder.php
 */

namespace SoftnCMS\helpers\form\builders;

use SoftnCMS\helpers\form\inputs\builders\InputBuilder;
use SoftnCMS\helpers\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\helpers\form\InputUrl;

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
