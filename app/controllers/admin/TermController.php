<?php
/**
 * TermController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\tables\Term;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;

/**
 * Class TermController
 * @author Nicolás Marulanda P.
 */
class TermController extends ControllerAbstract {
    
    public function index() {
        $termsManager = new TermsManager($this->getConnectionDB());
        $count        = $termsManager->count();
        
        $this->sendDataView([
            'terms' => $termsManager->searchAll($this->rowsPages($count)),
        ]);
        $this->view();
    }
    
    public function create() {
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            if ($this->isValidForm()) {
                $termsManager = new TermsManager($this->getConnectionDB());
                $term         = $this->getForm('term');
                
                if ($termsManager->create($term)) {
                    Messages::addSuccess(__('Etiqueta publicada correctamente.'), TRUE);
                    $this->redirectToAction('index');
                }
            }
            
            Messages::addDanger(__('Error al publicar la etiqueta.'));
        }
        
        $this->sendDataView([
            'isUpdate' => FALSE,
            'term'     => new Term(),
            'title'    => __('Publicar nueva etiqueta'),
        ]);
        $this->view('form');
    }
    
    public function update($id) {
        $termsManager = new TermsManager($this->getConnectionDB());
        $term         = $termsManager->searchById($id);
        
        if (empty($term)) {
            Messages::addDanger(__('La etiqueta no existe.'), TRUE);
            $this->redirectToAction('index');
        } elseif ($this->checkSubmit(Constants::FORM_UPDATE)) {
            if ($this->isValidForm()) {
                $term = $this->getForm('term');
                
                if ($termsManager->update($term)) {
                    Messages::addSuccess(__('Etiqueta actualizada correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar la etiqueta.'));
                }
            } else {
                Messages::addDanger(__('Error en los campos de la etiqueta.'));
            }
        }
        
        $this->sendDataView([
            'isUpdate' => TRUE,
            'term'     => $term,
            'title'    => __('Actualizar etiqueta'),
        ]);
        $this->view('form');
    }
    
    public function delete($id) {
        if (Token::check()) {
            $termsManager = new TermsManager($this->getConnectionDB());
            $result       = $termsManager->deleteById($id);
            $rowCount     = $termsManager->getRowCount();
            
            if ($rowCount === 0) {
                Messages::addWarning(__('La etiqueta no existe.'), TRUE);
            } elseif ($result) {
                Messages::addSuccess(__('Etiqueta borrada correctamente.'), TRUE);
            } else {
                Messages::addDanger(__('Error al borrar la etiqueta.'), TRUE);
            }
        }
        
        $this->redirectToAction('index');
    }
    
    protected function formToObject() {
        $term = new Term();
        $term->setId($this->getInput(TermsManager::COLUMN_ID));
        $term->setTermPostCount(NULL);
        $term->setTermDescription($this->getInput(TermsManager::TERM_DESCRIPTION));
        $term->setTermName($this->getInput(TermsManager::TERM_NAME));
        
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            $term->setTermPostCount(0);
        }
        
        return ['term' => $term];
    }
    
    protected function formInputsBuilders() {
        return [
            InputIntegerBuilder::init(TermsManager::COLUMN_ID)
                               ->build(),
            InputAlphanumericBuilder::init(TermsManager::TERM_NAME)
                                    ->build(),
            InputAlphanumericBuilder::init(TermsManager::TERM_DESCRIPTION)
                                    ->setRequire(FALSE)
                                    ->build(),
        ];
    }
    
}
