<?php
/**
 * InputHtml.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\Escape;
use SoftnCMS\util\form\inputs\types\InputText;

/**
 * Class InputHtml
 * @author Nicolás Marulanda P.
 */
class InputHtml extends InputText {
    
    public function filter() {
        return Escape::htmlEncode($this->value);
    }
}
