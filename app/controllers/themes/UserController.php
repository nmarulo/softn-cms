<?php

/**
 * Modulo controlador: Pagina de usuario de la plantilla de la aplicación.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\helpers\ArrayHelp;
use SoftnCMS\helpers\Helps;
use SoftnCMS\models\theme\PostsUserTemplate;
use SoftnCMS\models\theme\Template;
use SoftnCMS\models\theme\UserTemplate;

/**
 * Clase UserController de la pagina de entradas de la plantilla de la aplicación.
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
        //Se agrega el nombre al titulo de la pagina
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
