<?php
/**
 * CategoryController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\tables\Category;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class CategoryController
 * @author Nicolás Marulanda P.
 */
class CategoryController extends CUDControllerAbstract {
    
    public function create() {
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $categoriesManager = new CategoriesManager();
                $category          = Arrays::get($form, 'category');
                
                if ($categoriesManager->create($category)) {
                    $optionsManager = new OptionsManager();
                    Messages::addSuccess('Categoría publicada correctamente.', TRUE);
                    Util::redirect($optionsManager->getSiteUrl() . 'admin/category');
                }
            }
            
            Messages::addDanger('Error al publicar la categoría.');
        }
        
        ViewController::sendViewData('category', new Category());
        ViewController::sendViewData('title', 'Publicar nueva categoría');
        ViewController::view('form');
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
        $category->setCategoryPostCount(NULL);
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $category->setCategoryPostCount(0);
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
    
    public function update($id) {
        $categoriesManager = new CategoriesManager();
        $category          = $categoriesManager->searchById($id);
        
        if (empty($category)) {
            $optionsManager = new OptionsManager();
            Messages::addDanger('La categoría no existe.', TRUE);
            Util::redirect($optionsManager->getSiteUrl() . 'admin/category');
        } else {
            if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
                $form = $this->form();
                
                if (empty($form)) {
                    Messages::addDanger('Error en los campos de la categoría.');
                } else {
                    $category = Arrays::get($form, 'category');
                    
                    if ($categoriesManager->update($category)) {
                        Messages::addSuccess('Categoría actualizada correctamente.');
                    } else {
                        Messages::addDanger('Error al actualizar la categoría.');
                    }
                }
            }
            
            ViewController::sendViewData('category', $category);
            ViewController::sendViewData('title', 'Actualizar categoría');
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $categoriesManager = new CategoriesManager();
        
        if (empty($categoriesManager->delete($id))) {
            Messages::addDanger('Error al borrar la categoría.');
        } else {
            Messages::addSuccess('Categoría borrada correctamente.');
        }
        
        parent::delete($id);
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
    
}
