<?php

/**
 * Modulo controlador: Pagina de categorías de la plantilla de la aplicación.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\helpers\ArrayHelp;
use SoftnCMS\helpers\Helps;
use SoftnCMS\models\theme\CategoryTemplate;
use SoftnCMS\models\theme\PostsCategoryTemplate;
use SoftnCMS\models\theme\Template;

/**
 * Clase CategoryController de la pagina de categorías de la plantilla de la aplicación.
 * @author Nicolás Marulanda P.
 */
class CategoryController extends Controller {
    
    /**
     * Método llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $id       = ArrayHelp::get($data, 'id');
        $category = CategoryTemplate::selectByID($id);
        
        if ($category === FALSE) {
            Helps::redirect();
        }
        //Se agrega el nombre al titulo de la pagina
        Template::setTitle(' | ' . $category->getCategoryName());
        $output     = [];
        $countData  = PostsCategoryTemplate::count($id);
        $pagination = new Pagination(ArrayHelp::get($data, 'paged'), $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $posts      = PostsCategoryTemplate::selectByCategoryIDLimit($id, $limit);
        
        Template::setPagination($pagination);
        
        if ($posts !== \FALSE) {
            $output = $posts->getAll();
        }
        
        return [
            'category' => $category,
            'posts'    => $output,
        ];
    }
    
}
