<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses\Users;

use App\Rest\Common\BaseRest;

/**
 * @property array $profiles
 * Class ProfilesResponse
 * @author Nicolás Marulanda P.
 */
class ProfilesResponse {
    
    use BaseRest;
    
    /**
     * @var ProfileResponse[]
     */
    private $profiles;
    
    public static function getParseOfClasses(): array {
        return [
                'ProfileResponse' => ProfileResponse::class,
        ];
    }
    
}
