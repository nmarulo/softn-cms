<?php
/**
 * InputHtmlBuilder.php
 */

namespace SoftnCMS\helpers\form\builders;

use SoftnCMS\helpers\form\InputHtml;
use SoftnCMS\helpers\form\inputs\builders\InputBuilderInterface;
use SoftnCMS\helpers\form\inputs\builders\InputTextBuilder;

/**
 * Class InputHtmlBuilder
 * @author Nicolás Marulanda P.
 */
class InputHtmlBuilder extends InputTextBuilder implements InputBuilderInterface {
    
    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
        $this->initValue();
    }
    
    public static function init($name, $type = 'text') {
        return new InputHtmlBuilder($name, $type);
    }
    
    public function build() {
        return new InputHtml($this);
    }
}
