<?php
/**
 * Page.php
 */

namespace App\Helpers;

use App\Rest\Common\Magic;

/**
 * @property string $styleClass
 * @property string $value
 * @property array  $attrData
 * Class Page
 * @author Nicolás Marulanda P.
 */
class Page {
    
    use Magic;
    
    /** @var string */
    private $styleClass;
    
    /** @var string */
    private $value;
    
    /** @var array */
    private $attrData;
    
    public function attrToString() {
        $attr = array_map(function($key, $value) {
            return "data-${key}='${value}'";
        }, array_keys($this->attrData), $this->attrData);
        
        return implode(' ', $attr);
    }
    
}
