<?php
/**
 * CategoryController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\tables\Category;
use SoftnCMS\rute\Router;
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
        if (Form::submit(Constants::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $categoriesManager = new CategoriesManager();
                $category          = Arrays::get($form, 'category');
                
                if ($categoriesManager->create($category)) {
                    Messages::addSuccess(__('Categoría publicada correctamente.'), TRUE);
                    Util::redirect(Router::getSiteURL() . 'admin/category');
                }
            }
            
            Messages::addDanger(__('Error al publicar la categoría.'));
        }
        
        ViewController::sendViewData('isUpdate', FALSE);
        ViewController::sendViewData('category', new Category());
        ViewController::sendViewData('title', __('Publicar nueva categoría'));
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $category = new Category();
        $category->setId(Arrays::get($inputs, CategoriesManager::COLUMN_ID));
        $category->setCategoryName(Arrays::get($inputs, CategoriesManager::CATEGORY_NAME));
        $category->setCategoryDescription(Arrays::get($inputs, CategoriesManager::CATEGORY_DESCRIPTION));
        $category->setCategoryPostCount(NULL);
        
        if (Form::submit(Constants::FORM_CREATE)) {
            $category->setCategoryPostCount(0);
        }
        
        return ['category' => $category];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputIntegerBuilder::init(CategoriesManager::COLUMN_ID)
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
            Messages::addDanger(__('La categoría no existe.'), TRUE);
            Util::redirect(Router::getSiteURL(), 'admin/category');
        } else {
            if (Form::submit(Constants::FORM_UPDATE)) {
                $form = $this->form();
                
                if (empty($form)) {
                    Messages::addDanger(__('Error en los campos de la categoría.'));
                } else {
                    $category = Arrays::get($form, 'category');
                    
                    if ($categoriesManager->update($category)) {
                        Messages::addSuccess(__('Categoría actualizada correctamente.'));
                    } else {
                        Messages::addDanger(__('Error al actualizar la categoría.'));
                    }
                }
            }
            
            ViewController::sendViewData('isUpdate', TRUE);
            ViewController::sendViewData('category', $category);
            ViewController::sendViewData('title', __('Actualizar categoría'));
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $categoriesManager = new CategoriesManager();
        
        if (empty($categoriesManager->deleteById($id))) {
            Messages::addDanger(__('Error al borrar la categoría.'));
        } else {
            Messages::addSuccess(__('Categoría borrada correctamente.'));
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $categoriesManager = new CategoriesManager();
        $count             = $categoriesManager->count();
        $limit             = parent::pagination($count);
        
        ViewController::sendViewData('categories', $categoriesManager->searchAll($limit));
    }
    
}
