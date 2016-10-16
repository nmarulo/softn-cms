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
     * Método llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $id   = ArrayHelp::get($data, 'id');
        $user = UserTemplate::selectByID($id);
        
        if ($user === FALSE) {
            Helps::redirect();
        }
        Template::setTitle(' | ' . $user->getUserName());
        $output     = [];
        $countData  = PostsUserTemplate::count($id);
        $pagination = new Pagination(ArrayHelp::get($data, 'paged'), $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $posts      = PostsUserTemplate::selectByUserIDLimit($id, $limit);
        
        Template::setPagination($pagination);
        
        if ($posts !== \FALSE) {
            $output = $posts->getAll();
        }
        
        return [
            'author' => $user,
            'posts'  => $output,
        ];
    }
}
