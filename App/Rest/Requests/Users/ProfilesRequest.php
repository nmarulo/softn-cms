<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\ProfileDTO;

/**
 * @property array $profiles
 * Class ProfilesRequest
 * @author Nicolás Marulanda P.
 */
class ProfilesRequest {
    
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
