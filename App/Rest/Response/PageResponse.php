<?php
/**
 * PageResponse.php
 */

namespace App\Rest\Response;

use App\Facades\Utils;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;

/**
 * @property string $styleClass
 * @property string $value
 * @property string $attrData
 * @property array  $attr
 * Class PageResponse
 * @author Nicolás Marulanda P.
 */
class PageResponse implements ObjectToArray {
    
    use Magic;
    
    /** @var string */
    private $styleClass;
    
    /** @var string */
    private $value;
    
    /** @var string */
    private $attrData;
    
    /** @var array */
    private $attr;
    
    public function toArray(): array {
        return Utils::castObjectToArray($this);
    }
}
