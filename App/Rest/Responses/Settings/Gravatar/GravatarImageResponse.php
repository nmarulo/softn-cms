<?php
namespace App\Rest\Responses\Settings\Gravatar;

use App\Rest\Common\BaseRest;

/**
 * @property string $value
 * @property string $url
 * Class GravatarImageResponse
 * @author Nicolás Marulanda P.
 */
class GravatarImageResponse {
    
    use BaseRest;
    
    /** @var string */
    private $value;
    
    /** @var string */
    private $url;
    
}
