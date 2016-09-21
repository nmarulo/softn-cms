<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\models\admin\Category;
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
     * Metodo llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $output     = [];
        $template   = new Template();
        $category   = new CategoryTemplate(Category::selectByID($data['id']));
        $countData  = PostsCategoryTemplate::count($data['id']);
        $pagination = new Pagination($data['paged'], $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $posts      = PostsCategoryTemplate::selectByID($data['id'], $limit);
    
        $pagination->concatUrl("category/$data[id]");
        
        if ($posts !== \FALSE) {
            $postsTemplate = new PostsTemplate();
            $postsTemplate->addData($posts->getAll());
            $output = $postsTemplate->getAll();
        }
        
        return [
            'category'   => $category,
            'posts'      => $output,
            'pagination' => $pagination,
            'template'   => $template,
        ];
    }
    
}
