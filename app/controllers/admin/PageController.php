<?php
/**
 * PageController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\PagesManager;
use SoftnCMS\models\tables\Page;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\builders\InputHtmlBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\form\InputHtml;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class PageController
 * @author Nicolás Marulanda P.
 */
class PageController extends CUDControllerAbstract {
    
    public function create() {
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $pagesManager = new PagesManager();
                $page         = Arrays::get($form, 'page');
                
                if ($pagesManager->create($page)) {
                    Messages::addSuccess(__('Pagina creada correctamente.'), TRUE);
                    Util::redirect(Router::getSiteURL() . 'admin/page');
                }
            }
            
            Messages::addDanger(__('Error al publicar la pagina.'));
        }
        
        ViewController::sendViewData('page', new Page());
        ViewController::sendViewData('title', __('Publicar nueva pagina'));
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $page = new Page();
        $page->setId(Arrays::get($inputs, PagesManager::ID));
        $page->setPageTitle(Arrays::get($inputs, PagesManager::PAGE_TITLE));
        $page->setPageContents(Arrays::get($inputs, PagesManager::PAGE_CONTENTS));
        $page->setPageStatus(Arrays::get($inputs, PagesManager::PAGE_STATUS));
        $page->setPageCommentStatus(Arrays::get($inputs, PagesManager::PAGE_COMMENT_STATUS));
        $page->setPageDate(NULL);
        $page->setPageCommentCount(NULL);
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $page->setPageDate(Util::dateNow());
            $page->setPageCommentCount(0);
        }
        
        return ['page' => $page];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputIntegerBuilder::init(PagesManager::ID)
                               ->build(),
            InputAlphanumericBuilder::init(PagesManager::PAGE_TITLE)
                                    ->build(),
            InputBooleanBuilder::init(PagesManager::PAGE_STATUS)
                               ->build(),
            InputHtmlBuilder::init(PagesManager::PAGE_CONTENTS)
                                    ->build(),
            InputBooleanBuilder::init(PagesManager::PAGE_COMMENT_STATUS)
                               ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    public function update($id) {
        $pagesManager = new PagesManager();
        $page         = $pagesManager->searchById($id);
        
        if (empty($page)) {
            Messages::addDanger(__('La pagina no existe.'), TRUE);
            Util::redirect(Router::getSiteURL() . 'admin/page');
        }
        
        if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
            $form = $this->form();
            
            if (empty($form)) {
                Messages::addDanger(__('Error en los campos de la pagina.'));
            } else {
                $page = Arrays::get($form, 'page');
                
                if ($pagesManager->update($page)) {
                    Messages::addSuccess(__('Pagina actualizada correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar la pagina.'));
                }
            }
        }
        
        $linkPage = Router::getSiteURL() . 'page/' . $page->getId();
        ViewController::sendViewData('linkPage', $linkPage);
        ViewController::sendViewData('page', $page);
        ViewController::sendViewData('title', __('Actualizar pagina'));
        ViewController::view('form');
    }
    
    public function delete($id) {
        $pagesManager = new PagesManager();
        
        if (empty($pagesManager->delete($id))) {
            Messages::addDanger(__('Error al borrar la pagina.'));
        } else {
            Messages::addSuccess(__('Pagina borrada correctamente.'));
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $filters      = [];
        $pagesManager = new PagesManager();
        $count        = $pagesManager->count();
        $pagination   = parent::pagination($count);
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        ViewController::sendViewData('pages', $pagesManager->read($filters));
    }
    
}
