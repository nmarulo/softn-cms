<?php
/**
 * SortColumn.php
 */

namespace App\Rest\Requests\DataTable;

use App\Rest\Common\BaseRest;

/**
 * @property string $name
 * @property string $key
 * Class SortColumn
 * @author Nicolás Marulanda P.
 */
class SortColumn {
    
    use BaseRest;
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $key;
    
}
