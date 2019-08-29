<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Responses\PaginationResponse;

/**
 * @property array $profiles
 * @property PaginationResponse $pagination
 * Class ProfilesResponse
 * @author Nicolás Marulanda P.
 */
class ProfilesResponse {
    
    use BaseRest;
    
    /**
     * @var ProfileResponse[]
     */
    private $profiles;
    
    /**
     * @var PaginationResponse
     */
    private $pagination;
    
    public static function getParseOfClasses(): array {
        return [
                'ProfileResponse' => ProfileResponse::class,
                'PaginationResponse' => PaginationResponse::class,
        ];
    }
    
}
