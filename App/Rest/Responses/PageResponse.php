<?php
/**
 * PageResponse.php
 */

namespace App\Rest\Responses;

use App\Rest\Common\BaseRest;

/**
 * @property string $styleClass
 * @property string $value
 * @property string $attrData
 * @property array  $attr
 * Class PageResponse
 * @author Nicolás Marulanda P.
 */
class PageResponse {
    
    use BaseRest;
    
    /** @var string */
    private $styleClass;
    
    /** @var string */
    private $value;
    
    /** @var string */
    private $attrData;
    
    /** @var array */
    private $attr;
    
}
