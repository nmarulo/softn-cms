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
        $showForm = TRUE;
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form        = $this->form();
            $messages    = 'Error al publicar la etiqueta.';
            $typeMessage = Messages::TYPE_DANGER;
            
            if (!empty($form)) {
                $termsManager = new TermsManager();
                $term         = Arrays::get($form, 'term');
                
                if ($termsManager->create($term)) {
                    $showForm    = FALSE;
                    $messages    = 'Etiqueta publicada correctamente.';
                    $typeMessage = Messages::TYPE_SUCCESS;
                    Messages::addSessionMessage($messages, $typeMessage);
                    $optionsManager = new OptionsManager();
                    Util::redirect($optionsManager->getSiteUrl() . 'admin/term');
                }
            }
            
            Messages::addMessage($messages, $typeMessage);
        }
        
        if ($showForm) {
            ViewController::sendViewData('term', new Term());
            ViewController::sendViewData('title', 'Publicar nueva etiqueta');
            ViewController::view('form');
        }
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
        Form::setINPUT([
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
        $typeMessage  = Messages::TYPE_DANGER;
        $messages     = 'La etiqueta no existe.';
        $termsManager = new TermsManager();
        $term         = $termsManager->searchById($id);
        
        if (empty($term)) {
            $optionsManager = new OptionsManager();
            Messages::addSessionMessage($messages, $typeMessage);
            Util::redirect($optionsManager->getSiteUrl() . 'admin/term');
        } else {
            if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
                $messages = 'Error al actualizar la etiqueta.';
                $form     = $this->form();
                
                if (!empty($form)) {
                    $term = Arrays::get($form, 'term');
                    
                    if ($termsManager->update($term)) {
                        $messages    = 'Etiqueta actualizada correctamente.';
                        $typeMessage = Messages::TYPE_SUCCESS;
                    }
                }
                
                Messages::addMessage($messages, $typeMessage);
            }
            
            ViewController::sendViewData('term', $term);
            ViewController::sendViewData('title', 'Actualizar etiqueta');
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $messages     = 'Error al borrar la etiqueta.';
        $typeMessage  = Messages::TYPE_DANGER;
        $termsManager = new TermsManager();
        
        if (!empty($termsManager->delete($id))) {
            $messages    = 'Etiqueta borrada correctamente.';
            $typeMessage = Messages::TYPE_SUCCESS;
        }
        
        Messages::addMessage($messages, $typeMessage);
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
