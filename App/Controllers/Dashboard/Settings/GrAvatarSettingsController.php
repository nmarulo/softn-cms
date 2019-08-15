<?php
/**
 * softn-cms
 */

namespace App\Controllers\Dashboard\Settings;

use App\Facades\MessagesFacade;
use App\Facades\Rest\Settings\GrAvatarSettingsFormRestFacade;
use App\Rest\Requests\Settings\GrAvatarSettingsFormRequest;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\View;

/**
 * Class GrAvatarSettingsController
 * @author Nicolás Marulanda P.
 */
class GrAvatarSettingsController extends Controller {
    
    /** @var string */
    private $urlGrAvatarSettings = 'dashboard/settings/gravatar';
    
    public function index() {
        $gravatar                = GrAvatarSettingsFormRestFacade::getForm();
        $incrementGravatarImage  = 1;
        $countGravatarImageValue = count($gravatar->gravatarImageValueList);
        
        return View::make('dashboard.settings.gravatar')
                   ->with('gravatar', $gravatar)
                   ->with('incrementGravatarImage', $incrementGravatarImage)
                   ->with('countGravatarImageValue', $countGravatarImageValue);
    }
    
    public function form() {
        $request                       = GrAvatarSettingsFormRequest::parseOf(Request::all());
        $request->gravatarForceDefault = boolval($request->gravatarForceDefault);
        
        GrAvatarSettingsFormRestFacade::putForm($request);
        
        if (!GrAvatarSettingsFormRestFacade::isError()) {
            MessagesFacade::addSuccess('Configuración del GrAvatar actualizada correctamente.');
        }
        
        Redirect::to(sprintf('%1$s/%2$s', URL, $this->urlGrAvatarSettings));
    }
    
}
