<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests;

use App\Facades\UtilsFacade;
use App\Rest\Common\DataTable\DataTable;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;
use App\Rest\Common\ParseOf;
use App\Rest\Searches\UserSearch;

/**
 * @property array     $users
 * @property DataTable $dataTable
 * @property bool      $strict
 * Class UsersRequest
 * @author Nicolás Marulanda P.
 */
class UsersRequest implements ObjectToArray, ParseOf {
    
    use Magic;
    
    /**
     * @var UserSearch[]
     */
    private $users;
    
    /**
     * @var bool
     */
    private $strict;
    
    /**
     * @var DataTable
     */
    private $dataTable;
    
    public static function getParseOfClasses(): array {
        return [
                'DataTable'  => DataTable::class,
                'UserSearch' => UserSearch::class,
        ];
    }
    
    public static function parseOf(array $values): UsersRequest {
        return UtilsFacade::parseOf($values, self::class);
    }
    
    public function toArray(): array {
        return UtilsFacade::castObjectToArray($this);
    }
}
