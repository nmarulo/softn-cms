<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests\Settings;

use App\Rest\Common\BaseRest;

/**
 * @property int    $gravatarSize
 * @property string $gravatarImage
 * @property string $gravatarRating
 * @property bool   $gravatarForceDefault
 * Class GrAvatarSettingsFormRequest
 * @author Nicolás Marulanda P.
 */
class GrAvatarSettingsFormRequest {
    
    use BaseRest;
    
    /** @var int */
    private $gravatarSize;
    
    /** @var string */
    private $gravatarImage;
    
    /** @var string */
    private $gravatarRating;
    
    /** @var bool */
    private $gravatarForceDefault;
    
}
