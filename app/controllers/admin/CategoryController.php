<?php
/**
 * CategoryController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\tables\Category;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;

/**
 * Class CategoryController
 * @author Nicolás Marulanda P.
 */
class CategoryController extends CUDControllerAbstract {
    
    public function create() {
        $showForm = TRUE;
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form        = $this->form();
            $messages    = 'Error al publicar la categoría.';
            $typeMessage = Messages::TYPE_DANGER;
            
            if (!empty($form)) {
                $categoriesManager = new CategoriesManager();
                $category          = Arrays::get($form, 'category');
                
                if ($categoriesManager->create($category)) {
                    $showForm    = FALSE;
                    $messages    = 'Categoría publicada correctamente.';
                    $typeMessage = Messages::TYPE_SUCCESS;
                    Messages::addMessage($messages, $typeMessage);
                    $this->index();
                }
            }
            
            Messages::addMessage($messages, $typeMessage);
        }
        
        if ($showForm) {
            ViewController::sendViewData('category', new Category());
            ViewController::sendViewData('title', 'Publicar nueva categoría');
            ViewController::view('form');
        }
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $category = new Category();
        $category->setId(Arrays::get($inputs, CategoriesManager::ID));
        $category->setCategoryName(Arrays::get($inputs, CategoriesManager::CATEGORY_NAME));
        $category->setCategoryDescription(Arrays::get($inputs, CategoriesManager::CATEGORY_DESCRIPTION));
        $category->setCategoryCount(NULL);
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $category->setCategoryCount(0);
        }
        
        return ['category' => $category];
    }
    
    protected function filterInputs() {
        Form::setINPUT([
            InputIntegerBuilder::init(CategoriesManager::ID)
                               ->build(),
            InputAlphanumericBuilder::init(CategoriesManager::CATEGORY_NAME)
                                    ->build(),
            InputAlphanumericBuilder::init(CategoriesManager::CATEGORY_DESCRIPTION)
                                    ->setRequire(FALSE)
                                    ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    public function index() {
        $this->read();
        ViewController::view('index');
    }
    
    protected function read() {
        $filters           = [];
        $categoriesManager = new CategoriesManager();
        $count             = $categoriesManager->count();
        $pagination        = parent::pagination($count);
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        ViewController::sendViewData('categories', $categoriesManager->read($filters));
    }
    
    public function update($id) {
        $typeMessage       = Messages::TYPE_DANGER;
        $messages          = 'La categoría no existe.';
        $categoriesManager = new CategoriesManager();
        $category          = $categoriesManager->searchById($id);
        
        if (empty($category)) {
            Messages::addMessage($messages, $typeMessage);
            $this->index();
        } else {
            if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
                $messages = 'Error al actualizar la categoría.';
                $form     = $this->form();
                
                if (!empty($form)) {
                    $category = Arrays::get($form, 'category');
                    
                    if ($categoriesManager->update($category)) {
                        $messages    = 'Categoría actualizada correctamente.';
                        $typeMessage = Messages::TYPE_SUCCESS;
                    }
                }
                
                Messages::addMessage($messages, $typeMessage);
            }
            
            ViewController::sendViewData('category', $category);
            ViewController::sendViewData('title', 'Actualizar categoría');
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $messages          = 'Error al borrar la categoría.';
        $typeMessage       = Messages::TYPE_DANGER;
        $categoriesManager = new CategoriesManager();
        
        if (!empty($categoriesManager->delete($id))) {
            $messages    = 'Categoría borrada correctamente.';
            $typeMessage = Messages::TYPE_SUCCESS;
        }
        
        Messages::addMessage($messages, $typeMessage);
        parent::delete($id);
    }
    
}
