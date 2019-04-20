<?php
/**
 * Filter.php
 */

namespace App\Rest\Requests\DataTable;

use App\Rest\Common\BaseRest;

/**
 * @property string $value
 * @property bool   $strict
 * Class Filter
 * @author Nicolás Marulanda P.
 */
class Filter {
    
    use BaseRest;
    
    /**
     * @var string
     */
    private $value;
    
    /**
     * @var bool
     */
    private $strict;
    
}
