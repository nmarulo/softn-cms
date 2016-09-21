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
use SoftnCMS\models\theme\PostsUserTemplate;
use SoftnCMS\models\admin\User;
use SoftnCMS\models\theme\Template;
use SoftnCMS\models\theme\UserTemplate;

/**
 * Description of UserController
 * @author Nicolás Marulanda P.
 */
class UserController extends Controller {
    
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
        $user       = new UserTemplate(User::selectByID($data['id']));
        $countData  = PostsUserTemplate::count($data['id']);
        $pagination = new Pagination($data['paged'], $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $posts      = PostsUserTemplate::selectByID($data['id'], $limit);
    
        $pagination->concatUrl("user/$data[id]");
        
        if ($posts !== \FALSE) {
            $postsTemplate = new PostsTemplate();
            $postsTemplate->addData($posts->getAll());
            $output = $postsTemplate->getAll();
        }
        
        return [
            'author'     => $user,
            'posts'      => $output,
            'pagination' => $pagination,
            'template'   => $template,
        ];
    }
}
