<?php
/**
 * softn-cms
 */

namespace App\Helpers\Views;

use App\Rest\Responses\Users\PermissionResponse;

/**
 * @property bool $selected
 * Class ProfilePermissionView
 * @author Nicolás Marulanda P.
 */
class PermissionView extends PermissionResponse {
    
    /** @var bool */
    private $selected;
}
