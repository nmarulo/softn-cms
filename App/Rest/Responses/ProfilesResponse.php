<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\ProfileDTO;

/**
 * @property array $profiles
 * Class ProfilesResponse
 * @author Nicolás Marulanda P.
 */
class ProfilesResponse {
    
    use BaseRest;
    
    /**
     * @var ProfileDTO[]
     */
    private $profiles;
    
    public static function getParseOfClasses(): array {
        return [
                'ProfileDTO' => ProfileDTO::class,
        ];
    }
    
}
