<?php
/**
 * TermController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\tables\Term;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class TermController
 * @author Nicolás Marulanda P.
 */
class TermController extends CUDControllerAbstract {
    
    public function create() {
        if (Form::submit(Constants::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $termsManager = new TermsManager();
                $term         = Arrays::get($form, 'term');
                
                if ($termsManager->create($term)) {
                    Messages::addSuccess(__('Etiqueta publicada correctamente.'), TRUE);
                    Util::redirect(Router::getSiteURL(), 'admin/term');
                }
            }
            
            Messages::addDanger(__('Error al publicar la etiqueta.'));
        }
        
        ViewController::sendViewData('isUpdate', FALSE);
        ViewController::sendViewData('term', new Term());
        ViewController::sendViewData('title', __('Publicar nueva etiqueta'));
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $term = new Term();
        $term->setId(Arrays::get($inputs, TermsManager::COLUMN_ID));
        $term->setTermPostCount(NULL);
        $term->setTermDescription(Arrays::get($inputs, TermsManager::TERM_DESCRIPTION));
        $term->setTermName(Arrays::get($inputs, TermsManager::TERM_NAME));
        
        if (Form::submit(Constants::FORM_CREATE)) {
            $term->setTermPostCount(0);
        }
        
        return ['term' => $term];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputIntegerBuilder::init(TermsManager::COLUMN_ID)
                               ->build(),
            InputAlphanumericBuilder::init(TermsManager::TERM_NAME)
                                    ->build(),
            InputAlphanumericBuilder::init(TermsManager::TERM_DESCRIPTION)
                                    ->setRequire(FALSE)
                                    ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    public function update($id) {
        $termsManager = new TermsManager();
        $term         = $termsManager->searchById($id);
        
        if (empty($term)) {
            Messages::addDanger(__('La etiqueta no existe.'), TRUE);
            Util::redirect(Router::getSiteURL(), 'admin/term');
        } else {
            if (Form::submit(Constants::FORM_UPDATE)) {
                $form = $this->form();
                
                if (empty($form)) {
                    Messages::addDanger(__('Error en los campos de la etiqueta.'));
                } else {
                    $term = Arrays::get($form, 'term');
                    
                    if ($termsManager->update($term)) {
                        Messages::addSuccess(__('Etiqueta actualizada correctamente.'));
                    } else {
                        Messages::addDanger(__('Error al actualizar la etiqueta.'));
                    }
                }
                
            }
            ViewController::sendViewData('isUpdate', TRUE);
            ViewController::sendViewData('term', $term);
            ViewController::sendViewData('title', __('Actualizar etiqueta'));
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $termsManager = new TermsManager();
        
        if (empty($termsManager->deleteById($id))) {
            Messages::addDanger(__('Error al borrar la etiqueta.'));
        } else {
            Messages::addSuccess(__('Etiqueta borrada correctamente.'));
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $termsManager = new TermsManager();
        $count        = $termsManager->count();
        $limit        = parent::pagination($count);
        
        ViewController::sendViewData('terms', $termsManager->searchAll($limit));
    }
    
}
