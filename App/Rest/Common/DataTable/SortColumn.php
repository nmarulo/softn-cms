<?php
/**
 * SortColumn.php
 */

namespace App\Rest\Common\DataTable;

use App\Facades\UtilsFacade;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;

/**
 * @property string $name
 * @property string $key
 * Class SortColumn
 * @author Nicolás Marulanda P.
 */
class SortColumn implements ObjectToArray {
    
    use Magic;
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $key;
    
    public function toArray(): array {
        return UtilsFacade::castObjectToArray($this);
    }
    
}
