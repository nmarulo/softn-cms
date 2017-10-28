<?php
/**
 * CategoryController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\tables\Category;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;

/**
 * Class CategoryController
 * @author Nicolás Marulanda P.
 */
class CategoryController extends ControllerAbstract {
    
    public function create() {
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            if ($this->isValidForm()) {
                $categoriesManager = new CategoriesManager();
                $category          = $this->getForm('category');
                
                if ($categoriesManager->create($category)) {
                    Messages::addSuccess(__('Categoría publicada correctamente.'), TRUE);
                    $this->redirectToAction('index');
                }
            }
            
            Messages::addDanger(__('Error al publicar la categoría.'));
        }
        
        $this->sendDataView([
            'isUpdate' => FALSE,
            'category' => new Category(),
            'title'    => __('Publicar nueva categoría'),
        ]);
        
        $this->view('form');
    }
    
    public function update($id) {
        $categoriesManager = new CategoriesManager();
        $category          = $categoriesManager->searchById($id);
        
        if (empty($category)) {
            Messages::addDanger(__('La categoría no existe.'), TRUE);
            $this->redirectToAction('index');
        } elseif ($this->checkSubmit(Constants::FORM_UPDATE)) {
            if ($this->isValidForm()) {
                $category = $this->getForm('category');
                
                if ($categoriesManager->update($category)) {
                    Messages::addSuccess(__('Categoría actualizada correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar la categoría.'));
                }
            } else {
                Messages::addDanger(__('Error en los campos de la categoría.'));
            }
        }
        
        $this->sendDataView([
            'isUpdate' => TRUE,
            'category' => $category,
            'title'    => __('Actualizar categoría'),
        ]);
        $this->view('form');
    }
    
    public function delete($id) {
        if (Token::check()) {
            $categoriesManager = new CategoriesManager();
            $result            = $categoriesManager->deleteById($id);
            $rowCount          = $categoriesManager->getRowCount();
            
            if ($rowCount == 0) {
                Messages::addWarning(__('No existe ninguna categoría.'), TRUE);
            } elseif ($result) {
                Messages::addSuccess(__('Categoría borrada correctamente.'), TRUE);
            } else {
                Messages::addDanger(__('Error al borrar la categoría.'), TRUE);
            }
        }
        
        $this->redirectToAction('index');
    }
    
    public function index() {
        $categoriesManager = new CategoriesManager();
        $count             = $categoriesManager->count();
        
        $this->sendDataView([
            'categories' => $categoriesManager->searchAll($this->rowsPages($count)),
        ]);
        $this->view();
    }
    
    protected function formToObject() {
        $category = new Category();
        $category->setId($this->getInput(CategoriesManager::COLUMN_ID));
        $category->setCategoryName($this->getInput(CategoriesManager::CATEGORY_NAME));
        $category->setCategoryDescription($this->getInput(CategoriesManager::CATEGORY_DESCRIPTION));
        $category->setCategoryPostCount(NULL);
        
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            $category->setCategoryPostCount(0);
        }
        
        return ['category' => $category];
    }
    
    protected function formInputsBuilders() {
        return [
            InputIntegerBuilder::init(CategoriesManager::COLUMN_ID)
                               ->build(),
            InputAlphanumericBuilder::init(CategoriesManager::CATEGORY_NAME)
                                    ->build(),
            InputAlphanumericBuilder::init(CategoriesManager::CATEGORY_DESCRIPTION)
                                    ->setRequire(FALSE)
                                    ->build(),
        ];
    }
    
}
