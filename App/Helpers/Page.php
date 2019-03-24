<?php
/**
 * Page.php
 */

namespace App\Helpers;

use App\Facades\Utils;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;

/**
 * @property string $styleClass
 * @property string $value
 * @property array  $attrData
 * Class Page
 * @author Nicolás Marulanda P.
 */
class Page implements ObjectToArray {
    
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
    
    public function toArray() {
        return Utils::castObjectToArray($this);
    }
    
}
