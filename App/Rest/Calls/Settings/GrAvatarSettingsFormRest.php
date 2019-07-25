<?php
/**
 * softn-cms
 */

namespace App\Rest\Calls\Settings;

use App\Facades\MessagesFacade;
use App\Rest\Common\RestCall;
use App\Rest\Requests\Settings\GrAvatarSettingsFormRequest;
use App\Rest\Responses\Settings\GrAvatarSettingsFormResponse;

/**
 * Class GrAvatarSettingsFormRest
 * @author Nicolás Marulanda P.
 */
class GrAvatarSettingsFormRest extends RestCall {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getForm(): ?GrAvatarSettingsFormResponse {
        try {
            return $this->get(NULL);
        } catch (\Exception $exception) {
            return NULL;
        }
    }
    
    public function putForm(GrAvatarSettingsFormRequest $request): ?GrAvatarSettingsFormResponse {
        try {
            return $this->put(NULL, $request);
        } catch (\Exception $exception) {
            return NULL;
        }
    }
    
    protected function baseClassParseTo(): string {
        return GrAvatarSettingsFormResponse::class;
    }
    
    protected function baseUri(): string {
        return 'dashboard/settings/gravatar';
    }
    
    protected function catchException(\Exception $exception): void {
        MessagesFacade::addDanger($exception->getMessage());
    }
    
}
