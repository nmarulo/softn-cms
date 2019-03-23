<?php
/**
 * UserResponse.php
 */

namespace App\Rest\Response;

use App\Helpers\Pagination;
use App\Rest\Common\Magic;
use App\Rest\Common\ParseOfClass;
use App\Rest\Dto\Users;

/**
 * @property Users[]    $users
 * @property Pagination $pagination
 * Class UserResponse
 * @author Nicolás Marulanda P.
 */
class UserResponse implements ParseOfClass {
    
    use Magic;
    
    /**
     * @var Users[]
     */
    private $users;
    
    /**
     * @var Pagination
     */
    private $pagination;
    
    public function __construct() {
        $this->users = [];
    }
    
    public static function getParseOfClasses(): array {
        return [
                'Users'      => Users::class,
                'Pagination' => Pagination::class,
        ];
    }
}
