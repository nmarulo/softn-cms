<?php
/**
 * softn-cms
 */

namespace App\Controllers\Dashboard;

use App\Facades\MessagesFacade;
use App\Facades\Rest\SettingsFormRestFacade;
use App\Rest\Requests\Settings\SettingsFormRequest;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\View;

/**
 * Class SettingsController
 * @author Nicolás Marulanda P.
 */
class SettingsController extends Controller {
    
    /**
     * @var string
     */
    private $urlSettings = 'dashboard/settings';
    
    public function index() {
        $settings = SettingsFormRestFacade::getForm();
        
        return View::make('dashboard.settings.index')
                   ->with('settings', $settings);
    }
    
    public function form() {
        $request = SettingsFormRequest::parseOf(Request::all());
        SettingsFormRestFacade::putForm($request);
        
        if (!SettingsFormRestFacade::isError()) {
            MessagesFacade::addSuccess('Configuración actualizada correctamente.');
        }
        
        Redirect::to(sprintf('%1$s/%2$s', URL, $this->urlSettings));
    }
    
}
