<?php
/**
 * DataTable.php
 */

namespace App\Rest\Common\DataTable;

use App\Facades\Utils;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;
use App\Rest\Common\ParseOfClass;

/**
 * @property array  $sortColumn
 * @property Filter $filter
 * @property int    $page
 * Class DataTable
 * @author Nicolás Marulanda P.
 */
class DataTable implements ObjectToArray, ParseOfClass {
    
    use Magic;
    
    /**
     * @var SortColumn[]
     */
    private $sortColumn;
    
    /**
     * @var Filter
     */
    private $filter;
    
    /**
     * @var int
     */
    private $page;
    
    public static function getParseOfClasses(): array {
        return [
                'SortColumn' => SortColumn::class,
                'Filter'     => Filter::class,
        ];
    }
    
    public function toArray() {
        return Utils::castObjectToArray($this);
    }
    
}
