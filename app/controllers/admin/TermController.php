<?php
/**
 * TermController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\tables\Term;
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
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $termsManager = new TermsManager();
                $term         = Arrays::get($form, 'term');
                
                if ($termsManager->create($term)) {
                    Messages::addSuccess('Etiqueta publicada correctamente.', TRUE);
                    $optionsManager = new OptionsManager();
                    Util::redirect($optionsManager->getSiteUrl() . 'admin/term');
                }
            }
            
            Messages::addDanger('Error al publicar la etiqueta.');
        }
        
        ViewController::sendViewData('term', new Term());
        ViewController::sendViewData('title', 'Publicar nueva etiqueta');
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $term = new Term();
        $term->setId(Arrays::get($inputs, TermsManager::ID));
        $term->setTermPostCount(NULL);
        $term->setTermDescription(Arrays::get($inputs, TermsManager::TERM_DESCRIPTION));
        $term->setTermName(Arrays::get($inputs, TermsManager::TERM_NAME));
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $term->setTermPostCount(0);
        }
        
        return ['term' => $term];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputIntegerBuilder::init(TermsManager::ID)
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
            $optionsManager = new OptionsManager();
            Messages::addDanger('La etiqueta no existe.', TRUE);
            Util::redirect($optionsManager->getSiteUrl() . 'admin/term');
        } else {
            if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
                $form = $this->form();
                
                if (empty($form)) {
                    Messages::addDanger('Error en los campos de la etiqueta.');
                } else {
                    $term = Arrays::get($form, 'term');
                    
                    if ($termsManager->update($term)) {
                        Messages::addSuccess('Etiqueta actualizada correctamente.');
                    } else {
                        Messages::addDanger('Error al actualizar la etiqueta.');
                    }
                }
                
            }
            
            ViewController::sendViewData('term', $term);
            ViewController::sendViewData('title', 'Actualizar etiqueta');
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $termsManager = new TermsManager();
        
        if (empty($termsManager->delete($id))) {
            Messages::addDanger('Error al borrar la etiqueta.');
        } else {
            Messages::addSuccess('Etiqueta borrada correctamente.');
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $filters      = [];
        $termsManager = new TermsManager();
        $count        = $termsManager->count();
        $pagination   = parent::pagination($count);
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        ViewController::sendViewData('terms', $termsManager->read($filters));
    }
    
}
