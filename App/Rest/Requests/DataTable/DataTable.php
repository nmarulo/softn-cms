<?php
/**
 * DataTable.php
 */

namespace App\Rest\Requests\DataTable;

use App\Rest\Common\BaseRest;

/**
 * @property array  $sortColumn
 * @property Filter $filter
 * @property int    $page
 * @property int    $numberRowsShow
 * @property bool   $paginator
 * Class DataTable
 * @author Nicolás Marulanda P.
 */
class DataTable {
    
    use BaseRest;
    
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
    
    /** @var bool */
    private $paginator;
    
    /** @var int */
    private $numberRowsShow;
    
    public static function getParseOfClasses(): array {
        return [
                'SortColumn' => SortColumn::class,
                'Filter'     => Filter::class,
        ];
    }
    
}
