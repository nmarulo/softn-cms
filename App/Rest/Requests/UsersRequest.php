<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests;

use App\Rest\Common\BaseRest;
use App\Rest\Requests\DataTable\DataTable;
use App\Rest\Searches\UserSearch;

/**
 * @property array     $users
 * @property DataTable $dataTable
 * @property bool      $strict
 * Class UsersRequest
 * @author Nicolás Marulanda P.
 */
class UsersRequest {
    
    use BaseRest;
    
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
    
}
