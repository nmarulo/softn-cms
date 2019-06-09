<?php
/**
 * softn-cms
 */

namespace App\Rest\Calls;

use App\Facades\MessagesFacade;
use App\Rest\Common\RestCall;
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
            return $this->get(NULL, 'form');
        } catch (\Exception $exception) {
            return NULL;
        }
    }
    
    protected function baseClassParseTo(): string {
        return SettingsFormResponse::class;
    }
    
    protected function baseUri(): string {
        return 'dashboard/settings';
    }
    
    protected function catchException(\Exception $exception): void {
        MessagesFacade::addDanger($exception->getMessage());
    }
    
}
