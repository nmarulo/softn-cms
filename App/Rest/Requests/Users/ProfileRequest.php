<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\ProfileDTO;

/**
 * @property int $permissionsId
 * Class ProfileRequest
 * @author Nicolás Marulanda P.
 */
class ProfileRequest extends ProfileDTO {
    
    use BaseRest;
    
    /** @var int */
    private $permissionsId;
    
}
