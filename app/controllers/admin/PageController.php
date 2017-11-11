<?php
/**
 * PageController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\PagesManager;
use SoftnCMS\models\tables\Page;
use SoftnCMS\rute\Router;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\builders\InputHtmlBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;
use SoftnCMS\util\Util;

/**
 * Class PageController
 * @author Nicolás Marulanda P.
 */
class PageController extends ControllerAbstract {
    
    public function index() {
        $pagesManager = new PagesManager();
        $count        = $pagesManager->count();
        
        $this->sendDataView([
            'pages' => $pagesManager->searchAll($this->rowsPages($count)),
        ]);
        $this->view();
    }
    
    public function create() {
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            if ($this->isValidForm()) {
                $pagesManager = new PagesManager();
                $page         = $this->getForm('page');
                
                if ($pagesManager->create($page)) {
                    Messages::addSuccess(__('Pagina creada correctamente.'), TRUE);
                    $this->redirectToAction('index');
                }
            }
            
            Messages::addDanger(__('Error al publicar la pagina.'));
        }
        
        $this->sendDataView([
            'isUpdate' => FALSE,
            'page'     => new Page(),
            'title'    => __('Publicar nueva pagina'),
        ]);
        $this->view('form');
    }
    
    public function update($id) {
        $pagesManager = new PagesManager();
        $page         = $pagesManager->searchById($id);
        
        if (empty($page)) {
            Messages::addDanger(__('La pagina no existe.'), TRUE);
            $this->redirectToAction('index');
        } elseif ($this->checkSubmit(Constants::FORM_UPDATE)) {
            if ($this->isValidForm()) {
                $page = $this->getForm('page');
                
                if ($pagesManager->updateByColumnId($page)) {
                    Messages::addSuccess(__('Pagina actualizada correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar la pagina.'));
                }
            } else {
                Messages::addDanger(__('Error en los campos de la pagina.'));
            }
        }
        
        $this->sendDataView([
            'isUpdate' => TRUE,
            'linkPage' => Router::getSiteURL() . 'page/' . $page->getId(),
            'page'     => $page,
            'title'    => __('Actualizar pagina'),
        ]);
        $this->view('form');
    }
    
    public function delete($id) {
        if (Token::check()) {
            $pagesManager = new PagesManager();
            $result       = $pagesManager->deleteById($id);
            $rowCount     = $pagesManager->getRowCount();
            
            if ($rowCount === 0) {
                Messages::addWarning(__('La pagina no existe.'), TRUE);
            } elseif ($result) {
                Messages::addSuccess(__('Pagina borrada correctamente.'), TRUE);
            } else {
                Messages::addDanger(__('Error al borrar la pagina.'), TRUE);
            }
        }
        
        $this->redirectToAction('index');
    }
    
    protected function formToObject() {
        $page = new Page();
        $page->setId($this->getInput(PagesManager::COLUMN_ID));
        $page->setPageTitle($this->getInput(PagesManager::PAGE_TITLE));
        $page->setPageContents($this->getInput(PagesManager::PAGE_CONTENTS));
        $page->setPageStatus($this->getInput(PagesManager::PAGE_STATUS));
        $page->setPageCommentStatus($this->getInput(PagesManager::PAGE_COMMENT_STATUS));
        $page->setPageDate(NULL);
        $page->setPageCommentCount(NULL);
        
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            $page->setPageDate(Util::dateNow());
            $page->setPageCommentCount(0);
        }
        
        return ['page' => $page];
    }
    
    protected function formInputsBuilders() {
        return [
            InputIntegerBuilder::init(PagesManager::COLUMN_ID)
                               ->build(),
            InputAlphanumericBuilder::init(PagesManager::PAGE_TITLE)
                                    ->build(),
            InputBooleanBuilder::init(PagesManager::PAGE_STATUS)
                               ->build(),
            InputHtmlBuilder::init(PagesManager::PAGE_CONTENTS)
                            ->build(),
            InputBooleanBuilder::init(PagesManager::PAGE_COMMENT_STATUS)
                               ->build(),
        ];
    }
    
}
