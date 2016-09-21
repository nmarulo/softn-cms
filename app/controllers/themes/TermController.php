<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Pagination;
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
     * Metodo llamado por la función INDEX.
     *
     * @param int $id    Identificador de la categoría.
     * @param int $paged Pagina actual.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $output     = [];
        $template   = new Template();
        $term       = new TermTemplate(Term::selectByID($data['id']));
        $countData  = PostsTermTemplate::count($data['id']);
        $pagination = new Pagination($data['paged'], $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $posts      = PostsTermTemplate::selectByID($data['id'], $limit);
    
        $pagination->concatUrl("term/$data[id]");
        
        if ($posts !== \FALSE) {
            $postsTemplate = new PostsTemplate();
            $postsTemplate->addData($posts->getAll());
            $output = $postsTemplate->getAll();
        }
        
        return [
            'term'       => $term,
            'posts'      => $output,
            'pagination' => $pagination,
            'template'   => $template,
        ];
    }
}
