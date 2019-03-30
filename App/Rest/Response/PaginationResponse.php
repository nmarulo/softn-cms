<?php
/**
 * PaginationResponse.php
 */

namespace App\Rest\Response;

use App\Facades\Utils;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;
use App\Rest\Common\ParseOfClass;

/**
 * @property PageResponse[] $pages
 * @property PageResponse   $leftArrow
 * @property PageResponse   $rightArrow
 * @property int            $currentPageValue
 * @property int            $totalData
 * @property int            $numberRowShow
 * @property int            $maxNumberPagesShow
 * @property int            $totalNumberPages
 * @property bool           $rendered
 * @property int            $beginRow
 * Class PaginationResponse
 * @author Nicolás Marulanda P.
 */
class PaginationResponse implements ParseOfClass, ObjectToArray  {
    
    use Magic;
    
    /** @var PageResponse[] */
    private $pages;
    
    /** @var PageResponse */
    private $leftArrow;
    
    /** @var PageResponse */
    private $rightArrow;
    
    /** @var int */
    private $currentPageValue;
    
    /** @var int */
    private $totalData;
    
    /** @var int */
    private $numberRowShow;
    
    /** @var int */
    private $maxNumberPagesShow;
    
    /** @var int */
    private $totalNumberPages;
    
    /** @var bool */
    private $rendered;
    
    /** @var int */
    private $beginRow;
    
    public static function getParseOfClasses(): array {
        return [
                'PageResponse' => PageResponse::class,
        ];
    }
    
    public function toArray() {
        return Utils::castObjectToArray($this);
    }
    
}
