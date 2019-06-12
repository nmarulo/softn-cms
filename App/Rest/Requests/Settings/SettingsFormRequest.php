<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests\Settings;

use App\Rest\Common\BaseRest;

/**
 * Class SettingsFormRequest
 * @author Nicolás Marulanda P.
 */
class SettingsFormRequest {
    
    use BaseRest;
    
    /** @var string */
    private $title;
    
    /** @var string */
    private $description;
    
    /** @var string */
    private $emailAdmin;
    
    /** @var string */
    private $siteUrl;
}
