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
use SoftnCMS\models\theme\PostsTemplate;
use SoftnCMS\models\theme\PostsTermTemplate;
use SoftnCMS\models\admin\Term;
use SoftnCMS\models\theme\Template;
use SoftnCMS\models\theme\TermTemplate;

/**
 * Description of Term
 * @author Nicolás Marulanda P.
 */
class TermController extends Controller {
    
    /**
     * Método llamado por la función INDEX.
     *
     * @param array $data
     *
     * @return array
     */
    protected function dataIndex($data) {
        $id   = ArrayHelp::get($data, 'id');
        $term = TermTemplate::selectByID($id);
        
        if ($term === FALSE) {
            Helps::redirect();
        }
        
        Template::setTitle(' | ' . $term->getTermName());
        $output     = [];
        $countData  = PostsTermTemplate::count($id);
        $pagination = new Pagination(ArrayHelp::get($data, 'paged'), $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $posts      = PostsTermTemplate::selectByTermIDLimit($id, $limit);
        
        Template::setPagination($pagination);
        
        if ($posts !== \FALSE) {
            $output = $posts->getAll();
        }
        
        return [
            'term'  => $term,
            'posts' => $output,
        ];
    }
}
