<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\Helpers\ArrayHelp;
use SoftnCMS\Helpers\Helps;
use SoftnCMS\models\admin\Category;
use SoftnCMS\models\admin\PostsCategories;
use SoftnCMS\models\theme\CategoryTemplate;
use SoftnCMS\models\theme\PostsCategoryTemplate;
use SoftnCMS\models\theme\PostsTemplate;
use SoftnCMS\models\theme\Template;

/**
 * Description of CategoryController
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
