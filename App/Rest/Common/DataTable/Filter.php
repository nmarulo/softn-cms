<?php
/**
 * Filter.php
 */

namespace App\Rest\Common\DataTable;

use App\Facades\Utils;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;

/**
 * @property string $value
 * @property bool   $strict
 * Class Filter
 * @author Nicolás Marulanda P.
 */
class Filter implements ObjectToArray {
    
    use Magic;
    
    /**
     * @var string
     */
    private $value;
    
    /**
     * @var bool
     */
    private $strict;
    
    public function toArray() {
        return Utils::castObjectToArray($this);
    }
    
}
