<?php
/**
 * ToArrayClass.php
 */

namespace App\Rest\Common;

use App\Facades\UtilsFacade;

/**
 * Interface ObjectToArray
 * @author Nicolás Marulanda P.
 */
trait ObjectToArray {
    
    public function toArray(): array {
        return UtilsFacade::castObjectToArray($this);
    }
}
