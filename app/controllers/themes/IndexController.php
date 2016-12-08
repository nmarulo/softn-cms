<?php

/**
 * Modulo controlador: Pagina de inicio de la plantilla de la aplicación.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\helpers\ArrayHelp;
use SoftnCMS\models\admin\Posts;
use SoftnCMS\models\theme\PostsTemplate;
use SoftnCMS\models\theme\Template;

/**
 * Clase IndexController de la pagina inicio de la plantilla de la aplicación.
 * @author Nicolás Marulanda P.
 */
class IndexController extends Controller {
    
    /**
     * Método llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $output     = [];
        $countData  = Posts::count();
        $pagination = new Pagination(ArrayHelp::get($data, 'paged'), $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $posts      = PostsTemplate::selectByLimit($limit);
        Template::setPagination($pagination);
        
        if ($posts !== \FALSE) {
            $output = $posts->getAll();
        }
        
        return [
            'posts' => $output,
        ];
    }
    
}
