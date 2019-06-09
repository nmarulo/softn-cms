<?php
/**
 * softn-cms
 */

namespace App\Rest\Calls;

use App\Facades\MessagesFacade;
use App\Rest\Common\RestCall;
use App\Rest\Requests\Settings\SettingsFormRequest;
use App\Rest\Responses\Settings\SettingsFormResponse;

/**
 * Class SettingsRest
 * @author Nicolás Marulanda P.
 */
class SettingsFormRest extends RestCall {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getForm(): ?SettingsFormResponse {
        try {
            return $this->get(NULL);
        } catch (\Exception $exception) {
            return NULL;
        }
    }
    
    public function putForm(SettingsFormRequest $request): ?SettingsFormResponse {
        try {
            return $this->put(NULL, $request);
        } catch (\Exception $exception) {
            return NULL;
        }
    }
    
    protected function baseClassParseTo(): string {
        return SettingsFormResponse::class;
    }
    
    protected function baseUri(): string {
        return 'dashboard/settings/form';
    }
    
    protected function catchException(\Exception $exception): void {
        MessagesFacade::addDanger($exception->getMessage());
    }
    
}
