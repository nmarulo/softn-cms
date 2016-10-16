<?php

/**
 * Modulo del controlador de la pagina de categorías.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\BaseController;
use SoftnCMS\controllers\Form;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\controllers\Token;
use SoftnCMS\Helpers\ArrayHelp;
use SoftnCMS\Helpers\Helps;
use SoftnCMS\models\admin\Categories;
use SoftnCMS\models\admin\Category;
use SoftnCMS\models\admin\CategoryDelete;
use SoftnCMS\models\admin\CategoryInsert;
use SoftnCMS\models\admin\CategoryUpdate;
use SoftnCMS\models\admin\template\Template;

/**
 * Clase del controlador de la pagina de categorías.
 * @author Nicolás Marulanda P.
 */
class CategoryController extends BaseController {
    
    /**
     * Metodo llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $output     = [];
        $countData  = Categories::count();
        $pagination = new Pagination(ArrayHelp::get($data, 'paged'), $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $categories = Categories::selectByLimit($limit);
        Template::setPagination($pagination);
        
        if ($categories !== \FALSE) {
            $output = $categories->getAll();
        }
        
        return [
            'categories' => $output,
        ];
    }
    
    /**
     * Metodo llamado por la función INSERT.
     * @return array
     */
    protected function dataInsert() {
        if (Form::submit('publish')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput !== FALSE) {
                //Las categorías tienen nombres unicos, si ya existe se le agrega un numero al final
                $categoryName = $this->checkName($dataInput['categoryName']);
                
                $insert = new CategoryInsert($categoryName, $dataInput['categoryDescription']);
                
                if ($insert->insert()) {
                    Messages::addSuccess('Categoría publicada correctamente.');
                    //Si es correcto se muestra la pagina de edición.
                    Helps::redirectRoute('update/' . $insert->getLastInsertId());
                }
            }
            Messages::addError('Error al publicar categoría');
        }
        
        return [
            //Datos por defecto a mostrar en el formulario.
            'category' => Category::defaultInstance(),
        ];
    }
    
    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    protected function getDataInput() {
        if (Token::check()) {
            Form::addInputAlphanumeric('categoryName', TRUE);
            Form::addInputAlphanumeric('categoryDescription');
            
            return Form::postInput();
        }
        
        return FALSE;
    }
    
    /**
     * @param  string $categoryName
     * @param int     $id
     *
     * @return string
     */
    private function checkName($categoryName, $id = 0) {
        $name = Category::selectByName($categoryName);
        $aux  = $name;
        $num  = 0;
        
        while ($name !== FALSE && $name->getID() != $id) {
            $name = $aux->getCategoryName() . ++$num;
            $name = Category::selectByName($name);
        }
        
        if ($num > 0) {
            $name = $aux->getCategoryName() . $num;
        }
        
        if ($name === FALSE || (is_object($name) && $name->getID() == $id)) {
            $name = $categoryName;
        }
        
        return $name;
    }
    
    /**
     * Metodo llamado por la función UPDATE.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataUpdate($data) {
        $id       = ArrayHelp::get($data, 'id');
        $category = Category::selectByID($id);
        
        //En caso de que no exista.
        if (empty($category)) {
            Messages::addError('Error. La categoría no existe.');
            Helps::redirectRoute();
        }
        
        if (Form::submit('update')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput === FALSE) {
                Messages::addError('Error al actualizar la entrada.');
            } else {
                //Las categorías tienen nombres unicos, si ya existe se le agrega un numero al final
                $categoryName = $this->checkName($dataInput['categoryName'], $id);
                $update       = new CategoryUpdate($category, $categoryName, $dataInput['categoryDescription']);
                
                //Si ocurre un error la función "$update->update()" retorna FALSE.
                if ($update->update()) {
                    Messages::addSuccess('Categoría actualizada correctamente.');
                    $category = $update->getDataUpdate();
                } else {
                    Messages::addError('Error al actualizar la categoría.');
                }
            }
        }
        
        return [
            //Instancia Category
            'category' => $category,
        ];
    }
    
    /**
     * Metodo llamado por la función DELETE.
     *
     * @param array $data Lista de argumentos.
     */
    protected function dataDelete($data) {
        /*
         * Ya que este metodo no tiene modulo vista propio
         * se carga el modulo vista INDEX, asi que se retornan los datos
         * para esta vista.
         */
        
        $output = FALSE;
        
        if (Token::check()) {
            $delete = new CategoryDelete($data['id']);
            $output = $delete->delete();
        }
        
        if ($output) {
            Messages::addSuccess('Categoría borrada correctamente.');
        } elseif ($output === 0) {
            Messages::addWarning('La Categoría no existe.');
        } else {
            Messages::addError('Error al borrar la categoría.');
        }
        
    }
    
}
