<?php
/**
 * softn-cms
 */

namespace App\Controllers\Dashboard;

use App\Facades\Rest\SettingsFormRestFacade;
use Silver\Core\Controller;
use Silver\Http\View;

/**
 * Class SettingsController
 * @author Nicolás Marulanda P.
 */
class SettingsController extends Controller {
    
    public function index() {
        $settings = SettingsFormRestFacade::getForm();
        
        return View::make('dashboard.settings.index')
                   ->with('settings', $settings);
    }
    
}
